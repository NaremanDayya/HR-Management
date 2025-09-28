<?php

use App\Http\Controllers\EmployeeLoginIpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeActionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\EmployeeWorkHistoryController;
use App\Services\ImageMessageService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/employees/credentials', function () {
    $csvPath = 'exports/employees_credentials.csv';

    if (!Storage::exists($csvPath)) {
        return redirect()->back()->with('error', 'No credentials data available yet');
    }

    $credentials = [];
    $file = Storage::get($csvPath);
    $lines = preg_split('/\r\n|\r|\n/', $file);

    for ($i = 1; $i < count($lines); $i++) {
        $line = trim($lines[$i]);
        if (!empty($line)) {
            $credentials[] = str_getcsv($line);
        }
    }

    return view('Employees.credentials', compact('credentials'));
})->name('employees.credentials');
Route::middleware(['auth', App\Http\Middleware\CheckProjectManagerEmployeeAccess::class])
    ->prefix('employees')->name('employees.')->group(function () {
        // Route::middleware(['auth'])->prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/actions', [EmployeeController::class, 'actions'])->name('actions');
        Route::get('/alerts', [EmployeeController::class, 'allAlerts'])->name('alerts.all');
        Route::get('/advances', [EmployeeController::class, 'allAdvances'])->name('advances.all');
        Route::get('/advances_deductions', [EmployeeController::class, 'allAdvancesDeductions'])->name('advances.deductions.all');
        Route::get('/deductions', [EmployeeController::class, 'allDeductions'])->name('deductions.all');
        Route::get('/increases', [EmployeeController::class, 'allIncreases'])->name('increases.all');
        Route::get('/assignments', [EmployeeController::class, 'allTemporaryProjectAssignments'])->name('assignments.all');
        Route::get('/replacements', [EmployeeController::class, 'allReplacements'])->name('replacements.all');
        Route::get('/histories', [EmployeeWorkHistoryController::class, 'getWorkHistory'])->name('histories.all');
        Route::get('/projects/{projectId}/employees', [EmployeeController::class, 'employeesByProject']);
        Route::get('/managers/{managerId}/employees', [EmployeeController::class, 'employeesByManager']);
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
        Route::post('/replace', [EmployeeController::class, 'replace'])->name('replace');
        Route::get('/{employee}/replacements', [EmployeeController::class, 'showReplacements'])->name('replacements');
        Route::post('/bulk/{action}', [EmployeeActionController::class, 'bulkAction'])->name('action');
        Route::get('/{employee}/alerts', [EmployeeController::class, 'showAlerts'])->name('alerts');
        Route::get('/{employee}/deductions', [EmployeeController::class, 'showDeductions'])->name('deductions');
        Route::get('/{employee}/advances', [EmployeeController::class, 'showAdvances'])->name('advances');
        Route::get('/{employee}/advances_deductions', [EmployeeController::class, 'showAdvanceDeductions'])->name('advances_deductions');
        Route::get('/{employee}/increases', [EmployeeController::class, 'showIncreases'])->name('increases');
        Route::get('/{employee}/history', [EmployeeWorkHistoryController::class, 'getWorkHistory'])->name('histories');
        Route::get('/{employee}/assignments', [EmployeeController::class, 'showTemporaryProjectAssignments'])->name('assignments');
        Route::put('/{employee}/change-password', [EmployeeController::class, 'changePassword'])->name('change-password');
        Route::post('/{employee}/update-photo', [EmployeeController::class, 'updatePhoto'])->name('updatePhoto');
    });
Route::middleware(['auth'])->prefix('/admin/settings/')->name('settings.')->group(function () {
    Route::post('age-threshold', [SettingController::class, 'updateAgeThreshold'])->name('age_threshold.update');
});
Route::get('manager/temporary-assignments', [EmployeeController::class, 'temporaryAssignmentsView'])
    ->name('manager.temporary.assignments')->middleware('auth');
Route::post('/admin/update-photo', [DashboardController::class, 'updatePhoto'])->name('admin.updatePhoto');

