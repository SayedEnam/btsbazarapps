<?php

use App\Http\Controllers\PublicSiteController;
use App\Http\Controllers\ReferralController;
use App\Livewire\Admin\Accounts\Dashboard as AccountsDashboard;
use App\Livewire\Admin\ActivityLogs\Index as ActivityLogsIndex;
use App\Livewire\Admin\Applications\Index as AdminApplicationsIndex;
use App\Livewire\Admin\Customers\Index as CustomersIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Departments\Index as DepartmentsIndex;
use App\Livewire\Admin\Designations\Index as DesignationsIndex;
use App\Livewire\Admin\ExpenseCategories\Index as ExpenseCategoriesIndex;
use App\Livewire\Admin\Expenses\Index as ExpensesIndex;
use App\Livewire\Admin\FinancialTransactions\Index as FinancialTransactionsIndex;
use App\Livewire\Admin\HeroSlides\Index as HeroSlidesIndex;
use App\Livewire\Admin\Notifications\Index as AdminNotificationsIndex;
use App\Livewire\Admin\Officers\Index as OfficersIndex;
use App\Livewire\Admin\Packages\Index as AdminPackagesIndex;
use App\Livewire\Admin\Pages\Index as PagesIndex;
use App\Livewire\Admin\Payroll\Index as PayrollIndex;
use App\Livewire\Admin\Permissions\Index as PermissionsIndex;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\QaChecklist\Index as QaChecklistIndex;
use App\Livewire\Admin\Referrals\Index as AdminReferralsIndex;
use App\Livewire\Admin\Reports\Index as ReportsIndex;
use App\Livewire\Admin\Roles\Index as RolesIndex;
use App\Livewire\Admin\SalaryPayments\Index as SalaryPaymentsIndex;
use App\Livewire\Admin\SalaryProfiles\Index as SalaryProfilesIndex;
use App\Livewire\Admin\Settings as SettingsPage;
use App\Livewire\Admin\Testimonials\Index as TestimonialsIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Customer\Applications\Create as CustomerApplicationCreate;
use App\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Livewire\Customer\Profile as CustomerProfile;
use App\Livewire\Officer\Applications\Index as OfficerApplicationsIndex;
use App\Livewire\Officer\Dashboard as OfficerDashboard;
use App\Livewire\Officer\Profile as OfficerProfile;
use App\Livewire\Officer\Referrals\Index as OfficerReferralsIndex;
use App\Livewire\Public\Register;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

// Public marketing site.
Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/about', [PublicSiteController::class, 'about'])->name('about');
Route::get('/packages', [PublicSiteController::class, 'packages'])->name('packages');
Route::get('/how-it-works', [PublicSiteController::class, 'howItWorks'])->name('how-it-works');
Route::get('/contact', [PublicSiteController::class, 'contact'])->name('contact');
Route::get('/terms', [PublicSiteController::class, 'terms'])->name('terms');
Route::get('/privacy', [PublicSiteController::class, 'privacy'])->name('privacy');

// Referral capture: /r/{code} stores the code in session, then redirects to
// registration — the code is never relied upon staying in the URL.
Route::get('/r/{code}', [ReferralController::class, 'capture'])->name('referral.capture');

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:'.Role::SUPER_ADMIN.','.Role::ADMIN])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', Dashboard::class)->name('dashboard');
        Route::get('profile', Profile::class)->name('profile');

        Route::get('users', UsersIndex::class)->name('users.index');
        Route::get('roles', RolesIndex::class)->name('roles.index');
        Route::get('permissions', PermissionsIndex::class)->name('permissions.index');

        Route::get('pages', PagesIndex::class)->name('pages.index');
        Route::get('settings', SettingsPage::class)->name('settings');
        Route::get('hero-slides', HeroSlidesIndex::class)->name('hero-slides.index');
        Route::get('testimonials', TestimonialsIndex::class)->name('testimonials.index');

        Route::get('officers', OfficersIndex::class)->name('officers.index');
        Route::get('departments', DepartmentsIndex::class)->name('departments.index');
        Route::get('designations', DesignationsIndex::class)->name('designations.index');

        Route::get('customers', CustomersIndex::class)->name('customers.index');
        Route::get('referrals', AdminReferralsIndex::class)->name('referrals.index');
        Route::get('packages', AdminPackagesIndex::class)->name('packages.index');
        Route::get('applications', AdminApplicationsIndex::class)->name('applications.index');

        Route::get('salary-profiles', SalaryProfilesIndex::class)->name('salary-profiles.index');
        Route::get('payroll', PayrollIndex::class)->name('payroll.index');
        Route::get('salary-payments', SalaryPaymentsIndex::class)->name('salary-payments.index');

        Route::get('expense-categories', ExpenseCategoriesIndex::class)->name('expense-categories.index');
        Route::get('expenses', ExpensesIndex::class)->name('expenses.index');
        Route::get('financial-transactions', FinancialTransactionsIndex::class)->name('financial-transactions.index');
        Route::get('accounts', AccountsDashboard::class)->name('accounts.index');

        Route::get('reports', ReportsIndex::class)->name('reports.index');
        Route::get('activity-logs', ActivityLogsIndex::class)->name('activity-logs.index');
        Route::get('notifications', AdminNotificationsIndex::class)->name('notifications.index');
        Route::get('qa-checklist', QaChecklistIndex::class)->name('qa-checklist.index');
    });

Route::middleware(['auth', 'role:'.Role::CUSTOMER])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('dashboard', CustomerDashboard::class)->name('dashboard');
        Route::get('profile', CustomerProfile::class)->name('profile');
        Route::get('apply', CustomerApplicationCreate::class)->name('apply');
    });

Route::middleware(['auth', 'role:'.Role::MARKETING_OFFICER])
    ->prefix('officer')
    ->name('officer.')
    ->group(function () {
        Route::get('dashboard', OfficerDashboard::class)->name('dashboard');
        Route::get('referrals', OfficerReferralsIndex::class)->name('referrals.index');
        Route::get('applications', OfficerApplicationsIndex::class)->name('applications.index');
        Route::get('profile', OfficerProfile::class)->name('profile');
    });
