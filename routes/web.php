<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AsignProtectController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\LabelTransferController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\LabelListController;
use App\Http\Controllers\LabelStockController;
use App\Http\Controllers\Masters\CityController;
use App\Http\Controllers\Masters\CountryController;
use App\Http\Controllers\Masters\StateController;
use App\Http\Controllers\Masters\AdvisoryServicelistController;
use App\Http\Controllers\Masters\CurrencyController;
use App\Http\Controllers\Masters\FairController;
use App\Http\Controllers\Masters\LocationController;
use App\Http\Controllers\Masters\MediumTypeController;
use App\Http\Controllers\Masters\PriceController;
use App\Http\Controllers\Masters\SizeController;
use App\Http\Controllers\Masters\ObjectConditionController;
use App\Http\Controllers\Masters\SurfaceTypeController;
use App\Http\Controllers\Masters\ThirdPartyController;
use App\Http\Controllers\Masters\ValuationController;
use App\Http\Controllers\Masters\YearController;
use App\Http\Controllers\Masters\CommonController;
use App\Http\Controllers\Masters\BranchLocationController;
use App\Http\Controllers\ProtectRequestController;
use App\Http\Controllers\ProtectApprovedController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockCheckController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\StockTransferOrderController;
use App\Http\Controllers\LabelDamageController;
use App\Http\Controllers\LabelOrderController;
use App\Http\Controllers\LabelVoidController;
use App\Http\Controllers\Masters\GstController;
use App\Http\Controllers\Masters\ObjectTypeController;
use App\Http\Controllers\Masters\TimeZoneController;
use App\Http\Controllers\Masters\SiteConditionController;
use App\Http\Controllers\PoController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

