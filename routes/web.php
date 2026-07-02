<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonorController;
use App\Http\Controllers\Admin\BloodDonationController;
use App\Http\Controllers\Admin\BloodInventoryController;
use App\Http\Controllers\Admin\BloodRequestController;
use App\Http\Controllers\Admin\CallLogController;
use App\Http\Controllers\Admin\MoneyDonationController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\GlobalSearchController;
use App\Http\Controllers\Admin\MatchingController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DonorCardController;
use App\Http\Controllers\Portal\RegistrationController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\Admin\DonorStoryController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Portal\LandingPageController as PortalLandingController;
use App\Http\Controllers\Portal\VerificationController;
use App\Http\Controllers\Portal\BloodAvailabilityController;

Route::get('/', [PortalLandingController::class, 'index'])->name('portal.landing');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('donors/check-duplicate', [DonorController::class, 'checkDuplicate'])->name('donors.check-duplicate');
    Route::get('donors/search-referrer', [DonorController::class, 'searchReferrer'])->name('donors.search-referrer');
    Route::get('donors/{donor}/certificate', [DonorController::class, 'certificate'])->name('donors.certificate');
    Route::resource('donors', DonorController::class)->middleware('permission:donor-show|donor-create|donor-edit|donor-delete');

    Route::resource('blood-donations', BloodDonationController::class)->middleware('permission:blood-donation-show|blood-donation-create|blood-donation-edit|blood-donation-delete');
    Route::get('money-donations/{moneyDonation}/receipt', [MoneyDonationController::class, 'printReceipt'])->name('money-donations.receipt')->middleware('permission:money-donation-show');

    Route::resource('blood-inventory', BloodInventoryController::class)->middleware('permission:inventory-show|inventory-create|inventory-edit|inventory-delete');

    Route::resource('blood-requests', BloodRequestController::class)->middleware('permission:blood-request-show|blood-request-create|blood-request-edit|blood-request-delete');
    Route::get('patients/by-blood-group/{bloodGroup}', [BloodRequestController::class, 'getByBloodGroup'])->name('patients.by-blood-group')->middleware('permission:blood-request-show');
    Route::get('donors/by-blood-group/{bloodGroup}', [BloodRequestController::class, 'getDonorsByBloodGroup'])->name('donors.by-blood-group')->middleware('permission:blood-request-show');
    Route::get('blood-requests/{bloodRequest}/match', [MatchingController::class, 'index'])->name('blood-requests.match')->middleware('permission:blood-request-match');
    Route::post('blood-requests/{bloodRequest}/match/{donor}/call', [MatchingController::class, 'logCall'])->name('blood-requests.match.call')->middleware('permission:blood-request-match');

    Route::resource('call-logs', CallLogController::class)->middleware('permission:call-log-create|call-log-edit|call-log-delete');

    Route::resource('money-donations', MoneyDonationController::class)->middleware('permission:money-donation-show|money-donation-create|money-donation-edit|money-donation-delete');

    Route::resource('campaigns', CampaignController::class)->middleware('permission:campaign-show|campaign-create|campaign-edit|campaign-delete');
    Route::get('campaigns/{campaign}/attendance', [AttendanceController::class, 'print'])->name('campaigns.attendance')->middleware('permission:campaign-show');

    Route::resource('cities', CityController::class);
    Route::get('areas/by-city/{cityId}', [AreaController::class, 'getByCity'])->name('areas.by-city');
    Route::resource('areas', AreaController::class);

    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::post('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('profile/remove-photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');

    Route::get('search', [GlobalSearchController::class, 'search'])->name('search');

    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:view-reports');
    Route::match(['get', 'post'], 'reports/generate', [ReportController::class, 'generate'])->name('reports.generate')->middleware('permission:view-reports');
    Route::match(['get', 'post'], 'reports/pdf', [ReportController::class, 'exportPDF'])->name('reports.pdf')->middleware('permission:view-reports');
    Route::match(['get', 'post'], 'reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel')->middleware('permission:view-reports');
    Route::match(['get', 'post'], 'reports/word', [ReportController::class, 'exportWord'])->name('reports.word')->middleware('permission:view-reports');

    Route::get('donor-cards', [DonorCardController::class, 'index'])->name('donor-cards.index')->middleware('permission:donor-show');
    Route::post('donor-cards/print', [DonorCardController::class, 'printPdf'])->name('donor-cards.print')->middleware('permission:donor-show');
    Route::post('donor-cards/mark-printed', [DonorCardController::class, 'markPrinted'])->name('donor-cards.mark-printed')->middleware('permission:donor-edit');

    Route::resource('staff', StaffController::class)->middleware('permission:staff-create|staff-edit|staff-delete');

    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->middleware('permission:manage-settings');
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->middleware('permission:manage-settings');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index')->middleware('permission:manage-settings');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:manage-settings');
    Route::get('settings/remove-logo', [SettingController::class, 'removeLogo'])->name('settings.remove-logo')->middleware('permission:manage-settings');
    Route::get('settings/remove-favicon', [SettingController::class, 'removeFavicon'])->name('settings.remove-favicon')->middleware('permission:manage-settings');
    Route::post('settings/run-command', [SettingController::class, 'runCommand'])->name('settings.run-command')->middleware('permission:manage-settings');

    Route::get('landing-page', [LandingPageController::class, 'index'])->name('landing-page.index');
    Route::put('landing-page', [LandingPageController::class, 'update'])->name('landing-page.update');

    Route::resource('donor-stories', DonorStoryController::class);

    Route::resource('universities', UniversityController::class)->middleware('permission:manage-settings');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('register', [RegistrationController::class, 'create'])->name('registration.create');
    Route::post('register', [RegistrationController::class, 'store'])->name('registration.store');
    Route::get('register/{donor}/confirm', [RegistrationController::class, 'confirm'])->name('registration.confirm');
    Route::get('verify/{donor}', [VerificationController::class, 'show'])->name('verify');
    Route::get('availability', [BloodAvailabilityController::class, 'index'])->name('availability');

    Route::get('donors/search-referrer', [RegistrationController::class, 'searchReferrer'])->name('donors.search-referrer');
    Route::get('referrer/{cnic}', [VerificationController::class, 'referrer'])->name('referrer');
    Route::get('areas/by-city/{cityId}', [\App\Http\Controllers\Admin\AreaController::class, 'getByCity'])->name('areas.by-city');
});

Route::get('locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ur'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');

require __DIR__.'/auth.php';
