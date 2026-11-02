<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AdminController,
    ThesisController,
    SupervisionController,
    SupervisorController,
    StudentController,
    EthicsController,
    ProgressReportController,
    DocumentController,
    AnnouncementController,
};

// ── Public ───────────────────────────────────────────────────────────────────

Route::get('/', function () {
    $programmes = \App\Models\Programme::active()->where('applications_open', true)
        ->with('department.school')->orderBy('name')->take(6)->get();
    $programmeCount = \App\Models\Programme::active()->count() ?: '20+';
    $schoolCount = \App\Models\School::count() ?: 9;
    return view('welcome', compact('programmes', 'programmeCount', 'schoolCount'));
})->name('welcome');

require __DIR__ . '/auth.php';

// ── Post-Login Redirect ──────────────────────────────────────────────────────

Route::get('/redirect', function () {
    $user = Auth::user();

    if ($user->hasRole('admin'))           return redirect()->route('admin.dashboard');
    if ($user->hasRole('supervisor'))      return redirect()->route('supervisor.dashboard');
    if ($user->hasRole('student'))         return redirect()->route('student.dashboard');
    if ($user->hasRole('ethics_reviewer')) return redirect()->route('ethics.pending');
    if ($user->hasRole('hod'))             return redirect()->route('admissions.index');
    if ($user->hasRole('dean'))            return redirect()->route('admissions.index');
    if ($user->hasRole('finance'))         return redirect()->route('finance.dashboard');
    if ($user->hasRole('executive'))       return redirect()->route('executive.dashboard');
    if ($user->hasRole('director_bps') || $user->hasRole('registrar') || $user->hasRole('committee'))
                                           return redirect()->route('portal.home');

    return redirect('/');
})->middleware(['auth', 'verified'])->name('redirect');

// ── Authenticated Routes ─────────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    // ── Admin ────────────────────────────────────────────────────────────────
    Route::get('/profile', [\App\Http\Controllers\AccountController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [\App\Http\Controllers\AccountController::class, 'editPassword'])->name('password.edit');
    Route::put('/change-password', [\App\Http\Controllers\AccountController::class, 'updatePassword'])->name('password.change');

    // ── Admin ────────────────────────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/',           [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/monitor',    [AdminController::class, 'monitor'])->name('monitor');
        Route::get('/reports',    [AdminController::class, 'reports'])->name('reports');

        // User management
        Route::get('/users',                   [AdminController::class, 'users'])->name('users');
        Route::get('/users/create',            [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users',                  [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit',       [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}',            [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}',         [AdminController::class, 'deleteUser'])->name('users.delete');

        // Theses
        Route::get('/theses',                              [AdminController::class, 'theses'])->name('theses');
        Route::get('/theses/{thesis}',                     [AdminController::class, 'thesisDetail'])->name('thesis.detail');
        Route::get('/theses/{thesis}/allocate-supervisor', [ThesisController::class, 'allocateSupervisorForm'])->name('thesis.allocate-supervisor.form');
        Route::post('/theses/{thesis}/allocate-supervisor',[ThesisController::class, 'allocateSupervisor'])->name('thesis.allocate-supervisor');

        // Supervisions
        Route::get('/supervisions', [AdminController::class, 'supervisions'])->name('supervisions');

        // Branding (white-label settings)
        Route::get('/branding',   [\App\Http\Controllers\Admin\BrandingController::class, 'edit'])->name('branding.edit');
        Route::put('/branding',   [\App\Http\Controllers\Admin\BrandingController::class, 'update'])->name('branding.update');
        Route::delete('/branding',[\App\Http\Controllers\Admin\BrandingController::class, 'reset'])->name('branding.reset');
    });

    // ── Admin + Supervisor: thesis reviews ───────────────────────────────────
    Route::middleware('role:admin|supervisor')->group(function () {
        Route::get('/reviews',                         [ThesisController::class, 'reviewSubmissions'])->name('admin.thesis-reviews');
        Route::post('/reviews/{submission}',           [ThesisController::class, 'submitReview'])->name('admin.thesis.review');
        Route::post('/thesis/{thesis}/transition',     [ThesisController::class, 'transition'])->name('thesis.transition');
    });

    // ── Supervisor ───────────────────────────────────────────────────────────
    Route::middleware('role:supervisor')->group(function () {
        Route::get('/supervisor',          [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
        Route::get('/supervision/log',     [SupervisionController::class, 'create'])->name('supervision.log');
        Route::post('/meetings',           [SupervisionController::class, 'store'])->name('meetings.store');
        Route::get('/supervisor/progress-reports', [ProgressReportController::class, 'supervisorIndex'])->name('supervisor.progress-reports');

        // Progress reports (supervisor creates)
        Route::get( '/thesis/{thesis}/progress-reports/create', [ProgressReportController::class, 'create'])->name('progress-reports.create');
        Route::post('/thesis/{thesis}/progress-reports',        [ProgressReportController::class, 'store'])->name('progress-reports.store');
    });

    // ── Student ──────────────────────────────────────────────────────────────
   Route::middleware('role:student')->group(function () {
        Route::get('/student',                         [StudentController::class, 'dashboard'])->name('student.dashboard');

        // Apply from within the portal (pre-fills the applicant's own details
        // and links the resulting application to their account).
        Route::get('/apply/portal', [\App\Http\Controllers\ApplicationController::class, 'createForUser'])->name('apply.portal');

        // Thesis workflow
        Route::get('/thesis',                          [ThesisController::class, 'dashboard'])->name('thesis.dashboard');
        Route::post('/thesis/submit-concept',          [ThesisController::class, 'submitConcept'])->name('thesis.submit-concept');
        Route::post('/thesis/submit-proposal',         [ThesisController::class, 'submitProposal'])->name('thesis.submit-proposal');
        Route::post('/thesis/submit-ethics',           [ThesisController::class, 'submitEthicsForm'])->name('thesis.submit-ethics');
        Route::post('/thesis/submit-intent',           [ThesisController::class, 'submitIntentToSubmit'])->name('thesis.submit-intent');
        Route::post('/thesis/submit-thesis',           [ThesisController::class, 'submitThesisDocument'])->name('thesis.submit-thesis');
        Route::post('/thesis/request-meeting',         [ThesisController::class, 'requestMeeting'])->name('thesis.request-meeting');
        Route::get('/thesis/submissions',              [ThesisController::class, 'viewSubmissions'])->name('thesis.submissions');
        Route::get('/thesis/feedback',                 [ThesisController::class, 'viewFeedback'])->name('thesis.feedback');
        Route::get('/thesis/milestones',               [ThesisController::class, 'viewMilestones'])->name('thesis.milestones');

        // Meeting sign-off
        Route::post('/meetings/{meeting}/sign',        [SupervisionController::class, 'signoff'])->name('meetings.sign');

        // Progress report acknowledgement
        Route::post('/progress-reports/{report}/acknowledge', [ProgressReportController::class, 'acknowledge'])->name('progress-reports.acknowledge');
    });

    // ── Document download ────────────────────────────────────────────────────
    Route::get('/documents/{submission}/{index}', [DocumentController::class, 'download'])->name('documents.download');

    // ── Shared: meetings list ────────────────────────────────────────────────
    Route::get('/supervision/meetings', [SupervisionController::class, 'index'])->name('supervision.meetings');

    // ── Shared: progress reports view ────────────────────────────────────────
    Route::middleware('role:admin|supervisor|student|hod|dean')->group(function () {
        Route::get('/thesis/{thesis}/progress-reports', [ProgressReportController::class, 'index'])->name('progress-reports.index');
        Route::get('/progress-reports/{report}',        [ProgressReportController::class, 'show'])->name('progress-reports.show');
    });

    // ── Ethics Reviewer ──────────────────────────────────────────────────────
    Route::middleware('role:ethics_reviewer|admin')->group(function () {
        Route::get('/ethics/pending',              [EthicsController::class, 'pending'])->name('ethics.pending');
        Route::get('/ethics/{submission}',         [EthicsController::class, 'show'])->name('ethics.show');
        Route::post('/ethics/{submission}/review', [EthicsController::class, 'review'])->name('ethics.review');
    });
});

