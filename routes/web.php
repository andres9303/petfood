<?php

use App\Http\Controllers\config\ListController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\master\CategoryController;
use App\Http\Controllers\master\DietController;
use App\Http\Controllers\master\ProductController;
use App\Http\Controllers\master\RecipeController;
use App\Http\Controllers\master\UnitController;
use App\Http\Controllers\order\DispatchController;
use App\Http\Controllers\order\OrderController;
use App\Http\Controllers\order\ProduceController;
use App\Http\Controllers\person\ClientController;
use App\Http\Controllers\person\SupplierController;
use App\Http\Controllers\pet\AnimalController;
use App\Http\Controllers\pet\PetController;
use App\Http\Controllers\pet\RaceController;
use App\Http\Controllers\pet\TraceController;
use App\Http\Controllers\report\ReportController;
use App\Http\Controllers\security\MenuController;
use App\Http\Controllers\security\PermissionController;
use App\Http\Controllers\security\RoleController;
use App\Http\Controllers\security\ShortcutController;
use App\Http\Controllers\security\UserController;
use App\Http\Controllers\shopping\AdjustmentController;
use App\Http\Controllers\shopping\BillController;
use App\Http\Controllers\shopping\DirectPurchaseController;
use App\Http\Controllers\shopping\ShoppingListController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->name('home');