#..login..
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('login', 'index')->name('index.login');
    Route::post('login', 'login')->name('login');
    Route::post('emailcheck', 'emailcheck')->name('emailcheck');
});
#....
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.view');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout.get');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    #...Auth
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::get('/', 'get')->name('profile.view')->middleware(['checkAccess:profile.view']);
        Route::get('edit', 'edit')->name('profile.edit')->middleware(['checkAccess:profile.edit']);
        Route::post('update', 'update')->name('profile.update')->middleware(['checkAccess:profile.edit']);
        Route::delete('delete', 'delete')->name('profile.delete')->middleware(['checkAccess:profile.delete']);
    });

    Route::prefix('customer')->controller(App\Http\Controllers\Customers\CustomersController::class)->group(function () {
        Route::get('/', 'index')->name('customer.index')->middleware(['checkAccess:customer.view']);
        Route::get('/list', 'get')->name('customer.list')->middleware(['checkAccess:customer.view']);
        Route::get('protect-request/{id}', 'protectRequest')->name('customer.protect-request.list')->middleware(['checkAccess:customer.view']);
        Route::get('my-studio/{id}', 'myStudio')->name('customer.my-studio.list')->middleware(['checkAccess:customer.view']);
        Route::get('collection/{id}', 'collection')->name('customer.collection.list')->middleware(['checkAccess:customer.view']);
        Route::get('view/{id}', 'view')->name('customer.view')->middleware(['checkAccess:customer.view']);
        Route::post('update/{id}', 'update')->name('customer.update')->middleware(['checkAccess:customer.view']);
        Route::patch('{id}', 'statusUpdate')->name('customer.patch')->middleware(['checkAccess:customer.view']);
        Route::get('/export', 'export')->name('export.customers');
    });

    Route::prefix('customer/artist')->controller(App\Http\Controllers\Customers\ArtistController::class)->group(function () {
        Route::get('/export', 'export')->name('export.artist.export');
        Route::get('/', 'index')->name('customer.artist.index')->middleware(['checkAccess:customer.view']);
        Route::get('/list', 'get')->name('customer.artist.list')->middleware(['checkAccess:customer.view']);
        Route::get('/{id}', 'view')->name('customer.artist.view')->middleware(['checkAccess:customer.view']);
        Route::post('delete/{id}', 'delete')->name('customer.artist.delete');
        Route::post('update/{id}', 'update')->name('customer.artist.representation-update');
    });

    Route::prefix('customer/collector')->controller(App\Http\Controllers\Customers\CollectorController::class)->group(function () {
        Route::get('/export', 'export')->name('export.collector.export');
        Route::get('/', 'index')->name('collector.index')->middleware(['checkAccess:customer.view']);
        Route::get('/list', 'get')->name('collector.list')->middleware(['checkAccess:customer.view']);
        Route::get('/{id}', 'view')->name('collector.view')->middleware(['checkAccess:customer.view']);
        Route::post('delete/{id}', 'delete')->name('customer.collector.delete');
    });

    Route::prefix('customer/business')->controller(App\Http\Controllers\Customers\BusinessController::class)->group(function () {
        Route::get('/export', 'export')->name('export.business.export');
        Route::get('/', 'index')->name('business.index')->middleware(['checkAccess:customer.view']);
        Route::get('/list', 'get')->name('business.list')->middleware(['checkAccess:customer.view']);
        Route::get('/{id}', 'view')->name('business.view')->middleware(['checkAccess:customer.view']);
        Route::post('delete/{id}', 'delete')->name('customer.business.delete');
        Route::post('update/{id}', 'update')->name('customer.business.update');
    });
    #......integration
    Route::prefix('masters')->group(function () {
        Route::prefix('gallery')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('gallery.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('gallery.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('gallery.add_edit');
            Route::post('save', 'save')->name('gallery.save');
            Route::post('check', 'check')->name('gallery.check');
            Route::delete('delete', 'delete')->name('gallery.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('stockcheck-type')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('stockcheck-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('stockcheck-type.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('stockcheck-type.add_edit');
            Route::post('save', 'save')->name('stockcheck-type.save');
            Route::post('check', 'check')->name('stockcheck-type.check');
            Route::delete('delete', 'delete')->name('stockcheck-type.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('damage-type')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('damage-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('damage-type.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('damage-type.add_edit');
            Route::post('save', 'save')->name('damage-type.save');
            Route::post('check', 'check')->name('damage-type.check');
            Route::delete('delete', 'delete')->name('damage-type.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('transfer-reason')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('transfer-reason.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('transfer-reason.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('transfer-reason.add_edit');
            Route::post('save', 'save')->name('transfer-reason.save');
            Route::post('check', 'check')->name('transfer-reason.check');
            Route::delete('delete', 'delete')->name('transfer-reason.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('manufacturer')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('manufacturer.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('manufacturer.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('manufacturer.add_edit');
            Route::post('save', 'save')->name('manufacturer.save');
            Route::post('check', 'check')->name('manufacturer.check');
            Route::delete('delete', 'delete')->name('manufacturer.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('country')->controller(CountryController::class)->group(function () {
            Route::get('/', 'index')->name('country.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('country.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('country.add_edit');
            Route::post('save', 'save')->name('country.save');
            Route::post('check', 'check')->name('country.check');
            Route::delete('delete', 'delete')->name('country.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('state')->controller(StateController::class)->group(function () {
            Route::get('/', 'index')->name('state.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('state.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('state.add_edit');
            Route::post('save', 'save')->name('state.save');
            Route::post('check', 'check')->name('state.check');
            Route::delete('delete', 'delete')->name('state.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('city')->controller(CityController::class)->group(function () {
            Route::get('/', 'index')->name('city.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('city.list')->middleware(['checkAccess:master.view']);
            Route::get('getState', 'getState')->name('city.getState');
            Route::post('edit', 'add_edit')->name('city.add_edit');
            Route::post('save', 'save')->name('city.save');
            Route::post('check', 'check')->name('city.check');
            Route::delete('delete', 'delete')->name('city.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('acquisition-type')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('acquisition-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('acquisition-type.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('acquisition-type.add_edit');
            Route::post('save', 'save')->name('acquisition-type.save');
            Route::post('check', 'check')->name('acquisition-type.check');
            Route::delete('delete', 'delete')->name('acquisition-type.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('void-reason')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('void-reason.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('void-reason.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('void-reason.add_edit');
            Route::post('save', 'save')->name('void-reason.save');
            Route::post('check', 'check')->name('void-reason.check');
            Route::delete('delete', 'delete')->name('void-reason.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('advisory-service')->controller(AdvisoryServicelistController::class)->group(function () {
            Route::get('/', 'index')->name('advisory-service.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('advisory-service.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('advisory-service.add_edit');
            Route::post('save', 'save')->name('advisory-service.save');
            Route::post('check', 'check')->name('advisory-service.check');
            Route::delete('delete', 'delete')->name('advisory-service.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('category')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('category.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('category.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('category.add_edit');
            Route::post('save', 'save')->name('category.save');
            Route::post('check', 'check')->name('category.check');
            Route::delete('delete', 'delete')->name('category.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('condition-observation')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('condition-observation.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('condition-observation.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('condition-observation.add_edit');
            Route::post('save', 'save')->name('condition-observation.save');
            Route::post('check', 'check')->name('condition-observation.check');
            Route::delete('delete', 'delete')->name('condition-observation.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('currency')->controller(CurrencyController::class)->group(function () {
            Route::get('/', 'index')->name('currency.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('currency.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('currency.add_edit');
            Route::post('save', 'save')->name('currency.save');
            Route::post('check', 'check')->name('currency.check');
            Route::delete('delete', 'delete')->name('currency.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('document-type')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('document-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('document-type.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('document-type.add_edit');
            Route::post('save', 'save')->name('document-type.save');
            Route::post('check', 'check')->name('document-type.check');
            Route::delete('delete', 'delete')->name('document-type.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('era')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('era.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('era.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('era.add_edit');
            Route::post('save', 'save')->name('era.save');
            Route::post('check', 'check')->name('era.check');
            Route::delete('delete', 'delete')->name('era.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('exhibition-type')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('exhibition-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('exhibition-type.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('exhibition-type.add_edit');
            Route::post('save', 'save')->name('exhibition-type.save');
            Route::post('check', 'check')->name('exhibition-type.check');
            Route::delete('delete', 'delete')->name('exhibition-type.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('fair')->controller(FairController::class)->group(function () {
            Route::get('/', 'index')->name('fair.index')->middleware(['checkAccess:master.view']);
            Route::post('/', 'index')->name('fair')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('fair.list')->middleware(['checkAccess:master.view']);
            Route::get('/list-alt', 'listAlt')->name('fair.list-alt')->middleware(['checkAccess:master.view']);
            Route::get('/add-edit-alt/{id}', 'addEditAlt')->name('fair.add-edit-alt');
            Route::post('edit', 'add_edit')->name('fair.add_edit');
            Route::post('save', 'save')->name('fair.save');
            Route::post('/save-alt', 'saveAlt')->name('fair.save-alt');
            Route::post('check', 'check')->name('fair.check');
            Route::delete('delete', 'delete')->name('fair.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('genre')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('genre.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('genre.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('genre.add_edit');
            Route::post('save', 'save')->name('genre.save');
            Route::post('check', 'check')->name('genre.check');
            Route::delete('delete', 'delete')->name('genre.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('house')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('house.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('house.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('house.add_edit');
            Route::post('save', 'save')->name('house.save');
            Route::post('check', 'check')->name('house.check');
            Route::delete('delete', 'delete')->name('house.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('insurance-type')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('insurance-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('insurance-type.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('insurance-type.add_edit');
            Route::post('save', 'save')->name('insurance-type.save');
            Route::post('check', 'check')->name('insurance-type.check');
            Route::delete('delete', 'delete')->name('insurance-type.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('location')->controller(LocationController::class)->group(function () {
            Route::get('/', 'index')->name('location.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('location.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('location.add_edit');
            Route::post('save', 'save')->name('location.save');
            Route::post('check', 'check')->name('location.check')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('measurement-type')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('measurement-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('measurement-type.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('measurement-type.add_edit');
            Route::post('save', 'save')->name('measurement-type.save');
            Route::delete('delete', 'delete')->name('measurement-type.delete');
            Route::post('check', 'check')->name('measurement-type.check')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('medium')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('medium.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('medium.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('medium.add_edit');
            Route::post('save', 'save')->name('medium.save');
            Route::post('check', 'check')->name('medium.check');
            Route::delete('delete', 'delete')->name('medium.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('medium-type')->controller(MediumTypeController::class)->group(function () {
            Route::get('/', 'index')->name('medium-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('medium-type.list')->middleware(['checkAccess:master.view']);
        });
        Route::prefix('movement')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('movement.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('movement.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('movement.add_edit');
            Route::post('save', 'save')->name('movement.save');
            Route::post('check', 'check')->name('movement.check');
            Route::delete('delete', 'delete')->name('movement.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('object-type')->controller(ObjectTypeController::class)->group(function () {
            Route::get('/', 'index')->name('object-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('object-type.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('object-type.add_edit');
            Route::post('save', 'save')->name('object-type.save');
            Route::post('check', 'check')->name('object-type.check');
            Route::delete('delete', 'delete')->name('object-type.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('inscription')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('inscription.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('inscription.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('inscription.add_edit');
            Route::post('save', 'save')->name('inscription.save');
            Route::post('check', 'check')->name('inscription.check');
            Route::delete('delete', 'delete')->name('inscription.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('period')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('period.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('period.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('period.add_edit');
            Route::post('save', 'save')->name('period.save');
            Route::post('check', 'check')->name('period.check');
            Route::delete('delete', 'delete')->name('period.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('price')->controller(PriceController::class)->group(function () {
            Route::get('/', 'index')->name('price.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('price.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('price.add_edit');
            Route::post('save', 'save')->name('price.save');
            Route::post('check', 'check')->name('price.check');
            Route::delete('delete', 'delete')->name('price.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('report-condition')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('report-condition.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('report-condition.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('report-condition.add_edit');
            Route::post('save', 'save')->name('report-condition.save');
            Route::post('check', 'check')->name('report-condition.check');
            Route::delete('delete', 'delete')->name('report-condition.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('shape')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('shape.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('shape.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('shape.add_edit');
            Route::post('save', 'save')->name('shape.save');
            Route::post('check', 'check')->name('shape.check');
            Route::delete('delete', 'delete')->name('shape.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('size')->controller(SizeController::class)->group(function () {
            Route::get('/', 'index')->name('size.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('size.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('size.add_edit');
            Route::post('save', 'save')->name('size.save');
            Route::post('check', 'check')->name('size.check');
            Route::delete('delete', 'delete')->name('size.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('style')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('style.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('style.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('style.add_edit');
            Route::post('save', 'save')->name('style.save');
            Route::post('check', 'check')->name('style.check');
            Route::delete('delete', 'delete')->name('style.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('subject')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('subject.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('subject.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('subject.add_edit');
            Route::post('save', 'save')->name('subject.save');
            Route::post('check', 'check')->name('subject.check');
            Route::delete('delete', 'delete')->name('subject.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('surface-medium')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('surface-medium.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('surface-medium.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('surface-medium.add_edit');
            Route::post('save', 'save')->name('surface-medium.save');
            Route::post('check', 'check')->name('surface-medium.check');
            Route::delete('delete', 'delete')->name('surface-medium.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('technique')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('technique.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('technique.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('technique.add_edit');
            Route::post('save', 'save')->name('technique.save');
            Route::post('check', 'check')->name('technique.check');
            Route::delete('delete', 'delete')->name('technique.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('third-party')->controller(ThirdPartyController::class)->group(function () {
            Route::get('/', 'index')->name('third-party.index');
            Route::get('/list', 'list')->name('third-party.list');
        });
        Route::prefix('valuation')->controller(ValuationController::class)->group(function () {
            Route::get('/{tag}', 'index')->name('valuation.index')->middleware(['checkAccess:master.view']);
            Route::get('/list/{tag}', 'list')->name('valuation.list')->middleware(['checkAccess:master.view']);
            Route::post('/edit/{tag}', 'add_edit');
            Route::post('/save/{tag}', 'save');
            Route::delete('/delete/{tag}', 'delete');
            Route::post('check', 'check')->name('valuation.check')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('year')->controller(YearController::class)->group(function () {
            Route::get('/', 'index')->name('year.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('year.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('year.add_edit');
            Route::post('save', 'save')->name('year.save');
            Route::post('check', 'check')->name('year.check');
            Route::delete('delete', 'delete')->name('year.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('coverage')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('coverage.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('coverage.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('coverage.add_edit');
            Route::post('save', 'save')->name('coverage.save');
            Route::post('check', 'check')->name('coverage.check');
            Route::delete('delete', 'delete')->name('coverage.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('artist')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('artist.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('artist.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('artist.add_edit');
            Route::post('save', 'save')->name('artist.save');
            Route::post('check', 'check')->name('artist.check');
            Route::delete('delete', 'delete')->name('artist.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('branch-office')->controller(BranchLocationController::class)->group(function () {
            Route::get('/', 'index')->name('branch-office.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('branch-office.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('branch-office.add_edit');
            Route::post('save', 'save')->name('branch-office.save');
            Route::post('check', 'check')->name('branch-office.check');
            Route::delete('delete', 'delete')->name('branch-office.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('transporter')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('transporter.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('transporter.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('transporter.add_edit');
            Route::post('save', 'save')->name('transporter.save');
            Route::post('check', 'check')->name('transporter.check');
            Route::delete('delete', 'delete')->name('transporter.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('product')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('product.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('product.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('product.add_edit');
            Route::post('save', 'save')->name('product.save');
            Route::post('check', 'check')->name('product.check');
            Route::delete('delete', 'delete')->name('product.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('rejected-reason')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('rejected-reason.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('rejected-reason.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('rejected-reason.add_edit');
            Route::post('save', 'save')->name('rejected-reason.save');
            Route::post('check', 'check')->name('rejected-reason.check');
            Route::delete('delete', 'delete')->name('rejected-reason.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('object-condition')->controller(ObjectConditionController::class)->group(function () {
            Route::get('/', 'index')->name('object-condition.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('object-condition.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('object-condition.add_edit');
            Route::post('save', 'save')->name('object-condition.save');
            Route::post('check', 'check')->name('object-condition.check');
            Route::delete('delete', 'delete')->name('object-condition.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('site-condition')->controller(SiteConditionController::class)->group(function () {
            Route::get('/', 'index')->name('site-condition.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('site-condition.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('site-condition.add_edit');
            Route::post('save', 'save')->name('site-condition.save');
            Route::post('check', 'check')->name('site-condition.check');
            Route::delete('delete', 'delete')->name('site-condition.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('represenation-rejectedreason')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('represenation-rejectedreason.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('represenation-rejectedreason.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('represenation-rejectedreason.add_edit');
            Route::post('save', 'save')->name('represenation-rejectedreason.save');
            Route::post('check', 'check')->name('represenation-rejectedreason.check');
            Route::delete('delete', 'delete')->name('represenation-rejectedreason.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('authenticator-checklist')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('authenticator-checklist.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('authenticator-checklist.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('authenticator-checklist.add_edit');
            Route::post('save', 'save')->name('authenticator-checklist.save');
            Route::post('check', 'check')->name('authenticator-checklist.check');
            Route::delete('delete', 'delete')->name('authenticator-checklist.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('void-reason')->controller(CommonController::class)->group(function () {
            Route::get('/', 'index')->name('void-reason.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('void-reason.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('void-reason.add_edit');
            Route::post('save', 'save')->name('void-reason.save');
            Route::post('check', 'check')->name('void-reason.check');
            Route::delete('delete', 'delete')->name('void-reason.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('surface-type')->controller(SurfaceTypeController::class)->group(function () {
            Route::get('/', 'index')->name('surface-type.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('surface-type.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('surface-type.add_edit');
            Route::post('save', 'save')->name('surface-type.save');
            Route::post('check', 'check')->name('surface-type.check');
            Route::delete('delete', 'delete')->name('surface-type.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('time')->controller(TimeZoneController::class)->group(function () {
            Route::get('/', 'index')->name('time.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('time.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('time.add_edit');
            Route::post('save', 'save')->name('time.save');
            Route::post('check', 'check')->name('time.check');
            Route::delete('delete', 'delete')->name('time.delete')->middleware(['checkAccess:master.delete']);
        });
        Route::prefix('gst')->controller(GstController::class)->group(function () {
            Route::get('/', 'index')->name('gst.index')->middleware(['checkAccess:master.view']);
            Route::get('/list', 'list')->name('gst.list')->middleware(['checkAccess:master.view']);
            Route::post('edit', 'add_edit')->name('gst.add_edit');
            Route::post('save', 'save')->name('gst.save');
            Route::post('check', 'check')->name('gst.check');
            Route::delete('delete', 'delete')->name('gst.delete')->middleware(['checkAccess:master.delete']);
        });
    });

    Route::prefix('label-void')->controller(LabelVoidController::class)->group(function () {
        Route::get('/', 'index')->name('label-void.list');
        Route::post('save', 'save')->name('label-void.save');
    });

    Route::prefix('protect-request')->as('protect-request.')->controller(ProtectRequestController::class)->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('/{id}/view-object', 'showObject')->name('view-object');
        Route::get('/validate-envelope', 'envelopeValidator')->name('validate-envelope');
        Route::get('/validate-label', 'labelValidator')->name('validate-label');
        Route::post('/{id}/save-object', 'showObject')->name('save-object');
        Route::post('/file-upload', 'fileUpload')->name('file-upload');
        Route::post('/multi-file-upload', 'uploadFiles')->name('file-uploads');
        Route::post('/{id}/file-upload-crop', 'fileUploadCrop')->name('file-upload-crop');
        Route::get('/{id}', 'show')->name('show');
        Route::patch('/{id}/team', 'team')->name('team');
        Route::patch('/{id}/change-team', 'changeTeam')->name('change-team');
        Route::patch('/{id}/verify', 'verify')->name('verify');
        Route::post('/{id}/location', 'location')->name('location');
        Route::post('/{id}/component', 'component')->name('component');
        Route::post('/{id}/message', 'message')->name('message');
        Route::post('/{id}/approve', 'approve')->name('approve');
        Route::post('/{id}/rejection', 'rejection')->name('rejection');
        Route::post('/{id}/rejection-approve', 'rejectionApprove')->name('rejection-approve');
        Route::post('/{id}/rejection-override', 'rejectionOverride')->name('rejection-override');
        Route::post('/{id}/inspection', 'saveInspections')->name('inspection');
        Route::post('/{id}/inspection-file-upload', 'inspectionFileUpload')->name('inspection-file-upload');
        Route::post('/{id}/provenance-authenticator', 'saveProvenanceAuthenticator')->name('provenance-authenticator');
        Route::post('/{id}/provenance-objectnumber-check', 'provenanceAsignObjectNumberCheck')->name('provenance-objectnumber-check');
        Route::post('/{id}/inspection-damage-file-upload', 'inspectionDamageFileUpload')->name('inspection-damage-file-upload');
        Route::post('/{id}/inspection-object-file-upload', 'inspectionObjectFileUpload')->name('inspection-object-file-upload');
        Route::post('/{id}/inspection-remove-file-uploads', 'inspectionRemoveFileUploads')->name('inspection-remove-file-uploads');
        Route::post('/{id}/inspection-sitecondition-checklist-request-all', 'inspectionSiteconditionChecklistRequestAll')->name('inspection-sitecondition-checklist-request-all');
        Route::post('/{id}/inspection-sitecondition-checklist-request', 'inspectionSiteconditionChecklistRequest')->name('inspection-sitecondition-checklist-request');


        # ... Scanner App
        Route::post('/scanner-app-step', 'scannerAppStep')->name('scanner-app-step');
        Route::post('/scanner-app-save', 'scannerAppSave')->name('scanner-app-save');
        Route::post('/scanner-app-upload', 'scannerAppUpload')->name('scanner-app-upload');
        Route::post('/scanner-app-delete', 'scannerAppDelete')->name('scanner-app-delete');
        Route::post('/scanner-app-matching-value', 'scannerAppMatchingValue')->name('scanner-app-matching-value');
        Route::post('/void-save', 'scannerAppVoidSave')->name('void-save');
        Route::get('/void-reason', 'voidReason')->name('void-reason');
        Route::delete('/remove-label-image', 'removeLabelImage')->name('remove-label-image');
        Route::post('/image-edit-extended', 'imageEditExtended')->name('image-edit-extended');
    });
    Route::prefix('protect-approved')->as('protect-approved.')->controller(ProtectApprovedController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });
    Route::prefix('label-damaged')->controller(LabelDamageController::class)->group(function () {
        Route::get('/export', 'export')->name('export');
        Route::get('/', 'labelDamagedList')->name('label-damaged.list')->middleware(['checkAccess:damages.view']);
        Route::get('/create', 'labelDamagedCreate')->name('label-damaged.create')->middleware(['checkAccess:damages.create']);
        Route::get('/summary/{damaged_id}', 'labelDamagedSummary')->name('label-damaged.summary');
        Route::post('/save', 'labelDamagedSave')->name('label-damaged.save');
        Route::post('/update-summary', 'updateSummary')->name('label-damaged.update-summary');
        Route::post('/update-damaged-labels', 'updateDamagedLabels')->name('label-damaged.update-damaged-labels');
        Route::get('/labels', 'labelPopup')->name('label-damaged.labels');
        Route::get('/summary-delete/{summary_id}', 'deleteSummary')->name('label-damaged.summary-delete');
        Route::get('/summary-delete-all', 'deleteAllSummary')->name('label-damaged.summary-delete-all');
        Route::get('/clear-products/{location_id}', 'clearAllProducts')->name('label-damaged.clear-products');
        Route::get('/get-products/{damaged_id}', 'getProducts')->name('label-damaged.get-products');

        //Route::post('/update-product', 'updateProduct')->name('label-damaged.update-product');
        //Route::get('/fetch-summary/{product_id}', 'fetchSummary')->name('label-damaged.fetch-summary');
    });


    Route::prefix('label-transfer')->as('label-transfer.')->controller(LabelTransferController::class)->group(function () {
        Route::get('/export', 'export')->name('export');
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/summary', 'showSummary')->name('summary');
        Route::post('/save', 'save')->name('save');
        Route::put('/{id}', 'save')->name('update');
        Route::get('/pdf/{id}', 'pdfGenerator')->name('label.pdf');
    });


    Route::prefix('label-request')->as('label-request.')->controller(LabelController::class)->group(function () {
        Route::get('/export', 'export')->name('export');
        Route::get('/export-view', 'exportview')->name('export-view');
        Route::get('/', 'index')->name('index');
        Route::get('/exist', 'existRequest')->name('exist');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/summary', 'showSummary')->name('summary');
        Route::post('/save', 'save')->name('save');
        Route::put('/{id}', 'save')->name('update');
        Route::get('/pdf/{id}', 'pdfGenerator')->name('label.pdf');
    });

    Route::prefix('label-issues')->as('label-issues.')->controller(LabelController::class)->group(function () {
        Route::get('/export', 'export')->name('export');
        Route::get('/export-issue', 'exportissue')->name('export-issue');
        Route::get('/', 'index')->name('index');
        Route::get('/{agent_id}/load-previous', 'loadPreviousRequests')->name('load-previous');
        Route::get('/exist', 'existRequest')->name('exist');
        Route::get('/verify', 'verifyLabel')->name('verify');
        Route::get('/{id}', 'showIssues')->name('show');
        Route::post('save', 'saveNewIssueRequest')->name('save-new');
        Route::put('/{id}', 'saveIssues')->name('save');
        Route::get('/{id}/summary', 'showSummary')->name('summary');
    });

    Route::prefix('label-return')->as('label-return.')->controller(LabelController::class)->group(function () {
        Route::get('/export', 'export')->name('export');
        Route::get('/export-return', 'exportreturn')->name('export-return');
        Route::get('/', 'index')->name('index');
        Route::get('/details', 'requestDetails')->name('details');
        Route::get('/verify', 'verifyReturnLabel')->name('verify');
        Route::get('/{id}/summary', 'showSummary')->name('summary');
        Route::put('/{id}', 'saveReturn')->name('save');
        Route::get('/{id}', 'showReturn')->name('show');
    });

    Route::prefix('stock-check')->as('stock-check.')->controller(StockCheckController::class)->group(function () {
        Route::get('/export-show', 'exportshow')->name('export-show');
        Route::get('/', 'index')->name('index')->middleware(['checkAccess:stock-check.view']);
        Route::get('/export', 'export')->name('export');
        Route::put('/{id}', 'save')->name('update');
        Route::post('/save', 'save')->name('save');
        Route::get('/stocks', 'stocks')->name('stocks');
        Route::get('/verify', 'verify')->name('verify');
        Route::get('/{id}', 'show')->name('show');
    });

    Route::get('location-agents/{location_id}', [SettingController::class, 'locationAgent'])->name('location-agents');


    Route::prefix('label-stock')->as('label-stock.')->controller(LabelStockController::class)->group(function () {
        Route::get('/', 'index')->name('label-stock.index')->middleware(['checkAccess:stock-overview.view']);
        Route::get('/view/{product_id}/{location_id}', 'view')->name('label-stock.view')->middleware(['checkAccess:stock-overview.view']);
        Route::get('/product/{agent_id}/{product_id}/{location_id}', 'StockbyLabelindex')->name('stock-label-product.index')->middleware(['checkAccess:stock-overview.view']);
        Route::get('/product', 'StockbyLabelProduct')->name('stock-label-product.view')->middleware(['checkAccess:stock-overview.view']);
        Route::get('product-view/{id}/{product_id}', 'LabelProductBy')->name('label-product.view')->middleware(['checkAccess:stock-overview.view']);
        Route::get('product-view', 'LabelProductByview')->name('label-product-view.view')->middleware(['checkAccess:stock-overview.view']);
    });

    # ... Designs Only
    Route::prefix('asign-protect')->controller(AsignProtectController::class)->group(function () {
        Route::get('/', 'index')->name('asign-protect');
        Route::get('/request/{id}', 'asignRequest')->name('asign-protect.request');
    });
    Route::prefix('user-management')->controller(UserManagementController::class)->group(function () {
        Route::get('/', 'index')->name('user-management.get')->middleware(['checkAccess:user.view']);
        Route::get('/list', 'list')->name('user-management.index')->middleware(['checkAccess:user.view']);
        Route::get('/add_edit', 'create')->name('user-management.create')->middleware(['checkAccess:user.create']);
        Route::post('save', 'save')->name('user-management.save')->middleware(['checkAccess:user.create']);
        Route::post('check', 'check')->name('user-management.check');
        Route::get('/edit/{id}', 'edit')->name('user-management.edit')->middleware(['checkAccess:user.edit']);
        Route::post('permission', 'permission')->name('user-management.permission');
        Route::post('delete/{id}', 'delete')->name('user-management.delete')->middleware(['checkAccess:user.delete']);
    });
    Route::prefix('settings')->controller(SettingController::class)->group(function () {
        Route::get('/', 'index')->name('settings.get')->middleware(['checkAccess:user.view']);
        Route::post('/pricingsave', 'pricingsave')->name('pricing.save');
        Route::post('/labelsave', 'labelsave')->name('label.save');
        Route::post('/marketsave', 'marketsave')->name('market.save');
        Route::post('/paymentsave', 'paymentsave')->name('payment.save');
    });
    Route::prefix('masters/role-management')->controller(RoleManagementController::class)->group(function () {
        Route::get('/', 'index')->name('role-management.get')->middleware(['checkAccess:role.view']);
        Route::get('/list', 'list')->name('role-management.index')->middleware(['checkAccess:role.view']);
        Route::get('/add_edit', 'create')->name('role-management.create')->middleware(['checkAccess:role.create']);
        Route::post('save', 'save')->name('role-management.save')->middleware(['checkAccess:role.create']);
        Route::post('check', 'check')->name('role-management.check');
        Route::get('/{id}', 'edit')->name('role-management.edit')->middleware(['checkAccess:role.edit']);
    });
    // Route::prefix('purchase-orders')->controller(DesignController::class)->group(function () {
    //     Route::get('/', 'purchaseOrderList')->name('purchase-orders.list');
    //     Route::get('/create', 'purchaseOrderCreate')->name('purchase-orders.create');
    //     Route::get('/summary/{order_id}', 'purchaseOrderSummary')->name('purchase-orders.summary');
    //     Route::get('/create-grn/{order_id}', 'purchaseOrderGrn')->name('purchase-orders.grn');
    // });
    // Route::prefix('stock-transfer-orders')->controller(DesignController::class)->group(function () {
    //     Route::get('/', 'stockTransferOrderList')->name('stock-transfer-orders.list');
    //     Route::get('/create', 'stockTransferOrderCreate')->name('stock-transfer-orders.create');
    //     Route::get('/summary/{stock_id}', 'stockTransferOrderSummary')->name('stock-transfer-orders.summary');
    // });
    // Route::prefix('goods-received-notes')->controller(DesignController::class)->group(function () {
    //     Route::get('/', 'grnList')->name('grn-for-po.list');
    //     Route::get('/create', 'grnCreate')->name('grn-for-po.create');
    //     Route::get('/scan/{grn_id}', 'grnScan')->name('grn-for-po.scan');
    //     Route::get('/summary/{grn_id}', 'grnSummary')->name('grn-for-po.summary');
    // });
    Route::prefix('stock')->controller(DesignController::class)->group(function () {
        Route::get('/list', 'stockOverviewList')->name('stock.list');
        Route::get('/view/{stock_id}', 'stockViewByID')->name('stock.view');
        Route::get('/user-list/view/{stock_id}', 'stockViewUserList')->name('stock.user-list');
        Route::get('/user/request/{stock_id}', 'stockUserRequest')->name('stock.user');
    });

    #...purchase order

    Route::prefix('purchase-orders')->controller(PurchaseOrderController::class)->group(function () {
        Route::get('/', 'purchaseOrderList')->name('purchase-orders.list')->middleware(['checkAccess:purchase-order.view', 'unSavedGrn']);
        Route::get('/create', 'purchaseOrderCreate')->name('purchase-orders.create')->middleware(['checkAccess:purchase-order.create']);
        Route::get('/summary/{po_id}', 'summaryPO')->name('purchase-orders.show')->middleware(['checkAccess:purchase-order.edit']);
        Route::post('/save', 'create')->name('purchase.save');
        Route::post('/product-validation', 'productValidate')->name('purchase.product');
        Route::get('/pdf/{id}', 'pdfGenerator')->name('purchase.pdf');
    });

    #...label order

    Route::prefix('label-orders')->as('label-orders.')->controller(LabelOrderController::class)->group(function () {
        // Route::get('/export', 'export')->name('export');
        // Route::get('/export-view', 'exportview')->name('export-view');
        Route::get('/', 'index')->name('index');
        // Route::get('/exist', 'existRequest')->name('exist');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/summary', 'showSummary')->name('summary');
        Route::post('/save', 'save')->name('save');
        // Route::put('/{id}', 'save')->name('update');
        // Route::get('/pdf/{id}', 'pdfGenerator')->name('label.pdf');
    });

    #...Artwork Request

    Route::prefix('image-request')->controller(RequestController::class)->group(function () {
        Route::get('/imageRequestList', 'imageRequestList')->name('image-request.imageRequestList');
        Route::get('/priceRequestList', 'priceRequestList')->name('image-request.priceRequestList');
        Route::get('/viewRequestList', 'viewRequestList')->name('image-request.viewRequestList');
        Route::get('/offerRequestList', 'offerRequestList')->name('image-request.offerRequestList');
    });

    #...Good Received Notes

    Route::prefix('goods-received-notes')->controller(GrnController::class)->group(function () {
        Route::get('/', 'grnList')->name('grn.list')->middleware(['checkAccess:goods-received-note.view', 'unSavedGrn']);

        Route::get('/create-grn/{order_id?}/{grn_id?}/{type?}', 'createGrn')->name('grn.create')->middleware(['checkAccess:goods-received-note.create']);
        Route::get('/scan/{product_id}/{order_product_id}/{grn_id}/{type}', 'scanGrn')->name('grn.scan');
        Route::delete('/reset-grn', 'resetGrn')->name('grn.reset');
        Route::post('/scan/product', 'scanProduct')->name('grn.scan.product');
        Route::post('/save', 'saveGrn')->name('grn.save');

        Route::post('/grn', 'createGrnId')->name('grn.grnid');
        Route::post('/po-product', 'orderProductList')->name('grn.po.product');
        Route::get('/summary/{grn_id}/{type}', 'grnSummary')->name('grn.summary')->middleware(['checkAccess:goods-received-note.edit']);
        Route::get('/pdf/{id}/{type}', 'pdfGenerator')->name('grn.pdf');
    });

    #...Stock Transfer Order

    Route::prefix('stock-transfer-orders')->controller(StockTransferOrderController::class)->group(function () {

        Route::get('/', 'transferOrderList')->name('transfer-orders.list')->middleware([
            'checkAccess:stock-transfer-order.view', 'unSavedGrn'
        ]);
        Route::get('/create', 'transferOrderCreate')->name('sto.create')->middleware(['checkAccess:stock-transfer-order.create']);
        Route::get('/summary/{sto_order_id}/{order_status?}', 'stoSummary')->name('sto.summary')->middleware(['checkAccess:stock-transfer-order.edit']);
        Route::post('/save', 'create')->name('sto.save');
        Route::post('/check-availability', 'checkAvailability')->name('sto.productAvailability');
        Route::post('/product-validation', 'productValidate')->name('sto.productValidate');
        Route::get('/pdf/{id}', 'pdfGenerator')->name('sto.pdf');

        //Stock issue
        Route::get('/pack/{sto_id}/{sto_status?}', 'pack')->name('sto.pack');
        Route::get('/pack-add/{sto_id}', 'createPack')->name('sto.create.pack');
        Route::get('/pack-scan/{product_id}/{sto_id}/{sto_product_id}', 'scanStoPack')->name('sto.pack.scan');
        Route::post('/scan/pack/product', 'scanStoPackProduct')->name('sto.scan.pack.product');
        Route::delete('/reset-sto-pack', 'resetStoPack')->name('sto.reset.pack');
        Route::post('/pack/edit-product', 'editStoOrderedProduct')->name('sto.edit.pack.product');
        Route::post('/transit/add', 'addStoTransit')->name('sto.pack.transit');
        Route::post('/pack/add', 'addStoPack')->name('sto.pack.order');
    });


    Route::prefix('label-status')->controller(LabelListController::class)->group(function () {
        Route::get('/', 'index')->name('label-status.list');
    });

    Route::prefix('label-void')->controller(LabelVoidController::class)->group(function () {
        Route::get('/', 'index')->name('label-void.list');
    });

    Route::prefix('excel-download')->controller(ExcelController::class)->group(function () {
        Route::get('/', 'index')->name('Excel');
        Route::get('/downloadExcel', 'downloadExcel')->name('downloadExcel');
    });
});