// ── Merged institutional modules ─────────────────────────────────────────────
use App\Http\Controllers\FinanceController;

Route::middleware(['auth', 'verified'])->group(function () {

    // Generic landing for roles whose full module UI is being ported
    Route::get('/home', function () {
        return view('portal-home', ['user' => Auth::user()]);
    })->name('portal.home');

    // Finance office
    Route::middleware('role:finance|admin')->prefix('finance')->name('finance.')->group(function () {
        Route::get('/dashboard', [FinanceController::class, 'dashboard'])->name('dashboard');
        Route::get('/invoices', [FinanceController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/create', [FinanceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [FinanceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{invoice}', [FinanceController::class, 'show'])->name('invoices.show');
        Route::post('/invoices/{invoice}/payment', [FinanceController::class, 'recordPayment'])->name('invoices.payment');
        Route::post('/invoices/{invoice}/waive', [FinanceController::class, 'waive'])->name('invoices.waive');
    });

    // Student fee statement
    Route::middleware('role:student')->get('/student/fees', [FinanceController::class, 'myFees'])->name('student.fees');
});

// ── Admissions: public application (registration) flow ───────────────────────
use App\Http\Controllers\ApplicationController;

Route::get('/apply', [ApplicationController::class, 'create'])->middleware('verified')->name('apply');
Route::get('/programmes', [\App\Http\Controllers\ProgrammeController::class, 'index'])->name('programmes.index');
Route::post('/apply', [ApplicationController::class, 'store'])->middleware('verified')->name('apply.store');
Route::get('/apply/success/{token}', [ApplicationController::class, 'success'])->name('apply.success');
Route::get('/referee/{token}', [ApplicationController::class, 'refereeShow'])->name('referee.show');
Route::post('/referee/{token}', [ApplicationController::class, 'refereeRespond'])->name('referee.respond');
Route::get('/track/{token}', [ApplicationController::class, 'track'])->name('track');
Route::post('/track/{token}/respond', [ApplicationController::class, 'respondToOffer'])->name('track.respond');

// ── Admissions: internal review (HOD / Director / Registrar / Admin) ──────────
Route::middleware(['auth', 'role:hod|dean|director_bps|registrar|admin'])->group(function () {
    Route::get('/admissions', [ApplicationController::class, 'index'])->name('admissions.index');
    Route::get('/admissions/{application}', [ApplicationController::class, 'show'])->name('admissions.show');
    Route::get('/admissions/{application}/document/{type}', [ApplicationController::class, 'previewDocument'])->name('admissions.document');
    Route::get('/admissions/{application}/document/{type}/content', [ApplicationController::class, 'documentContent'])->name('admissions.document.content');
    Route::get('/admissions/{application}/document/{type}/download', [ApplicationController::class, 'downloadDocument'])->name('admissions.document.download');
    Route::get('/admissions/{application}/referee/{referee}/attachment', [ApplicationController::class, 'previewRefereeAttachment'])->name('admissions.referee.attachment');
    Route::get('/admissions/{application}/referee/{referee}/attachment/content', [ApplicationController::class, 'refereeAttachmentContent'])->name('admissions.referee.attachment.content');
    Route::get('/admissions/{application}/referee/{referee}/attachment/download', [ApplicationController::class, 'downloadRefereeAttachment'])->name('admissions.referee.attachment.download');
    Route::post('/admissions/{application}/hod', [ApplicationController::class, 'hodDecision'])->name('admissions.hod');
    Route::post('/admissions/{application}/director', [ApplicationController::class, 'directorDecision'])->name('admissions.director');
});

// ── Admin: academic structure (assign HODs / Deans) ──────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/structure', [\App\Http\Controllers\StructureController::class, 'index'])->name('admin.structure');
    Route::post('/admin/structure/department/{department}', [\App\Http\Controllers\StructureController::class, 'updateDepartment'])->name('admin.structure.department');
    Route::post('/admin/structure/school/{school}', [\App\Http\Controllers\StructureController::class, 'updateSchool'])->name('admin.structure.school');

    Route::resource('/admin/programmes', \App\Http\Controllers\Admin\ProgrammeAdminController::class)
        ->names('admin.programmes')->except('show');
});

