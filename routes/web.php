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
Route::resource('security/roles/{role}/permissions', PermissionController::class)->middleware(['auth', 'can:view-menu,"permission"'])->except(['show', 'edit', 'update'])->names('role.permission');
Route::resource('security/menus', MenuController::class)->middleware(['auth', 'can:view-menu,"menu"'])->except(['show', 'destroy'])->names('menu');
Route::resource('security/shortcuts', ShortcutController::class)->middleware(['auth', 'can:view-menu,"shortcut"'])->except(['show', 'edit', 'update'])->names('shortcut');

//Maestros
Route::resource('master/units', UnitController::class)->middleware(['auth', 'can:view-menu,"unit"'])->except(['show', 'destroy'])->names('unit');
Route::resource('master/categories', CategoryController::class)->middleware(['auth', 'can:view-menu,"category"'])->except(['show'])->names('category');
Route::resource('master/products', ProductController::class)->middleware(['auth', 'can:view-menu,"product"'])->except(['show', 'destroy'])->names('product');
Route::get('master/products/{product}/edit/image', [ProductController::class, 'editImage'])->name('product.edit.image');
Route::put('master/products/{product}/update/image', [ProductController::class, 'updateImage'])->name('product.update.image');
Route::resource('master/diets', DietController::class)->middleware(['auth', 'can:view-menu,"diet"'])->except(['show', 'destroy'])->names('diet');
Route::resource('master/diets/{diet}/recipes', RecipeController::class)->middleware(['auth', 'can:view-menu,"diet"'])->except(['show', 'create', 'edit'])->names('diet.recipe');

//Personas
Route::resource('person/clients', ClientController::class)->middleware(['auth', 'can:view-menu,"client"'])->except(['show', 'destroy'])->names('client');
Route::resource('person/suppliers', SupplierController::class)->middleware(['auth', 'can:view-menu,"supplier"'])->except(['show', 'destroy'])->names('supplier');

//Mascotas
Route::resource('pet/animals', AnimalController::class)->middleware(['auth', 'can:view-menu,"animal"'])->except(['show'])->names('animal');
Route::resource('pet/races', RaceController::class)->middleware(['auth', 'can:view-menu,"race"'])->except(['show'])->names('race');
Route::resource('pet/pets', PetController::class)->middleware(['auth', 'can:view-menu,"pet"'])->names('pet');

//Gestión clientes
Route::resource('order/orders', OrderController::class)->middleware(['auth', 'can:view-menu,"order"'])->except(['show'])->names('order');
Route::resource('order/produces', ProduceController::class)->middleware(['auth', 'can:view-menu,"produce"'])->names('produce');
Route::resource('order/dispatchs', DispatchController::class)->middleware(['auth', 'can:view-menu,"dispatch"'])->names('dispatch');

//Compras
Route::resource('shopping/shopping-lists', ShoppingListController::class)->middleware(['auth', 'can:view-menu,"shopping-list"'])->except(['show', 'create', 'edit', 'update', 'destroy'])->names('shopping-list');
Route::resource('shopping/direct-purchases', DirectPurchaseController::class)->middleware(['auth', 'can:view-menu,"direct-purchase"'])->except(['show', 'destroy'])->names('direct-purchase');
Route::resource('shopping/bills', BillController::class)->middleware(['auth', 'can:view-menu,"bill"'])->except(['show', 'destroy'])->names('bill');
Route::resource('shopping/adjustments', AdjustmentController::class)->middleware(['auth', 'can:view-menu,"adjustment"'])->except(['show'])->names('adjustment');

//Configuración
Route::resource('security/lists', ListController::class)->middleware(['auth', 'can:view-menu,"list"'])->except(['show'])->names('list');
Route::resource('security/variables', VariableController::class)->middleware(['auth', 'can:view-menu,"variable"'])->except(['show'])->names('variable');
