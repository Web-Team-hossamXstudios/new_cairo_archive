<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\LandController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\PhysicalLocationController;
use App\Http\Controllers\Admin\GeographicAreaController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', [AuthenticatedSessionController::class, 'create']);

// Redirect /dashboard to admin dashboard
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboards.admin.pages.dashboard.index');
    })->name('dashboard');

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/remove-avatar', [ProfileController::class, 'removeAvatar'])->name('remove-avatar');
    });

    // Orders (placeholder)
    Route::get('/orders/create', function () {
        return redirect()->route('admin.dashboard');
    })->name('orders.create');

    // Clients Management
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::get('/generate-code', [ClientController::class, 'generateCode'])->name('generate-code');
        Route::get('/search-barcode', [ClientController::class, 'searchByBarcode'])->name('search-barcode');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::post('/bulk-delete', [ClientController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-restore', [ClientController::class, 'bulkRestore'])->name('bulk-restore');
        Route::post('/bulk-force-delete', [ClientController::class, 'bulkForceDelete'])->name('bulk-force-delete');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::get('/{client}/lands', [ClientController::class, 'getLands'])->name('lands');
        Route::post('/{client}', [ClientController::class, 'update'])->name('update');
        Route::post('/{client}/delete', [ClientController::class, 'destroy'])->name('destroy');
        Route::post('/{client}/restore', [ClientController::class, 'restore'])->name('restore');
        Route::post('/{client}/force-delete', [ClientController::class, 'forceDelete'])->name('force-delete');
    });

    // Lands Management
    Route::prefix('lands')->name('lands.')->group(function () {
        Route::get('/', [LandController::class, 'index'])->name('index');
        Route::post('/', [LandController::class, 'store'])->name('store');
        Route::post('/bulk-delete', [LandController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-restore', [LandController::class, 'bulkRestore'])->name('bulk-restore');
        Route::post('/bulk-force-delete', [LandController::class, 'bulkForceDelete'])->name('bulk-force-delete');
        Route::get('/{land}', [LandController::class, 'show'])->name('show');
        Route::post('/{land}', [LandController::class, 'update'])->name('update');
        Route::post('/{land}/delete', [LandController::class, 'destroy'])->name('destroy');
        Route::post('/{land}/restore', [LandController::class, 'restore'])->name('restore');
        Route::post('/{land}/force-delete', [LandController::class, 'forceDelete'])->name('force-delete');
        Route::get('/cities/{governorate}', [LandController::class, 'getCities'])->name('cities');
        Route::get('/districts/{city}', [LandController::class, 'getDistricts'])->name('districts');
        Route::get('/zones/{district}', [LandController::class, 'getZones'])->name('zones');
        Route::get('/areas/{zone}', [LandController::class, 'getAreas'])->name('areas');
    });

    // Files Management
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/', [FileController::class, 'index'])->name('index');
        Route::get('/create', [FileController::class, 'create'])->name('create');
        Route::get('/barcode/{barcode}', [FileController::class, 'findByBarcode'])->name('barcode');
        Route::post('/', [FileController::class, 'store'])->name('store');
        Route::post('/bulk-delete', [FileController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-restore', [FileController::class, 'bulkRestore'])->name('bulk-restore');
        Route::post('/bulk-force-delete', [FileController::class, 'bulkForceDelete'])->name('bulk-force-delete');
        Route::get('/{file}', [FileController::class, 'show'])->name('show');
        Route::get('/{file}/edit', [FileController::class, 'edit'])->name('edit');
        Route::post('/{file}/update', [FileController::class, 'update'])->name('update');
        Route::post('/{file}/delete', [FileController::class, 'destroy'])->name('destroy');
        Route::get('/{file}/view', [FileController::class, 'view'])->name('view');
        Route::get('/{file}/download', [FileController::class, 'download'])->name('download');
        Route::post('/{file}/retry', [FileController::class, 'retry'])->name('retry');
        Route::get('/{file}/pages', [FileController::class, 'getPages'])->name('pages');
        Route::post('/{file}/upload-document', [FileController::class, 'uploadDocument'])->name('upload-document');
    });

    // Physical Locations Management
    Route::prefix('physical-locations')->name('physical-locations.')->group(function () {
        Route::get('/', [PhysicalLocationController::class, 'index'])->name('index');

        // Rooms
        Route::post('/rooms', [PhysicalLocationController::class, 'storeRoom'])->name('rooms.store');
        Route::get('/rooms/{room}/show', [PhysicalLocationController::class, 'showRoom'])->name('rooms.show');
        Route::post('/rooms/{room}', [PhysicalLocationController::class, 'updateRoom'])->name('rooms.update');
        Route::post('/rooms/{room}/delete', [PhysicalLocationController::class, 'destroyRoom'])->name('rooms.destroy');
        Route::get('/rooms/{room}/lanes', [PhysicalLocationController::class, 'getLanes'])->name('rooms.lanes');

        // Lanes
        Route::post('/lanes', [PhysicalLocationController::class, 'storeLane'])->name('lanes.store');
        Route::post('/lanes/{lane}', [PhysicalLocationController::class, 'updateLane'])->name('lanes.update');
        Route::post('/lanes/{lane}/delete', [PhysicalLocationController::class, 'destroyLane'])->name('lanes.destroy');
        Route::get('/lanes/{lane}/stands', [PhysicalLocationController::class, 'getStands'])->name('lanes.stands');

        // Stands
        Route::post('/stands', [PhysicalLocationController::class, 'storeStand'])->name('stands.store');
        Route::post('/stands/{stand}', [PhysicalLocationController::class, 'updateStand'])->name('stands.update');
        Route::post('/stands/{stand}/delete', [PhysicalLocationController::class, 'destroyStand'])->name('stands.destroy');
        Route::get('/stands/{stand}/racks', [PhysicalLocationController::class, 'getRacks'])->name('stands.racks');

        // Racks
        Route::post('/racks', [PhysicalLocationController::class, 'storeRack'])->name('racks.store');
        Route::post('/racks/{rack}', [PhysicalLocationController::class, 'updateRack'])->name('racks.update');
        Route::post('/racks/{rack}/delete', [PhysicalLocationController::class, 'destroyRack'])->name('racks.destroy');
        Route::get('/racks/{rack}/files', [PhysicalLocationController::class, 'getRackFiles'])->name('racks.files');
    });

    // Geographic Areas Management
    Route::prefix('geographic-areas')->name('geographic-areas.')->group(function () {
        Route::get('/', [GeographicAreaController::class, 'index'])->name('index');

        // Governorates
        Route::post('/governorates', [GeographicAreaController::class, 'storeGovernorate'])->name('governorates.store');
        Route::get('/governorates/{governorate}/show', [GeographicAreaController::class, 'showGovernorate'])->name('governorates.show');
        Route::post('/governorates/{governorate}', [GeographicAreaController::class, 'updateGovernorate'])->name('governorates.update');
        Route::post('/governorates/{governorate}/delete', [GeographicAreaController::class, 'destroyGovernorate'])->name('governorates.destroy');

        // Helper routes for cascading dropdowns
        Route::get('/cities/by-governorate/{governorate}', [GeographicAreaController::class, 'getCitiesByGovernorate'])->name('cities.by-governorate');
        Route::get('/districts/by-city/{city}', [GeographicAreaController::class, 'getDistrictsByCity'])->name('districts.by-city');
        Route::get('/zones/by-district/{district}', [GeographicAreaController::class, 'getZonesByDistrict'])->name('zones.by-district');
        Route::get('/areas/by-zone/{zone}', [GeographicAreaController::class, 'getAreasByZone'])->name('areas.by-zone');

        // Cities
        Route::post('/cities', [GeographicAreaController::class, 'storeCity'])->name('cities.store');
        Route::get('/cities/{city}', [GeographicAreaController::class, 'showCity'])->name('cities.show');
        Route::post('/cities/{city}', [GeographicAreaController::class, 'updateCity'])->name('cities.update');
        Route::post('/cities/{city}/delete', [GeographicAreaController::class, 'destroyCity'])->name('cities.destroy');

        // Districts
        Route::post('/districts', [GeographicAreaController::class, 'storeDistrict'])->name('districts.store');
        Route::get('/districts/{district}', [GeographicAreaController::class, 'showDistrict'])->name('districts.show');
        Route::post('/districts/{district}', [GeographicAreaController::class, 'updateDistrict'])->name('districts.update');
        Route::post('/districts/{district}/delete', [GeographicAreaController::class, 'destroyDistrict'])->name('districts.destroy');

        // Zones
        Route::post('/zones', [GeographicAreaController::class, 'storeZone'])->name('zones.store');
        Route::get('/zones/{zone}', [GeographicAreaController::class, 'showZone'])->name('zones.show');
        Route::post('/zones/{zone}', [GeographicAreaController::class, 'updateZone'])->name('zones.update');
        Route::post('/zones/{zone}/delete', [GeographicAreaController::class, 'destroyZone'])->name('zones.destroy');

        // Areas
        Route::post('/areas', [GeographicAreaController::class, 'storeArea'])->name('areas.store');
        Route::get('/areas/{area}', [GeographicAreaController::class, 'showArea'])->name('areas.show');
        Route::post('/areas/{area}', [GeographicAreaController::class, 'updateArea'])->name('areas.update');
        Route::post('/areas/{area}/delete', [GeographicAreaController::class, 'destroyArea'])->name('areas.destroy');
        Route::get('/areas/{area}/lands', [GeographicAreaController::class, 'getAreaLands'])->name('areas.lands');
    });

    // Items Management
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::post('/bulk-delete', [ItemController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-restore', [ItemController::class, 'bulkRestore'])->name('bulk-restore');
        Route::post('/bulk-force-delete', [ItemController::class, 'bulkForceDelete'])->name('bulk-force-delete');
        Route::post('/{item}', [ItemController::class, 'update'])->name('update');
        Route::post('/{item}/delete', [ItemController::class, 'destroy'])->name('destroy');
        Route::post('/{item}/restore', [ItemController::class, 'restore'])->name('restore');
        Route::post('/{item}/force-delete', [ItemController::class, 'forceDelete'])->name('force-delete');
    });

    // Import Management
    Route::prefix('imports')->name('imports.')->group(function () {
        Route::get('/', [ImportController::class, 'index'])->name('index');
        Route::get('/create', [ImportController::class, 'create'])->name('create');
        Route::post('/upload', [ImportController::class, 'upload'])->name('upload');
        Route::get('/{import}/validate', [ImportController::class, 'validateImport'])->name('validate');
        Route::post('/{import}/execute', [ImportController::class, 'execute'])->name('execute');
        Route::get('/{import}', [ImportController::class, 'show'])->name('show');
        Route::get('/{import}/status', [ImportController::class, 'status'])->name('status');
        Route::get('/template/download', [ImportController::class, 'downloadTemplate'])->name('template');
        Route::get('/{import}/errors', [ImportController::class, 'downloadErrors'])->name('errors');
    });
      // Users Management
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('/create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('/bulk-restore', [UserController::class, 'bulkRestore'])->name('bulk-restore');
                Route::post('/bulk-force-delete', [UserController::class, 'bulkForceDelete'])->name('bulk-force-delete');
                Route::post('/bulk-upload', [UserController::class, 'bulkUpload'])->name('bulk-upload');
                Route::get('/bulk-download', [UserController::class, 'bulkDownload'])->name('bulk-download');
                Route::get('/download-sample', [UserController::class, 'downloadSample'])->name('download-sample');
                Route::get('/{id}', [UserController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
                Route::post('/{id}', [UserController::class, 'update'])->name('update');
                Route::post('/{id}/destroy', [UserController::class, 'destroy'])->name('destroy');
                Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
                Route::post('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete');
                Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
                Route::post('/{id}/assign-role', [UserController::class, 'assignRole'])->name('assign-role');
                Route::post('/{id}/change-password', [UserController::class, 'changePassword'])->name('change-password');
            });

            // Roles Management
            Route::prefix('roles')->name('roles.')->group(function () {
                Route::get('/', [RoleController::class, 'index'])->name('index');
                Route::get('/create', [RoleController::class, 'create'])->name('create');
                Route::post('/', [RoleController::class, 'store'])->name('store');
                Route::post('/bulk-delete', [RoleController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('/bulk-download', [RoleController::class, 'bulkDownload'])->name('bulk-download');
                Route::get('/{id}', [RoleController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
                Route::post('/{id}', [RoleController::class, 'update'])->name('update');
                Route::post('/{id}/destroy', [RoleController::class, 'destroy'])->name('destroy');
                Route::post('/{id}/sync-permissions', [RoleController::class, 'syncPermissions'])->name('sync-permissions');
            });
});

require __DIR__.'/auth.php';