// ── Executive / top-management oversight (read-only aggregates) ───────────────
Route::middleware(['auth', 'role:executive|director_bps|dean|admin'])
    ->get('/executive', [\App\Http\Controllers\ExecutiveController::class, 'index'])
    ->name('executive.dashboard');

// announcements
Route::get('/announcements', [AnnouncementController::class, 'index'])
     ->name('announcements.index');

// ── OAuth2 — portal as identity provider (SSO for Moodle etc.) ───────────────
Route::prefix('oauth')->name('oauth.')->group(function () {
    // Browser-facing: needs a logged-in session, so the 'auth' middleware
    // (applied inside the controller) sends unauthenticated visitors to
    // /login first — logging in there IS the single sign-on.
    Route::get('/authorize', [\App\Http\Controllers\OAuth\AuthorizationController::class, 'show'])->name('authorize');
    Route::post('/authorize', [\App\Http\Controllers\OAuth\AuthorizationController::class, 'approve'])->name('approve');
    Route::post('/deny', [\App\Http\Controllers\OAuth\AuthorizationController::class, 'deny'])->name('deny');

    // Server-to-server: called by Moodle itself, not a browser session.
    Route::post('/token', [\App\Http\Controllers\OAuth\TokenController::class, 'issue'])->name('token');
    Route::get('/userinfo', [\App\Http\Controllers\OAuth\UserInfoController::class, 'show'])->name('userinfo');
});

// OpenID Connect discovery — lets Moodle's "Service base URL" field
// auto-discover the three endpoints above (see DiscoveryController).
Route::get('/.well-known/openid-configuration', [\App\Http\Controllers\OAuth\DiscoveryController::class, 'show'])
    ->name('oauth.discovery');

// Branding assets (logo, hero image) — served directly, no storage:link needed.
Route::get('/brand-asset/{path}', [\App\Http\Controllers\BrandAssetController::class, 'show'])
    ->where('path', '.*')->name('brand.asset');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/sso',    [\App\Http\Controllers\Admin\OAuthClientController::class, 'index'])->name('admin.oauth-clients.index');
    Route::post('/admin/sso',   [\App\Http\Controllers\Admin\OAuthClientController::class, 'store'])->name('admin.oauth-clients.store');
    Route::patch('/admin/sso/{oauthClient}/trust',  [\App\Http\Controllers\Admin\OAuthClientController::class, 'toggleTrusted'])->name('admin.oauth-clients.toggle-trusted');
    Route::delete('/admin/sso/{oauthClient}', [\App\Http\Controllers\Admin\OAuthClientController::class, 'destroy'])->name('admin.oauth-clients.destroy');
});