Route::middleware([
    'auth',
    App\Http\Middleware\AdminRoleMiddleware::class
])->group(function () {

    Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::get('roles/{role}/permissions', [DashboardController::class, 'edit'])->name('admin.roles.permissions.edit');
        Route::put('roles/{role}/permissions', [DashboardController::class, 'update'])->name('admin.roles.permissions.update');
        Route::post('roles', [DashboardController::class, 'store'])->name('admin.roles.store');
    });
    Route::post('/admin/change-password', [DashboardController::class, 'changePassword'])->name('admin.change-password')->middleware('auth');

    Route::get('/financials', [EmployeeController::class, 'Allfinancials'])->name('financials.all');
});

Route::resource('projects', ProjectController::class)->middleware('auth');
Route::get('/projects-statistics', [ProjectController::class, 'projectStatistics'])->name('projects-statistics');
Route::get('/projects/{project}/statistics', [ProjectController::class, 'showStatistics'])->name('project.statistics');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/employee-ips', [EmployeeLoginIpController::class, 'index'])->name('admin.employee-ips.index');
    Route::post('/admin/employee-ips/{employeeLoginIp}/block', [EmployeeLoginIpController::class, 'block'])->name('admin.employee-ips.block');
    Route::post('/admin/employee-ips/{employeeLoginIp}/unblock', [EmployeeLoginIpController::class, 'unblock'])->name('admin.employee-ips.unblock');
    Route::post('/admin/employee-ips/{employee}/add-temp-ip', [EmployeeLoginIpController::class, 'addTemporaryIp'])->name('admin.employee-ips.add-temp-ip');
});


Route::middleware(['auth'])->prefix('EmployeeEditRequest')->name('employee-request.')->group(function () {
    Route::post('/', [EmployeeRequestController::class, 'storeEditRequest'])->name('store');
    Route::get('/', [EmployeeRequestController::class, 'index'])->name('index');
    Route::post('/{id}/status', [EmployeeRequestController::class, 'changeStatus'])->name('change-status');
});
Route::middleware(['auth', 'web', \App\Http\Middleware\MarkNotificationAsRead::class])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);
});

Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
    ->middleware(['auth', 'web'])
    ->name('notifications.markAllAsRead');

Route::get('/notification/open', [NotificationController::class, 'open'])
    ->middleware(['auth', \App\Http\Middleware\MarkNotificationAsRead::class])
    ->name('notification.redirect');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');


Route::get('/test-upload', function () {
    return '<form method="POST" action="/s3-upload-test" enctype="multipart/form-data">
                ' . csrf_field() . '
                <input type="file" name="image">
                <button type="submit">Upload</button>
            </form>';
})->middleware('auth');

Route::post('/s3-upload-test', function (Request $request) {
    if (!$request->hasFile('image')) {
        return response()->json(['error' => 'No file uploaded.']);
    }

    $file = $request->file('image');
    $originalName = $file->getClientOriginalName();
    $tempPath = $file->getPathname();
    $size = $file->getSize();

    // Debug info
    dump('Original name: ' . $originalName);
    dump('Temp path: ' . $tempPath);
    dump('Size: ' . $size);

    try {
        $disk = Storage::disk('s3');
        $path = $disk->putFile('test-uploads', $file);

        if (!$path) {
            return response()->json([
                'error' => 'Failed to store the file. Path is empty.',
                'disk_config' => config('filesystems.disks.s3')
            ]);
        }

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => $disk->url($path)
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
})->middleware('auth');


Route::get('/s3-test', function () {
    try {
        $disk = Storage::disk('s3');

        // Debug S3 configuration
        $config = method_exists($disk->getDriver(), 'getAdapter')
            ? $disk->getDriver()->getAdapter()->getClient()->getConfig()->toArray()
            : null;

        // Test upload
        $result = $disk->put('test.txt', 'Hello S3!');

        return response()->json([
            's3_config' => $config,
            'upload_result' => $result ? 'Upload succeeded!' : 'Upload failed!',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ], 500);
    }
});
