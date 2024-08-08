<?php

use App\Http\Controllers\AgentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingSourceController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CharacteristicsController;
use App\Http\Controllers\ColorTypesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CompanyPreferencesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\LanguagesController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PlacesController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RateCodeController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\StationsController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TypesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleExchangeController;
use App\Http\Controllers\VisitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('resetPassword',  [AuthController::class, 'resetPassword']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
});

    Route::group([
        'middleware' => ['auth:sanctum']
    ], function () {
    Route::get('refresh', 'AuthController@refresh');

    //Home and combined
    Route::prefix('home')->group(function () {
        Route::get('/', [HomeController::class, 'index_api']);
        Route::get('/combined', [ExportController::class, 'combinedCollections']);
        Route::get('/combinedVehicles',  [ExportController::class, 'combinedCollectionsVehicles']);
        Route::get('/combinedBookingSources',  [ExportController::class, 'combinedCollectionsBookingSources']);
        Route::get('/combinedUsers', [ExportController::class, 'combinedCollectionsUsers']);
        Route::get('/combinedTypes', [ExportController::class, 'combinedCollectionsTypes']);
        Route::get('/combinedAgents', [ExportController::class, 'combinedCollectionsAgents']);
        Route::get('/combinedInvoices', [ExportController::class, 'combinedCollectionsInvoices']);
        Route::get('/combinedPayments', [ExportController::class, 'combinedCollectionsPayments']);
        Route::get('/combinedQuotes', [ExportController::class, 'combinedCollectionsQuotes']);
        Route::get('/combinedBookings', [ExportController::class, 'combinedCollectionsBookings']);
        Route::get('/combinedRentals', [ExportController::class, 'combinedCollectionsRentals']);
    });

        //TYPES
        Route::prefix('types')->group(function () {
        Route::post('/upload', [TypesController::class, 'upload']);
        Route::delete('/uploadRemove/{id}', [TypesController::class, 'uploadRemove']);
            Route::get('/',  [TypesController::class, 'preview_api']);
            Route::get('/{id}', [TypesController::class, 'edit']);
            Route::patch('/{id}',  [TypesController::class, 'update']);
            Route::post('/create',  [TypesController::class, 'createApi']);
            Route::delete('/{id}',  [TypesController::class, 'delete_api']);
        });

        //IMAGES
    Route::prefix('image')->group(function () {
        Route::delete('/', [ImageController::class, 'removeImageLink']);
        Route::post('/upload', [ImageController::class, 'uploadImage']);
    });


         //DOCUMENT TYPES
        Route::prefix('documentType')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', [DocumentTypeController::class, 'preview_api']);
                Route::post('/create',  [DocumentTypeController::class, 'update_store_api']);
                Route::get('/{id}', [DocumentTypeController::class, 'edit']);
                Route::patch('/{id}',  [DocumentTypeController::class, 'update_store_api']);
                Route::delete('/{id}',  [DocumentTypeController::class, 'delete_api']);
            });
        });

        //DOCUMENTS
        Route::prefix('document')->group(function () {
        Route::post('/upload',  [DocumentController::class, 'update_store_api']);
            Route::get('/', [DocumentController::class, 'preview_api']);
            Route::post('/create',  [DocumentController::class, 'update_store_api']);
            Route::get('/{id}',  [DocumentController::class, 'edit']);
            Route::patch('/{id}',  [DocumentController::class, 'update_store_api']);
                Route::delete('/{id}',  [DocumentController::class, 'delete_api']);
        });


          //ROLES
        Route::middleware('permissions')->prefix('roles')->group(function () {
            Route::get('/',  [RolesController::class, 'preview_api']);
            Route::patch('/{id}',  [RolesController::class, 'update_store_api']);
            Route::get('/{id}',  [RolesController::class, 'edit']);
            Route::post('/create',  [RolesController::class, 'update_store_api']);
            Route::delete('/{id}',  [RolesController::class, 'delete_api']);
        });

        //AGENTS
        Route::prefix('agents')->group(function () {
            Route::get('/', [AgentsController::class, 'preview_api']);

            Route::get('/{id}', [AgentsController::class, 'edit']);
            Route::post('/create', [AgentsController::class, 'update_store_api']);
            Route::patch('/{id}', [AgentsController::class, 'update_store_api']);
            Route::delete('/{id}', [AgentsController::class, 'delete_api']);
        });

        //CONTACTS
        Route::prefix('contacts')->group(function () {
            Route::get('/', [ContactController::class, 'preview_api']);
            Route::get('/{id}',  [ContactController::class, 'edit']);
            Route::post('/create',  [ContactController::class, 'update_store_api']);
            Route::patch('/{id}',  [ContactController::class, 'update_store_api']);
            Route::delete('/{id}',  [ContactController::class, 'delete_api']);
        });


         //LANGUAGES
        Route::middleware('permissions')->prefix('languages')->group(function () {
            Route::get('/', [LanguagesController::class, 'preview_api']);
            Route::patch('/{id}', [LanguagesController::class, 'update_store_api']);
            Route::get('/{id}', [LanguagesController::class, 'edit']);
            Route::post('/create', [LanguagesController::class, 'update_store_api']);
            Route::delete('/{id}', [LanguagesController::class, 'delete_api']);
        });

        //DRIVERS
        Route::prefix('drivers')->group(function () {
        Route::get('/emp', [DriversController::class, 'previewEmp']);
        Route::get('/emp/{id}',  [DriversController::class, 'editEmp']);

            Route::get('/',  [DriversController::class, 'preview_api']);
            Route::get('/{id}',  [DriversController::class, 'edit']);
            Route::post('/create',  [DriversController::class, 'update_store_api']);
            Route::patch('/{id}',  [DriversController::class, 'update_store_api']);
            Route::delete('/{id}',  [DriversController::class, 'delete_api']);
        });

        //COMPANIES
        Route::prefix('companies')->group(function () {
            Route::get('/',  [CompaniesController::class, 'preview_api']);
            Route::get('/{id}',  [CompaniesController::class, 'edit']);
            Route::post('/create',  [CompaniesController::class, 'update_store_api']);
            Route::patch('/{id}',  [CompaniesController::class, 'update_store_api']);
            Route::delete('/{id}',  [CompaniesController::class, 'delete_api']);
        });

        //BOOKING SOURCES
        Route::prefix('booking_sources')->group(function () {
            Route::get('/', [BookingSourceController::class, 'preview_api']);
            Route::get('/{id}', [BookingSourceController::class, 'edit']);
            Route::post('/create', [BookingSourceController::class, 'update_store_api']);
            Route::patch('/{id}', [BookingSourceController::class, 'update_store_api']);
            Route::delete('/{id}', [BookingSourceController::class, 'delete_api']);
        });

    //QUOTES
    Route::prefix('quotes')->group(function () {
        Route::get('/',  [QuoteController::class, 'preview_api']);
        Route::get('/{id}', [QuoteController::class, 'edit']);
        Route::post('/create', [QuoteController::class, 'create_api']);
        Route::patch('/{id}', [QuoteController::class, 'create_api']);
        Route::delete('/{id}', [QuoteController::class, 'delete_api']);
    });


         //BOOKINGS
         Route::prefix('bookings')->group(function () {
            Route::get('/', [BookingController::class, 'preview_api']);
            Route::get('/{id}',  [BookingController::class, 'edit']);
            Route::post('/create',  [BookingController::class, 'create_api']);
            Route::patch('/{id}',  [BookingController::class, 'create_api']);
            Route::delete('/{id}',  [BookingController::class, 'delete_api']);
        Route::options('/reason',  [BookingController::class, 'reason']);
        Route::get('/reason/{id}',  [BookingController::class, 'reasonGetOne']);
        });


    //RENTALS
    Route::prefix('rentals')->group(function () {
        Route::get('/', [RentalController::class, 'preview_api']);
        Route::get('/{id}',  [RentalController::class, 'edit']);
        Route::post('/create',  [RentalController::class, 'create_api']);
        Route::patch('/{id}',  [RentalController::class, 'create_api']);
        Route::delete('/{id}',  [RentalController::class, 'delete_api']);

        //signature
        Route::post('/signatureExcess', [SignatureController::class, 'uploadSignatureExcess']);
        Route::post('/signatureExcDelete',  [SignatureController::class, 'deleteSignatureExcess']);
        Route::post('/signatureSee1',  [SignatureController::class, 'SignatureSee1']);

        Route::post('/signatureMain',  [SignatureController::class, 'uploadSignatureMain']);
        Route::post('/signatureMDelete',  [SignatureController::class, 'deleteSignatureMain']);
        Route::post('/signatureSee2',  [SignatureController::class, 'SignatureSee2']);

        Route::post('/signatureSecDriver',  [SignatureController::class, 'uploadSignatureSecDriver']);
        Route::post('/signatureSecDelete',  [SignatureController::class, 'deleteSignatureSecDriver']);
        Route::post('/signatureSee3',  [SignatureController::class, 'SignatureSee3']);
    });

        //BRANDS
        Route::prefix('brands')->group(function () {
        Route::post('/upload',  [BrandsController::class, 'upload']);
        Route::delete('/uploadRemove/{id}',  [BrandsController::class, 'uploadRemove']);
            Route::get('/',  [BrandsController::class, 'preview_api']);
            Route::get('/{id}',  [BrandsController::class, 'edit']);
            Route::post('/create',  [BrandsController::class, 'update_store_api']);
            Route::patch('/{id}',  [BrandsController::class, 'update_store_api']);
            Route::delete('/{id}',  [BrandsController::class, 'delete_api']);
        });


        //VEHICLES (Car controller)
        Route::prefix('vehicles')->group(function () {
            Route::get('/class', [CarController::class, 'class']);
            Route::get('/fuel', [CarController::class, 'fuel']);
            Route::get('/ownership', [CarController::class, 'ownership']);
            Route::get('/use', [CarController::class, 'use']);
            Route::get('/periodicFeeTypes', [CarController::class, 'periodicFeeTypes']);
            Route::get('/transmission', [CarController::class, 'transmission']);
            Route::get('/drive_type', [CarController::class, 'drive_type']);

            Route::get('/', [CarController::class, 'preview_api']);
            Route::get('/{id}', [CarController::class, 'edit']);
            Route::post('/create', [CarController::class, 'update_store_api']);
            Route::patch('/{id}', [CarController::class, 'update_store_api']);
            Route::delete('/{id}', [CarController::class, 'delete_api']);
        });

           //COLOR TYPES
           Route::middleware('permissions')->prefix('color_types')->group(function() {
            Route::get('/', [ColorTypesController::class, 'preview_api']);
            Route::patch('/{id}', [ColorTypesController::class, 'update_store_api']);
            Route::post('/create', [ColorTypesController::class, 'update_store_api']);
            Route::get('/{id}', [ColorTypesController::class, 'edit']);
            Route::delete('/{id}', [ColorTypesController::class, 'delete_api']);
        });

        //LICENCE PLATE
        // Route::prefix('licencePlate')->group(function () {
        //     Route::get('/{id}', 'LicencePlateController@edit');
        //     Route::post('/create', 'LicencePlateController@store');
        //     Route::patch('/{id}', 'LicencePlateController@update');
        //     Route::delete('/{id}', 'LicencePlateController@delete');
        // });

        //LOCATIONS
        Route::prefix('locations')->group(function () {
            Route::get('/',  [LocationsController::class, 'preview_api']);
            Route::get('/{id}',  [LocationsController::class, 'edit']);
            Route::post('/create',  [LocationsController::class, 'store']);
            Route::patch('/{id}',  [LocationsController::class, 'update']);
            Route::delete('/{id}',  [LocationsController::class, 'delete_api']);
        });

        //STATIONS
        Route::prefix('stations')->group(function () {
            Route::get('/',  [StationsController::class, 'preview_api']);
            Route::get('/{id}',  [StationsController::class, 'edit']);
            Route::post('/create',  [StationsController::class, 'store_api']);
            Route::patch('/{id}',  [StationsController::class, 'update']);
            Route::delete('/{id}',  [StationsController::class, 'delete_api']);
        });

        //PLACES
        Route::prefix('places')->group(function () {
            Route::get('/', [PlacesController::class, 'preview_api']);
            Route::get('/{id}', [PlacesController::class, 'edit']);
            Route::post('/create', [PlacesController::class, 'store']);
            Route::patch('/{id}', [PlacesController::class, 'update']);
            Route::delete('/{id}', [PlacesController::class, 'delete_api']);
        });

        //OPTIONS
        Route::prefix('options')->group(function () {
            Route::get('/', [OptionsController::class, 'preview_api']);
        Route::delete('/{option_type}/uploadRemove/{id}', [OptionsController::class, 'uploadRemove']);
        Route::post('/{option_type}/upload', [OptionsController::class, 'upload']);
            Route::get('/{option_type}', [OptionsController::class, 'preview_api']);
            Route::get('/{option_type}/{id}', [OptionsController::class, 'edit']);
            Route::post('/{option_type}/create', [OptionsController::class, 'update_store_api']);
            Route::patch('/{option_type}/{id}', [OptionsController::class, 'update_store_api']);
            Route::delete('/{option_type}/{id}', [OptionsController::class, 'delete_api']);
        });

         //CATEGORIES
         Route::middleware('permissions')->prefix('categories')->group(function () {
        Route::delete('/uploadRemove/{id}', [CategoriesController::class, 'uploadRemove']);
        Route::post('/upload', [CategoriesController::class, 'upload']);
            Route::get('/', [CategoriesController::class, 'preview_api']);
            Route::get('/{id}', [CategoriesController::class, 'edit']);
            Route::post('/create', [CategoriesController::class, 'update_store_api']);
            Route::patch('/{id}', [CategoriesController::class, 'update_store_api']);
            Route::delete('/{id}', [CategoriesController::class, 'delete_api']);
        });

         //CHARACTERISTICS
         Route::prefix('characteristics')->group(function () {
        Route::delete('/uploadRemove/{id}', [CharacteristicsController::class, 'uploadRemove']);
        Route::post('/upload',  [CharacteristicsController::class, 'upload']);
                Route::get('/',  [CharacteristicsController::class, 'preview_api']);
                Route::get('/{id}',  [CharacteristicsController::class, 'edit']);
                Route::post('/create',  [CharacteristicsController::class, 'update_store_api']);
                Route::patch('/{id}',  [CharacteristicsController::class, 'update_store_api']);
                Route::delete('/{id}',  [CharacteristicsController::class, 'delete_api']);
        });

        //VISIT
        Route::prefix('visit')->group(function () {
            Route::get('/service-details',  [VisitController::class, 'service_details']);
            Route::get('/service_status',  [VisitController::class, 'service_status']);

            Route::get('/',  [VisitController::class, 'preview_api']);
            Route::get('/{id}',  [VisitController::class, 'edit']);
            Route::post('/create',  [VisitController::class, 'store_api']);
            Route::patch('/{id}',  [VisitController::class, 'update_api']);
            Route::delete('/{id}',  [VisitController::class, 'delete_api']);
        });


         //RATE CODES
        Route::prefix('rate-code')->group(function() {
            Route::get('/', [RateCodeController::class, 'preview_api']);
            Route::post('/create',  [RateCodeController::class, 'update_store_api']);
            Route::get('/{id}',  [RateCodeController::class, 'edit']);
            Route::patch('/{id}',  [RateCodeController::class, 'update_store_api']);
            Route::delete('/{id}',  [RateCodeController::class, 'delete_api']);
        });


        //STATUS
        Route::prefix('status')->group(function () {
            Route::get('/', [StatusController::class, 'preview_api']);
            Route::post('/create', [StatusController::class, 'update_store_api']);
            Route::get('/{id}', [StatusController::class, 'edit']);
            Route::patch('/{id}', [StatusController::class, 'update_store_api']);
            Route::delete('/{id}', [StatusController::class, 'delete_api']);
        });

    //TRANSITION
    Route::prefix('transition')->group(function () {
        Route::group([
            'middleware' => 'permissions'
        ], function () {
            Route::delete('/{id}', [TransferController::class, 'delete_api']);
        });
        Route::group([
            'middleware' => 'permissions:editor'
        ], function () {
            Route::get('/type',  [TransferController::class, 'transition_type']);
            Route::get('/',  [TransferController::class, 'preview_api']);
            Route::post('/create',  [TransferController::class, 'update_store_api']);
            Route::patch('/{id}',  [TransferController::class, 'update_store_api']);
            Route::get('/{id}',  [TransferController::class, 'edit']);
        });
    });


    //VEHICLE EXCHANGE
    Route::prefix('vehicle-exchanges')->group(function () {
        Route::group([
            'middleware' => 'permissions'
        ], function () {
            Route::delete('/{id}', [VehicleExchangeController::class, 'delete_api']);
        });
        Route::group([
            'middleware' => 'permissions:editor'
        ], function () {
            Route::get('/', [VehicleExchangeController::class, 'preview_api']);
            Route::get('/{id}', [VehicleExchangeController::class, 'edit_api']);
            Route::post('/create', [VehicleExchangeController::class, 'store_update_api']);
            Route::patch('/{id}', [VehicleExchangeController::class, 'store_update_api']);
        });
    });



        //PAYMENTS
        Route::prefix('payments')->group(function () {
            Route::get('/methods', [PaymentsController::class, 'getMethods']);
            Route::get('/cards', [PaymentsController::class, 'getCards']);
            Route::get('/', [PaymentsController::class, 'preview_api']);
            Route::get('/{payment_type}', [PaymentsController::class, 'preview_api']);
            Route::get('/{payment_type}/{id}', [PaymentsController::class, 'edit']);
            Route::post('/{payment_type}/create', [PaymentsController::class, 'update_store_api']);
            Route::patch('/{payment_type}/{id}', [PaymentsController::class, 'update_store_api']);
            Route::delete('/{payment_type}/{id}', [PaymentsController::class, 'delete_api']);
        });

        //PROGRAMS
        Route::prefix('programs')->group(function () {
            Route::get('/', [ProgramController::class, 'preview_api']);
            Route::get('/{id}',  [ProgramController::class, 'edit']);
        });


          //INVOICES
          Route::middleware('permissions')->prefix('invoices')->group(function () {
        Route::post('/badPrinting',  [InvoicesController::class, 'badPrinting']);
            Route::get('/',  [InvoicesController::class, 'preview_api']);
            Route::get('/{id}',  [InvoicesController::class, 'edit']);
            Route::delete('/{id}',  [InvoicesController::class, 'delete_api']);
            Route::post('/create',  [InvoicesController::class, 'create_api']);
            Route::patch('/{id}',  [InvoicesController::class, 'update_api']);
        });



        //COMPANY PREFERENCES
        Route::prefix('company_preferences')->group(function() {
            Route::get('/',  [CompanyPreferencesController::class, 'edit']);
            Route::post('/',  [CompanyPreferencesController::class, 'update_api']);
        });

        //USERS
        Route::prefix('users')->group(function () {
            Route::get('/',  [UserController::class, 'preview_api']);
            Route::get('/{id}',  [UserController::class, 'edit']);
            Route::post('/create',  [UserController::class, 'update_store_api']);
            Route::patch('/{id}',  [UserController::class, 'update_store_api']);
            Route::delete('/{id}',  [UserController::class, 'delete_api']);
        });

        //SUB ACCOUNTS
        Route::prefix('subaccounts')->group(function() {
            Route::get('/',  [AgentsController::class, 'search_subaccount_with_agent_ajax2']);
        });

          //CUSTOMER
        Route::prefix('customer')->group(function() {
            Route::get('/', [TransactionController::class, 'search_transactor_ajax2']);
        });

        //TAGS
        Route::prefix('tags')->group(function() {
            Route::get('/',  [TagController::class, 'preview_api']);
        });

    // PDF-Mail
    Route::get('/create-payment-pdf/{id}',  [PDFController::class, 'create_payment_api']);
    Route::post('/mail-payment-pdf',  [PDFController::class, 'mail_payment']);//same as v1

    Route::get('/create-invoice-pdf/{id}',  [PDFController::class, 'create_invoice_api']);
    Route::post('/mail-invoice-pdf',  [PDFController::class, 'mail_invoice']); //same as v1

    Route::get('/create-booking-pdf/{id}',  [PDFController::class, 'create_booking_api']);
    Route::post('/mail-booking-pdf',  [PDFController::class, 'mail_booking']);//same as v1

    Route::get('/create-quote-pdf/{id}',  [PDFController::class, 'create_quote_api']);
    Route::post('/mail-quote-pdf',  [PDFController::class, 'mail_quote']); //same as v1

    Route::get('/create-rental-pdf/{id}',  [PDFController::class, 'create_rental_api']);
    Route::post('/mail-rental-pdf',  [PDFController::class, 'mail_rental']);//same as v1

    });