//Seguridad
Route::resource('security/users', UserController::class)->middleware(['auth', 'can:view-menu,"user"'])->except(['show'])->names('user');
Route::get('security/users/{user}/edit-password', [UserController::class, 'editPassword'])->name('user.editPassword');
Route::put('security/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
Route::get('security/users/{user}/roles', [UserController::class, 'indexRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.index');
Route::get('security/users/{user}/roles/create', [UserController::class, 'createRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.create');
Route::post('security/users/{user}/roles', [UserController::class, 'storeRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.store');
Route::delete('security/users/{user}/roles/{role}', [UserController::class, 'destroyRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.destroy');
Route::resource('security/roles', RoleController::class)->middleware(['auth', 'can:view-menu,"role"'])->except(['show'])->names('role');
Route::resource('security/roles/{role}/permissions', PermissionController::class)->middleware(['auth', 'can:view-menu,"role"'])->except(['show', 'edit', 'update'])->names('role.permission');
Route::resource('security/menus', MenuController::class)->middleware(['auth', 'can:view-menu,"menu"'])->except(['show', 'create', 'store', 'destroy'])->names('menu');
Route::resource('security/shortcuts', ShortcutController::class)->middleware(['auth', 'can:view-menu,"shortcut"'])->except(['show', 'edit', 'update'])->names('shortcut');

//Maestros
Route::resource('master/units', UnitController::class)->middleware(['auth', 'can:view-menu,"unit"'])->except(['show', 'destroy'])->names('unit');
Route::resource('master/categories', CategoryController::class)->middleware(['auth', 'can:view-menu,"category"'])->except(['show'])->names('category');
Route::resource('master/products', ProductController::class)->middleware(['auth', 'can:view-menu,"product"'])->except(['show', 'destroy'])->names('product');
Route::get('master/products/{product}/edit/image', [ProductController::class, 'editImage'])->name('product.edit.image');
Route::put('master/products/{product}/update/image', [ProductController::class, 'updateImage'])->name('product.update.image');
Route::resource('master/diets', DietController::class)->middleware(['auth', 'can:view-menu,"diet"'])->except(['show', 'destroy'])->names('diet');
Route::resource('master/diets/{diet}/recipes', RecipeController::class)->middleware(['auth', 'can:view-menu,"diet"'])->except(['show', 'create', 'store', 'edit', 'update'])->names('diet.recipe');

//Personas
Route::resource('person/clients', ClientController::class)->middleware(['auth', 'can:view-menu,"client"'])->except(['show'])->names('client');
Route::resource('person/suppliers', SupplierController::class)->middleware(['auth', 'can:view-menu,"supplier"'])->except(['show', 'store', 'update', 'destroy'])->names('supplier');

//Mascotas
Route::resource('pet/animals', AnimalController::class)->middleware(['auth', 'can:view-menu,"animal"'])->except(['show'])->names('animal');
Route::resource('pet/races', RaceController::class)->middleware(['auth', 'can:view-menu,"race"'])->except(['show'])->names('race');
Route::resource('pet/pets', PetController::class)->middleware(['auth', 'can:view-menu,"pet"'])->except(['destroy'])->names('pet');
Route::resource('pet/pets/{pet}/traces', TraceController::class)->middleware(['auth', 'can:view-menu,"pet"'])->except(['index', 'show'])->names('pet.trace');
Route::get('pet/pets/{pet}/edit/image', [PetController::class, 'editImage'])->name('pet.edit.image');
Route::put('pet/pets/{pet}/update/image', [PetController::class, 'updateImage'])->name('pet.update.image');

//Gestión pedidos
Route::resource('order/orders', OrderController::class)->middleware(['auth', 'can:view-menu,"order"'])->except(['show'])->names('order');
Route::resource('order/produces', ProduceController::class)->middleware(['auth', 'can:view-menu,"produce"'])->names('produce');
Route::get('order/produces/{produce}/edit/complete', [ProduceController::class, 'editComplete'])->name('produce.edit.complete');
Route::put('order/produces/{produce}/update/complete', [ProduceController::class, 'updateComplete'])->name('produce.update.complete');
Route::resource('order/dispatchs', DispatchController::class)->middleware(['auth', 'can:view-menu,"dispatch"'])->except(['show'])->names('dispatch');

//Compras
Route::get('shopping/shopping-lists', [ShoppingListController::class, 'index'])->middleware(['auth', 'can:view-menu,"shopping-list"'])->name('shopping-list.index');
Route::resource('shopping/direct-purchases', DirectPurchaseController::class)->middleware(['auth', 'can:view-menu,"direct-purchase"'])->except(['show', 'destroy'])->names('direct-purchase');
Route::resource('shopping/bills', BillController::class)->middleware(['auth', 'can:view-menu,"bill"'])->except(['show'])->names('bill');
Route::resource('shopping/adjustments', AdjustmentController::class)->middleware(['auth', 'can:view-menu,"adjustment"'])->except(['show'])->names('adjustment');

//Reportes
Route::get('report/inventory', [ReportController::class, 'inventory'])->middleware(['auth', 'can:view-menu,"report.inventory"'])->name('report.inventory.index');
Route::get('report/movements', [ReportController::class, 'movements'])->middleware(['auth', 'can:view-menu,"report.movements"'])->name('report.movements.index');
Route::get('report/order', [ReportController::class, 'order'])->middleware(['auth', 'can:view-menu,"report.order"'])->name('report.order.index');
Route::get('report/purchase', [ReportController::class, 'purchase'])->middleware(['auth', 'can:view-menu,"report.purchase"'])->name('report.purchase.index');
Route::get('report/bill', [ReportController::class, 'bill'])->middleware(['auth', 'can:view-menu,"report.bill"'])->name('report.bill.index');
Route::get('report/production', [ReportController::class, 'production'])->middleware(['auth', 'can:view-menu,"report.production"'])->name('report.production.index');
Route::get('report/sale', [ReportController::class, 'sale'])->middleware(['auth', 'can:view-menu,"report.sale"'])->name('report.sale.index');
Route::get('report/dispatch', [ReportController::class, 'dispatch'])->middleware(['auth', 'can:view-menu,"report.dispatch"'])->name('report.dispatch.index');
Route::get('report/balance', [ReportController::class, 'balance'])->middleware(['auth', 'can:view-menu,"report.balance"'])->name('report.balance.index');
Route::get('report/cost', [ReportController::class, 'cost'])->middleware(['auth', 'can:view-menu,"report.cost"'])->name('report.cost.index');


//Configuración
Route::resource('config/lists', ListController::class)->middleware(['auth', 'can:view-menu,"list"'])->except(['show'])->names('list');
Route::resource('config/variables', VariableController::class)->middleware(['auth', 'can:view-menu,"variable"'])->except(['show'])->names('variable');
