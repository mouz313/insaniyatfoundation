<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonorController;
use App\Http\Controllers\Admin\BloodDonationController;
use App\Http\Controllers\Admin\BloodRequestController;
use App\Http\Controllers\Admin\CallLogController;
use App\Http\Controllers\Admin\MoneyDonationController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DonorCardController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Portal\VerificationController;
use App\Http\Controllers\Portal\BloodAvailabilityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('donors', DonorController::class);
    Route::resource('blood-donations', BloodDonationController::class);
    Route::resource('blood-requests', BloodRequestController::class);
    Route::resource('call-logs', CallLogController::class);
    Route::resource('money-donations', MoneyDonationController::class);
    Route::resource('campaigns', CampaignController::class);
    Route::resource('cities', CityController::class);
    Route::resource('areas', AreaController::class);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
    Route::post('reports/pdf', [ReportController::class, 'exportPDF'])->name('reports.pdf');
    Route::post('reports/word', [ReportController::class, 'exportWord'])->name('reports.word');

    Route::get('donor-cards', [DonorCardController::class, 'index'])->name('donor-cards.index');
    Route::post('donor-cards/print', [DonorCardController::class, 'printPdf'])->name('donor-cards.print');
    Route::post('donor-cards/mark-printed', [DonorCardController::class, 'markPrinted'])->name('donor-cards.mark-printed');

    Route::resource('staff', StaffController::class);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('verify/{donor}', [VerificationController::class, 'show'])->name('verify');
    Route::get('availability', [BloodAvailabilityController::class, 'index'])->name('availability');
    Route::post('availability/check', [BloodAvailabilityController::class, 'check'])->name('availability.check');
});

require __DIR__.'/auth.php';

Route::get("/test", function() { return "ROUTE WORKS"; });