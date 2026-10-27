<?php

use App\Http\Controllers\Finance\FinanceCategoryController;
use App\Http\Controllers\Finance\FinanceMerchantController;
use App\Http\Controllers\Finance\FinanceTransactionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkSpacesController;
use App\Http\Controllers\WorkSpaceMembersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

//alfay merubah sesuatu aku ubah lagi

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('api-token', [UserController::class, 'getApiToken'])->name('api-token');

    Route::get('switch-workspace/{workspaceId}', [WorkSpacesController::class, 'switchWorkspace'])->name('switch-workspace');

    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
    Route::put('settings/appearance', [Settings\AppearanceController::class, 'update'])->name('settings.appearance.update');

    // Roles Management - dengan permission check
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:view-roles');
    Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export')->middleware('permission:download-roles');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:create-roles');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:create-roles');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:show-roles');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit-roles');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:edit-roles');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete-roles');
    
    // Permissions Management - dengan permission check
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:view-permissions');
    Route::get('permissions/export', [PermissionController::class, 'export'])->name('permissions.export')->middleware('permission:download-permissions');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:create-permissions');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:create-permissions');
    Route::get('permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show')->middleware('permission:show-permissions');
    Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:edit-permissions');
    Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:edit-permissions');
    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:delete-permissions');
    
    // Users Management - dengan permission check
    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view-users');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export')->middleware('permission:download-users');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create-users');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create-users');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:show-users');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit-users');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit-users');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete-users');

    // Work Spaces Management - dengan permission check
    Route::get('work-spaces', [WorkSpacesController::class, 'index'])->name('work-spaces.index')->middleware('permission:view-work-spaces');
    Route::get('work-spaces/table', [WorkSpacesController::class, 'indexTable'])->name('work-spaces.indexTable')->middleware('permission:view-work-spaces');
    Route::get('work-spaces/export', [WorkSpacesController::class, 'export'])->name('work-spaces.export')->middleware('permission:download-work-spaces');
    Route::get('work-spaces/create', [WorkSpacesController::class, 'create'])->name('work-spaces.create')->middleware('permission:create-work-spaces');
    Route::post('work-spaces', [WorkSpacesController::class, 'store'])->name('work-spaces.store')->middleware('permission:create-work-spaces');
    Route::get('work-spaces/{workSpace}', [WorkSpacesController::class, 'show'])->name('work-spaces.show')->middleware('permission:show-work-spaces');
    Route::get('work-spaces/{workSpace}/edit', [WorkSpacesController::class, 'edit'])->name('work-spaces.edit')->middleware('permission:edit-work-spaces');
    Route::put('work-spaces/{workSpace}', [WorkSpacesController::class, 'update'])->name('work-spaces.update')->middleware('permission:edit-work-spaces');
    Route::delete('work-spaces/{workSpace}', [WorkSpacesController::class, 'destroy'])->name('work-spaces.destroy')->middleware('permission:delete-work-spaces');

    // Work Space Members Management - dengan permission check
    Route::get('work-space-members', [WorkSpaceMembersController::class, 'index'])->name('work-space-members.index')->middleware('permission:view-work-space-members');
    Route::get('work-space-members/table', [WorkSpaceMembersController::class, 'indexTable'])->name('work-space-members.indexTable')->middleware('permission:view-work-space-members');
    Route::get('work-space-members/export', [WorkSpaceMembersController::class, 'export'])->name('work-space-members.export')->middleware('permission:download-work-space-members');
    Route::get('work-space-members/create', [WorkSpaceMembersController::class, 'create'])->name('work-space-members.create')->middleware('permission:create-work-space-members');
    Route::post('work-space-members', [WorkSpaceMembersController::class, 'store'])->name('work-space-members.store')->middleware('permission:create-work-space-members');
    Route::get('work-space-members/{workSpaceMember}', [WorkSpaceMembersController::class, 'show'])->name('work-space-members.show')->middleware('permission:show-work-space-members');
    Route::get('work-space-members/{workSpaceMember}/edit', [WorkSpaceMembersController::class, 'edit'])->name('work-space-members.edit')->middleware('permission:edit-work-space-members');
    Route::put('work-space-members/{workSpaceMember}', [WorkSpaceMembersController::class, 'update'])->name('work-space-members.update')->middleware('permission:edit-work-space-members');
    Route::delete('work-space-members/{workSpaceMember}', [WorkSpaceMembersController::class, 'destroy'])->name('work-space-members.destroy')->middleware('permission:delete-work-space-members');

    // Finance Management
    Route::get('finance-categories', [FinanceCategoryController::class, 'index'])->name('finance-categories.index')->middleware('permission:view-finance-categories');
    Route::get('finance-categories/table', [FinanceCategoryController::class, 'indexTable'])->name('finance-categories.indexTable')->middleware('permission:view-finance-categories');
    Route::get('finance-categories/export', [FinanceCategoryController::class, 'export'])->name('finance-categories.export')->middleware('permission:download-finance-categories');
    Route::get('finance-categories/create', [FinanceCategoryController::class, 'create'])->name('finance-categories.create')->middleware('permission:create-finance-categories');
    Route::post('finance-categories', [FinanceCategoryController::class, 'store'])->name('finance-categories.store')->middleware('permission:create-finance-categories');
    Route::get('finance-categories/{financeCategory}', [FinanceCategoryController::class, 'show'])->name('finance-categories.show')->middleware('permission:show-finance-categories');
    Route::get('finance-categories/{financeCategory}/edit', [FinanceCategoryController::class, 'edit'])->name('finance-categories.edit')->middleware('permission:edit-finance-categories');
    Route::put('finance-categories/{financeCategory}', [FinanceCategoryController::class, 'update'])->name('finance-categories.update')->middleware('permission:edit-finance-categories');
    Route::delete('finance-categories/{financeCategory}', [FinanceCategoryController::class, 'destroy'])->name('finance-categories.destroy')->middleware('permission:delete-finance-categories');

    Route::get('finance-merchants', [FinanceMerchantController::class, 'index'])->name('finance-merchants.index')->middleware('permission:view-finance-merchants');
    Route::get('finance-merchants/table', [FinanceMerchantController::class, 'indexTable'])->name('finance-merchants.indexTable')->middleware('permission:view-finance-merchants');
    Route::get('finance-merchants/export', [FinanceMerchantController::class, 'export'])->name('finance-merchants.export')->middleware('permission:download-finance-merchants');
    Route::get('finance-merchants/create', [FinanceMerchantController::class, 'create'])->name('finance-merchants.create')->middleware('permission:create-finance-merchants');
    Route::post('finance-merchants', [FinanceMerchantController::class, 'store'])->name('finance-merchants.store')->middleware('permission:create-finance-merchants');
    Route::get('finance-merchants/{financeMerchant}', [FinanceMerchantController::class, 'show'])->name('finance-merchants.show')->middleware('permission:show-finance-merchants');
    Route::get('finance-merchants/{financeMerchant}/edit', [FinanceMerchantController::class, 'edit'])->name('finance-merchants.edit')->middleware('permission:edit-finance-merchants');
    Route::put('finance-merchants/{financeMerchant}', [FinanceMerchantController::class, 'update'])->name('finance-merchants.update')->middleware('permission:edit-finance-merchants');
    Route::delete('finance-merchants/{financeMerchant}', [FinanceMerchantController::class, 'destroy'])->name('finance-merchants.destroy')->middleware('permission:delete-finance-merchants');

    Route::get('finance-transactions', [FinanceTransactionController::class, 'index'])->name('finance-transactions.index')->middleware('permission:view-finance-transactions');
    Route::get('finance-transactions/table', [FinanceTransactionController::class, 'indexTable'])->name('finance-transactions.indexTable')->middleware('permission:view-finance-transactions');
    Route::get('finance-transactions/export', [FinanceTransactionController::class, 'export'])->name('finance-transactions.export')->middleware('permission:download-finance-transactions');
    Route::post('finance-transactions/import', [FinanceTransactionController::class, 'importPdf'])->name('finance-transactions.import')->middleware('permission:download-finance-transactions');
    Route::get('finance-transactions/create', [FinanceTransactionController::class, 'create'])->name('finance-transactions.create')->middleware('permission:create-finance-transactions');
    Route::post('finance-transactions', [FinanceTransactionController::class, 'store'])->name('finance-transactions.store')->middleware('permission:create-finance-transactions');
    Route::get('finance-transactions/{financeTransaction}', [FinanceTransactionController::class, 'show'])->name('finance-transactions.show')->middleware('permission:show-finance-transactions');
    Route::get('finance-transactions/{financeTransaction}/edit', [FinanceTransactionController::class, 'edit'])->name('finance-transactions.edit')->middleware('permission:edit-finance-transactions');
    Route::put('finance-transactions/{financeTransaction}', [FinanceTransactionController::class, 'update'])->name('finance-transactions.update')->middleware('permission:edit-finance-transactions');
    Route::delete('finance-transactions/{financeTransaction}', [FinanceTransactionController::class, 'destroy'])->name('finance-transactions.destroy')->middleware('permission:delete-finance-transactions');
});

require __DIR__.'/auth.php';
