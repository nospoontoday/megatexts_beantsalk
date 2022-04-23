<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\UserController as ProfileController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ActivityController;
use App\Http\Controllers\Auth\CustomerController;
use App\Http\Controllers\Auth\PrintBookController;
use App\Http\Controllers\Auth\PrintJournalController;
use App\Http\Controllers\Auth\AVMaterialController;
use App\Http\Controllers\Auth\LibraryFixtureController;
use App\Http\Controllers\Auth\EBookController;
use App\Http\Controllers\Auth\EJournalController;
use App\Http\Controllers\Auth\OnlineDatabaseController;
use App\Http\Controllers\Auth\LibraryTechnologyController;
use App\Http\Controllers\Auth\SalesOrderController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\QuotationController;
use App\Http\Controllers\Auth\VendorController;
use App\Http\Controllers\Auth\ProductListController;
use App\Http\Controllers\Auth\ShipmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/', function () {
        return view('auth.login');
    }
);

Route::group(
    ['middleware' => ['auth']], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::group(
            ['middleware' => ['is_admin']], function () {

                Route::get('settings', [UserController::class, 'index'])->name('admin-dashboard');
                
                Route::group(
                    [
                        'prefix'        => 'admin',
                        'as'            => 'admin.',
                    ],
                    function () {
                        Route::resource('users', UserController::class);
                        Route::resource('roles', RoleController::class);
                    }
                );

            }
        );

        Route::resource('profile', ProfileController::class);

        Route::resource('users', ProfileController::class);

        Route::resource('activities', ActivityController::class);

        Route::post('customers/import', [CustomerController::class, 'import'])->name('customer-import');

        Route::resource('customers', CustomerController::class);

        Route::post('vendors/import', [VendorController::class, 'import'])->name('vendor-import');

        Route::resource('vendors', VendorController::class);

        Route::resource('product-list', ProductListController::class);

        Route::resource('quotations', QuotationController::class);

        Route::resource('shipments', ShipmentController::class);

        Route::get('sales-order/library-technologies/create', [SalesOrderController::class, 'create'])->name('create-library-technologies-sales-order');
        Route::get('sales-order/online-databases/create', [SalesOrderController::class, 'create'])->name('create-online-databases-sales-order');
        Route::get('sales-order/e-journals/create', [SalesOrderController::class, 'create'])->name('create-e-journals-sales-order');
        Route::get('sales-order/e-books/create', [SalesOrderController::class, 'create'])->name('create-e-books-sales-order');
        Route::get('sales-order/library-fixtures/create', [SalesOrderController::class, 'create'])->name('create-library-fixtures-sales-order');
        Route::get('sales-order/av-materials/create', [SalesOrderController::class, 'create'])->name('create-av-materials-sales-order');
        Route::get('sales-order/print-books/create', [SalesOrderController::class, 'create'])->name('create-print-books-sales-order');
        Route::get('sales-order/print-journals/create', [SalesOrderController::class, 'create'])->name('create-print-journals-sales-order');
        Route::resource('sales-order', SalesOrderController::class);


        Route::group(
            [
                'prefix'    => 'print',
                'as'        => 'print.',
            ],
            function () {
                Route::post('print-books/import', [PrintBookController::class, 'import'])->name('print-book-import');
                Route::resource('print-books', PrintBookController::class);
                Route::post('print-journals/import', [PrintJournalController::class, 'import'])->name('print-journal-import');
                Route::resource('print-journals', PrintJournalController::class);
                Route::post('av-materials/import', [AVMaterialController::class, 'import'])->name('av-material-import');
                Route::resource('av-materials', AVMaterialController::class);
                Route::post('library-fixtures/import', [LibraryFixtureController::class, 'import'])->name('library-fixture-import');
                Route::resource('library-fixtures', LibraryFixtureController::class);
            }
        );

        Route::group(
            [
                'prefix'    => 'digital',
                'as'        => 'digital.',
            ],
            function () {
                Route::post('e-books/import', [EBookController::class, 'import'])->name('e-book-import');
                Route::resource('e-books', EBookController::class);
                Route::post('e-journals/import', [EJournalController::class, 'import'])->name('e-journal-import');
                Route::resource('e-journals', EJournalController::class);
                Route::post('online-databases/import', [OnlineDatabaseController::class, 'import'])->name('online-database-import');
                Route::resource('online-databases', OnlineDatabaseController::class);
                Route::post('library-technologies/import', [LibraryTechnologyController::class, 'import'])->name('library-technology-import');
                Route::resource('library-technologies', LibraryTechnologyController::class);
            }
        );

        
    }
);

require __DIR__.'/auth.php';
