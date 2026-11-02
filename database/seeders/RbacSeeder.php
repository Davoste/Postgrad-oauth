<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Unified role/permission matrix for the merged portal.
 *
 * Roles are the UNION of both source apps:
 *   from postgrad : admin, student, supervisor, hod, dean, ethics_reviewer, committee
 *   from generated: director_bps, finance, registrar  (postgrad's "registry" is folded into registrar)
 *
 * Permissions are the union too — postgrad's research/ethics set plus the
 * institutional set (admissions decisions, finance, registrar, admin console).
 */
class RbacSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // platform admin
            'manage_users', 'manage_programmes', 'manage_structure', 'view_audit_log',
            'view_executive_dashboard',
            // admissions
            'view_applications', 'review_applications', 'manage_admissions', 'decide_admission',
            // thesis + research lifecycle
            'view_thesis', 'submit_thesis', 'update_thesis', 'review_thesis', 'assign_examiner',
            // supervision + meetings
            'allocate_supervisor', 'log_meeting', 'approve_meeting',
            // progress reporting (MCPR / BPRF)
            'submit_progress_report', 'view_progress_report', 'acknowledge_progress_report',
            // ethics / IREC
            'review_ethics',
            // finance
            'manage_invoices', 'record_payments', 'waive_fees', 'clear_thesis_finance',
            // registrar / records
            'manage_student_records', 'export_students', 'change_student_status',
            'view_clearance', 'generate_transcript',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'admin', 'student', 'supervisor', 'hod', 'dean',
            'director_bps', 'finance', 'registrar', 'ethics_reviewer', 'committee', 'executive',
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Admin — everything
        Role::findByName('admin')->syncPermissions(Permission::all());

        Role::findByName('student')->syncPermissions([
            'view_thesis', 'submit_thesis', 'log_meeting', 'acknowledge_progress_report',
        ]);

        Role::findByName('supervisor')->syncPermissions([
            'view_thesis', 'update_thesis', 'review_thesis',
            'log_meeting', 'approve_meeting',
            'submit_progress_report', 'view_progress_report',
        ]);

        Role::findByName('hod')->syncPermissions([
            'view_applications', 'review_applications', 'decide_admission',
            'view_thesis', 'update_thesis', 'review_thesis',
            'view_progress_report', 'allocate_supervisor',
        ]);

        Role::findByName('dean')->syncPermissions([
            'view_executive_dashboard',
            'view_applications', 'view_thesis', 'review_thesis',
            'view_progress_report', 'allocate_supervisor',
        ]);

        // Director, Board of Postgraduate Studies — final academic authority
        Role::findByName('director_bps')->syncPermissions([
            'view_executive_dashboard',
            'view_applications', 'review_applications', 'manage_admissions', 'decide_admission',
            'view_thesis', 'review_thesis', 'assign_examiner',
            'view_progress_report', 'allocate_supervisor', 'view_clearance',
        ]);

        Role::findByName('finance')->syncPermissions([
            'manage_invoices', 'record_payments', 'waive_fees',
            'clear_thesis_finance', 'view_clearance',
        ]);

        Role::findByName('registrar')->syncPermissions([
            'manage_student_records', 'export_students', 'change_student_status',
            'generate_transcript', 'view_clearance', 'view_applications',
        ]);

        // Executive / top management (VC, DVC) — read-only oversight
        Role::findByName('executive')->syncPermissions([
            'view_executive_dashboard',
        ]);

        Role::findByName('ethics_reviewer')->syncPermissions([
            'review_ethics', 'view_thesis',
        ]);

        Role::findByName('committee')->syncPermissions([
            'assign_examiner', 'review_thesis', 'view_thesis', 'view_clearance',
        ]);
    }
}
