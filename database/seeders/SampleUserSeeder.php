<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Supervisor;

class SampleUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(['email' => 'admin@postgrad.ac.ke'], [
            'name' => 'System Administrator',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);
        $admin->syncRoles('admin');

        // Supervisor
        $supervisorUser = User::firstOrCreate(['email' => 'supervisor@postgrad.ac.ke'], [
            'name' => 'Dr. Jane Akinyi',
            'password' => Hash::make('supervisor123'),
            'email_verified_at' => now(),
        ]);
        $supervisorUser->syncRoles('supervisor');
        Supervisor::firstOrCreate(['user_id' => $supervisorUser->id], [
            'department' => 'Computer Science',
            'max_capacity' => 5,
        ]);

        // Student
        $studentUser = User::firstOrCreate(['email' => 'student@postgrad.ac.ke'], [
            'name' => 'John Omollo',
            'password' => Hash::make('student123'),
            'email_verified_at' => now(),
        ]);
        $studentUser->syncRoles('student');
        Student::firstOrCreate(['user_id' => $studentUser->id], [
            'reg_no' => 'PG-2024-0001',
            'program' => 'Master of Computer Science',
            'admission_year' => 2024,
        ]);

        // Ethics Reviewer
        $ethicsUser = User::firstOrCreate(['email' => 'ethics@postgrad.ac.ke'], [
            'name' => 'Dr. Ethics Reviewer',
            'password' => Hash::make('ethics123'),
            'email_verified_at' => now(),
        ]);
        $ethicsUser->syncRoles('ethics_reviewer');

        // HOD
        $hodUser = User::firstOrCreate(['email' => 'hod@postgrad.ac.ke'], [
            'name' => 'Prof. Robert Achar',
            'password' => Hash::make('hod123'),
            'email_verified_at' => now(),
        ]);
        $hodUser->syncRoles('hod');

        // Dean
        $deanUser = User::firstOrCreate(['email' => 'dean@postgrad.ac.ke'], [
            'name' => 'Prof. Sarah Wambui',
            'password' => Hash::make('dean123'),
            'email_verified_at' => now(),
        ]);
        $deanUser->syncRoles('dean');

        // Director, Board of Postgraduate Studies
        $directorUser = User::firstOrCreate(['email' => 'director@postgrad.ac.ke'], [
            'name' => 'Prof. Daniel Otieno',
            'password' => Hash::make('director123'),
            'email_verified_at' => now(),
        ]);
        $directorUser->syncRoles('director_bps');

        // Finance
        $financeUser = User::firstOrCreate(['email' => 'finance@postgrad.ac.ke'], [
            'name' => 'Grace Achieng',
            'password' => Hash::make('finance123'),
            'email_verified_at' => now(),
        ]);
        $financeUser->syncRoles('finance');

        // Registrar (Academic)
        $registrarUser = User::firstOrCreate(['email' => 'registrar@postgrad.ac.ke'], [
            'name' => 'Peter Kamau',
            'password' => Hash::make('registrar123'),
            'email_verified_at' => now(),
        ]);
        $registrarUser->syncRoles('registrar');

        // Board committee
        $committeeUser = User::firstOrCreate(['email' => 'committee@postgrad.ac.ke'], [
            'name' => 'Dr. Committee Member',
            'password' => Hash::make('committee123'),
            'email_verified_at' => now(),
        ]);
        $committeeUser->syncRoles('committee');

        // Executive / top management (VC, DVC) — read-only oversight
        $execUser = User::firstOrCreate(['email' => 'executive@postgrad.ac.ke'], [
            'name' => 'Prof. Executive Management',
            'password' => Hash::make('executive123'),
            'email_verified_at' => now(),
        ]);
        $execUser->syncRoles('executive');

        // Sample fee invoices + a part-payment for the demo student
        $student = \App\Models\Student::where('user_id', $studentUser->id)->first();
        if ($student) {
            $inv1 = \App\Models\FeeInvoice::firstOrCreate(
                ['student_id' => $student->id, 'academic_year' => date('Y') . '/' . (date('Y') + 1), 'semester' => 1, 'description' => 'Tuition fee — Year 1, Semester 1'],
                ['amount' => 60000, 'due_date' => now()->subMonths(4), 'created_by' => $financeUser->id]
            );
            if ($inv1->payments()->count() === 0) {
                \App\Models\FeePayment::create([
                    'fee_invoice_id' => $inv1->id, 'student_id' => $student->id,
                    'amount' => 30000, 'method' => 'mpesa', 'reference' => 'QABC1234',
                    'paid_on' => now()->subMonths(3), 'recorded_by' => $financeUser->id,
                ]);
                $inv1->recalcStatus();
            }
            \App\Models\FeeInvoice::firstOrCreate(
                ['student_id' => $student->id, 'academic_year' => date('Y') . '/' . (date('Y') + 1), 'semester' => 2, 'description' => 'Tuition fee — Year 1, Semester 2'],
                ['amount' => 60000, 'due_date' => now()->addMonth(), 'created_by' => $financeUser->id]
            );
        }

        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('==================');
        $this->command->info('Admin:           admin@postgrad.ac.ke      / admin123');
        $this->command->info('Supervisor:      supervisor@postgrad.ac.ke / supervisor123');
        $this->command->info('Student:         student@postgrad.ac.ke    / student123');
        $this->command->info('Ethics Reviewer: ethics@postgrad.ac.ke     / ethics123');
        $this->command->info('HOD:             hod@postgrad.ac.ke        / hod123');
        $this->command->info('Dean:            dean@postgrad.ac.ke       / dean123');
        $this->command->info('Director (BPS):  director@postgrad.ac.ke   / director123');
        $this->command->info('Finance:         finance@postgrad.ac.ke    / finance123');
        $this->command->info('Registrar:       registrar@postgrad.ac.ke  / registrar123');
        $this->command->info('Committee:       committee@postgrad.ac.ke  / committee123');
        $this->command->info('Executive:       executive@postgrad.ac.ke  / executive123');
    }
}
