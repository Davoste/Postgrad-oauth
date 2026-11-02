<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 30)->unique();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('academic_year', 9);
            $table->unsignedTinyInteger('semester')->default(1);
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->enum('status', ['unpaid', 'partial', 'paid', 'waived'])->default('unpaid');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no', 30)->unique();
            $table->foreignId('fee_invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['bank', 'mpesa', 'cheque', 'cash', 'waiver', 'other'])->default('bank');
            $table->string('reference')->nullable();
            $table->date('paid_on');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
        Schema::dropIfExists('fee_invoices');
    }
};
