<?php

namespace App\Http\Controllers;

use App\Models\{FeeInvoice, FeePayment, Student, Thesis};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class FinanceController extends Controller
{
    public function dashboard()
    {
        $summary = [
            'billed'      => FeeInvoice::sum('amount'),
            'collected'   => FeeInvoice::sum('amount_paid'),
            'outstanding' => FeeInvoice::whereIn('status', ['unpaid', 'partial'])->get()->sum(fn ($i) => $i->balance),
            'invoices'    => FeeInvoice::count(),
        ];
        $recentPayments = FeePayment::with('student.user')->latest('paid_on')->take(8)->get();
        return view('finance.dashboard', compact('summary', 'recentPayments'));
    }

    public function invoices(Request $request)
    {
        $query = FeeInvoice::with('student.user');
        if ($status = $request->get('status')) $query->where('status', $status);
        if ($q = $request->get('q')) {
            $query->where(function ($x) use ($q) {
                $x->where('invoice_no', 'like', "%{$q}%")
                  ->orWhereHas('student.user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            });
        }
        $invoices = $query->latest()->paginate(20)->withQueryString();
        $summary = [
            'billed'      => FeeInvoice::sum('amount'),
            'collected'   => FeeInvoice::sum('amount_paid'),
            'outstanding' => FeeInvoice::whereIn('status', ['unpaid', 'partial'])->get()->sum(fn ($i) => $i->balance),
        ];
        return view('finance.invoices', compact('invoices', 'summary'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        return view('finance.invoice-create', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'academic_year' => 'required|string|max:9',
            'semester'      => 'required|integer|min:1|max:3',
            'description'   => 'required|string|max:160',
            'amount'        => 'required|numeric|min:0',
            'due_date'      => 'nullable|date',
        ]);
        $invoice = FeeInvoice::create($data + ['created_by' => Auth::id()]);
        return redirect()->route('finance.invoices')->with('success', "Invoice {$invoice->invoice_no} created.");
    }

    public function show(FeeInvoice $invoice)
    {
        $invoice->load('student.user', 'payments.recordedBy', 'createdBy');
        return view('finance.invoice-show', compact('invoice'));
    }

    public function recordPayment(Request $request, FeeInvoice $invoice)
    {
        $data = $request->validate([
            'amount'    => 'required|numeric|min:0.01',
            'method'    => 'required|in:bank,mpesa,cheque,cash,waiver,other',
            'reference' => 'nullable|string|max:120',
            'paid_on'   => 'required|date',
            'notes'     => 'nullable|string|max:255',
        ]);
        DB::transaction(function () use ($invoice, $data) {
            FeePayment::create($data + [
                'fee_invoice_id' => $invoice->id,
                'student_id'     => $invoice->student_id,
                'recorded_by'    => Auth::id(),
            ]);
            $invoice->recalcStatus();
        });
        return back()->with('success', 'Payment recorded.');
    }

    public function waive(FeeInvoice $invoice)
    {
        $invoice->update(['status' => 'waived']);
        return back()->with('success', 'Invoice waived.');
    }

    // Student-facing fee statement
    public function myFees()
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);
        $student->load('feeInvoices.payments', 'feePayments');
        return view('finance.my-fees', compact('student'));
    }
}
