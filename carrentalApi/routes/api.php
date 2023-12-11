<?php

use App\Http\Controllers\AuthController;
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
Route::post('logout', 'AuthController@logout');
Route::get('user', 'AuthController@user');
Route::post('resetPassword', 'AuthController@resetPassword');


// Route::group([
//     'prefix' => '{locale}',
//     'where' => ['locale' => '[a-zA-Z]{2}'],
//     'middleware' => ['setlocale']], function () {

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });



    Route::group([
        'middleware' => ['auth:api']
    ], function () {
    Route::get('refresh', 'AuthController@refresh');

    //Home and combined
    Route::prefix('home')->group(function () {
        Route::get('/', 'HomeController@index_api');
        Route::get('/combined', 'ExportController@combinedCollections');
        Route::get('/combinedVehicles', 'ExportController@combinedCollectionsVehicles');
        Route::get('/combinedBookingSources', 'ExportController@combinedCollectionsBookingSources');
        Route::get('/combinedUsers', 'ExportController@combinedCollectionsUsers');
        Route::get('/combinedTypes', 'ExportController@combinedCollectionsTypes');
        Route::get('/combinedAgents', 'ExportController@combinedCollectionsAgents');
        Route::get('/combinedInvoices', 'ExportController@combinedCollectionsInvoices');
        Route::get('/combinedPayments', 'ExportController@combinedCollectionsPayments');
        Route::get('/combinedQuotes', 'ExportController@combinedCollectionsQuotes');
        Route::get('/combinedBookings', 'ExportController@combinedCollectionsBookings');
        Route::get('/combinedRentals', 'ExportController@combinedCollectionsRentals');
    });

        //TYPES
        Route::prefix('types')->group(function () {
        Route::post('/upload', 'TypesController@upload');
        Route::delete('/uploadRemove/{id}', 'TypesController@uploadRemove');
            Route::get('/', 'TypesController@preview_api');
            Route::get('/{id}', 'TypesController@edit');
            Route::patch('/{id}', 'TypesController@update');
            Route::post('/create', 'TypesController@createApi');
            Route::delete('/{id}', 'TypesController@delete_api');
        });

        //IMAGES
    Route::prefix('image')->group(function () {
        Route::delete('/', 'ImageController@removeImageLink');
        Route::post('/upload', 'ImageController@uploadImage');
    });


         //DOCUMENT TYPES
        Route::prefix('documentType')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'DocumentTypeController@preview_api');
                Route::post('/create', 'DocumentTypeController@update_store_api');
                Route::get('/{id}', 'DocumentTypeController@edit');
                Route::patch('/{id}', 'DocumentTypeController@update_store_api');
                Route::delete('/{id}', 'DocumentTypeController@delete_api');
            });
        });

        //DOCUMENTS
        Route::prefix('document')->group(function () {
        Route::post('/upload', 'DocumentController@update_store_api');
            Route::get('/', 'DocumentController@preview_api');
            Route::post('/create', 'DocumentController@update_store_api');
            Route::get('/{id}', 'DocumentController@edit');
            Route::patch('/{id}', 'DocumentController@update_store_api');
            Route::delete('/{id}', 'DocumentController@delete_api');
        });


          //ROLES
        Route::middleware('permissions')->prefix('roles')->group(function () {
            Route::get('/', 'RolesController@preview_api');
            Route::patch('/{id}', 'RolesController@update_store_api');
            Route::get('/{id}', 'RolesController@edit');
            Route::post('/create', 'RolesController@update_store_api');
            Route::delete('/{id}', 'RolesController@delete_api');
        });

        //AGENTS
        Route::prefix('agents')->group(function () {
            Route::get('/', 'AgentsController@preview_api');

            Route::get('/{id}', 'AgentsController@edit');
            Route::post('/create', 'AgentsController@update_store_api');
            Route::patch('/{id}', 'AgentsController@update_store_api');
            Route::delete('/{id}', 'AgentsController@delete_api');
        });

        //CONTACTS
        Route::prefix('contacts')->group(function () {
            Route::get('/', 'ContactController@preview_api');
            Route::get('/{id}', 'ContactController@edit');
            Route::post('/create', 'ContactController@update_store_api');
            Route::patch('/{id}', 'ContactController@update_store_api');
            Route::delete('/{id}', 'ContactController@delete_api');
        });


         //LANGUAGES
        Route::middleware('permissions')->prefix('languages')->group(function () {
            Route::get('/', 'LanguagesController@preview_api');
            Route::patch('/{id}', 'LanguagesController@update_store_api');
            Route::get('/{id}', 'LanguagesController@edit');
            Route::post('/create', 'LanguagesController@update_store_api');
            Route::delete('/{id}', 'LanguagesController@delete_api');
        });

        //DRIVERS
        Route::prefix('drivers')->group(function () {
        Route::get('/emp', 'DriversController@previewEmp');
        Route::get('/emp/{id}', 'DriversController@editEmp');

            Route::get('/', 'DriversController@preview_api');
            Route::get('/{id}', 'DriversController@edit');
            Route::post('/create', 'DriversController@update_store_api');
            Route::patch('/{id}', 'DriversController@update_store_api');
            Route::delete('/{id}', 'DriversController@delete_api');
        });

        //COMPANIES
        Route::prefix('companies')->group(function () {
            Route::get('/', 'CompaniesController@preview_api');
            Route::get('/{id}', 'CompaniesController@edit');
            Route::post('/create', 'CompaniesController@update_store_api');
            Route::patch('/{id}', 'CompaniesController@update_store_api');
            Route::delete('/{id}', 'CompaniesController@delete_api');
        });

        //BOOKING SOURCES
        Route::prefix('booking_sources')->group(function () {
            Route::get('/', 'BookingSourceController@preview_api');
            Route::get('/{id}', 'BookingSourceController@edit');
            Route::post('/create', 'BookingSourceController@update_store_api');
            Route::patch('/{id}', 'BookingSourceController@update_store_api');
            Route::delete('/{id}', 'BookingSourceController@delete_api');
        });

    //QUOTES
    Route::prefix('quotes')->group(function () {
        Route::get('/', 'QuoteController@preview_api');
        Route::get('/{id}', 'QuoteController@edit');
        Route::post('/create', 'QuoteController@create_api');
        Route::patch('/{id}', 'QuoteController@create_api');
        Route::delete('/{id}', 'QuoteController@delete_api');
    });


         //BOOKINGS
         Route::prefix('bookings')->group(function () {
            Route::get('/', 'BookingController@preview_api');
            Route::get('/{id}', 'BookingController@edit');
            Route::post('/create', 'BookingController@create_api');
            Route::patch('/{id}', 'BookingController@create_api');
            Route::delete('/{id}', 'BookingController@delete_api');
        Route::options('/reason', 'BookingController@reason');
        Route::get('/reason/{id}', 'BookingController@reasonGetOne');
        });


    //RENTALS
    Route::prefix('rentals')->group(function () {
        Route::get('/', 'RentalController@preview_api');
        Route::get('/{id}', 'RentalController@edit');
        Route::post('/create', 'RentalController@create_api');
        Route::patch('/{id}', 'RentalController@create_api');
        Route::delete('/{id}', 'RentalController@delete_api');

        //signature
        Route::post('/signatureExcess', 'SignatureController@uploadSignatureExcess');
        Route::post('/signatureExcDelete', 'SignatureController@deleteSignatureExcess');
        Route::post('/signatureSee1', 'SignatureController@SignatureSee1');

        Route::post('/signatureMain', 'SignatureController@uploadSignatureMain');
        Route::post('/signatureMDelete', 'SignatureController@deleteSignatureMain');
        Route::post('/signatureSee2', 'SignatureController@SignatureSee2');

        Route::post('/signatureSecDriver', 'SignatureController@uploadSignatureSecDriver');
        Route::post('/signatureSecDelete', 'SignatureController@deleteSignatureSecDriver');
        Route::post('/signatureSee3', 'SignatureController@SignatureSee3');
    });

        //BRANDS
        Route::prefix('brands')->group(function () {
        Route::post('/upload', 'BrandsController@upload');
        Route::delete('/uploadRemove/{id}', 'BrandsController@uploadRemove');
            Route::get('/', 'BrandsController@preview_api');
            Route::get('/{id}', 'BrandsController@edit');
            Route::post('/create', 'BrandsController@update_store_api');
            Route::patch('/{id}', 'BrandsController@update_store_api');
            Route::delete('/{id}', 'BrandsController@delete_api');
        });


        //VEHICLES (Car controller)
        Route::prefix('vehicles')->group(function () {
            Route::get('/class', 'CarController@class');
            Route::get('/fuel', 'CarController@fuel');
            Route::get('/ownership', 'CarController@ownership');
            Route::get('/use', 'CarController@use');
            Route::get('/periodicFeeTypes', 'CarController@periodicFeeTypes');
            Route::get('/transmission', 'CarController@transmission');
            Route::get('/drive_type', 'CarController@drive_type');

            Route::get('/', 'CarController@preview_api');
            Route::get('/{id}', 'CarController@edit');
            Route::post('/create', 'CarController@update_store_api');
            Route::patch('/{id}', 'CarController@update_store_api');
            Route::delete('/{id}', 'CarController@delete_api');
        });

           //COLOR TYPES
           Route::middleware('permissions')->prefix('color_types')->group(function() {
            Route::get('/', 'ColorTypesController@preview_api');
            Route::patch('/{id}', 'ColorTypesController@update_store_api');
            Route::post('/create', 'ColorTypesController@update_store_api');
            Route::get('/{id}', 'ColorTypesController@edit');
            Route::delete('/{id}', 'ColorTypesController@delete_api');
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
            Route::get('/', 'LocationsController@preview_api');
            Route::get('/{id}', 'LocationsController@edit');
            Route::post('/create', 'LocationsController@store');
            Route::patch('/{id}', 'LocationsController@update');
            Route::delete('/{id}', 'LocationsController@delete_api');
        });

        //STATIONS
        Route::prefix('stations')->group(function () {
            Route::get('/', 'StationsController@preview_api');
            Route::get('/{id}', 'StationsController@edit');
            Route::post('/create', 'StationsController@store_api');
            Route::patch('/{id}', 'StationsController@update');
            Route::delete('/{id}', 'StationsController@delete_api');
        });

        //PLACES
        Route::prefix('places')->group(function () {
            Route::get('/', 'PlacesController@preview_api');
            Route::get('/{id}', 'PlacesController@edit');
            Route::post('/create', 'PlacesController@store');
            Route::patch('/{id}', 'PlacesController@update');
            Route::delete('/{id}', 'PlacesController@delete_api');
        });

        //OPTIONS
        Route::prefix('options')->group(function () {
            Route::get('/', 'OptionsController@preview_api');
        Route::delete('/{option_type}/uploadRemove/{id}', 'OptionsController@uploadRemove');
        Route::post('/{option_type}/upload', 'OptionsController@upload');
            Route::get('/{option_type}', 'OptionsController@preview_api');
            Route::get('/{option_type}/{id}', 'OptionsController@edit');
            Route::post('/{option_type}/create', 'OptionsController@update_store_api');
            Route::patch('/{option_type}/{id}', 'OptionsController@update_store_api');
            Route::delete('/{option_type}/{id}', 'OptionsController@delete_api');
        });

         //CATEGORIES
         Route::middleware('permissions')->prefix('categories')->group(function () {
        Route::delete('/uploadRemove/{id}', 'CategoriesController@uploadRemove');
        Route::post('/upload', 'CategoriesController@upload');
            Route::get('/', 'CategoriesController@preview_api');
            Route::get('/{id}', 'CategoriesController@edit');
            Route::post('/create', 'CategoriesController@update_store_api');
            Route::patch('/{id}', 'CategoriesController@update_store_api');
            Route::delete('/{id}', 'CategoriesController@delete_api');
        });

         //CHARACTERISTICS
         Route::prefix('characteristics')->group(function () {
        Route::delete('/uploadRemove/{id}', 'CharacteristicsController@uploadRemove');
        Route::post('/upload', 'CharacteristicsController@upload');
                Route::get('/', 'CharacteristicsController@preview_api');
                Route::get('/{id}', 'CharacteristicsController@edit');
                Route::post('/create', 'CharacteristicsController@update_store_api');
                Route::patch('/{id}', 'CharacteristicsController@update_store_api');
                Route::delete('/{id}', 'CharacteristicsController@delete_api');
        });

        //VISIT
        Route::prefix('visit')->group(function () {
            Route::get('/service-details', 'VisitController@service_details');
            Route::get('/service_status', 'VisitController@service_status');

            Route::get('/', 'VisitController@preview_api');
            Route::get('/{id}', 'VisitController@edit');
            Route::post('/create', 'VisitController@store_api');
            Route::patch('/{id}', 'VisitController@update_api');
            Route::delete('/{id}', 'VisitController@delete_api');
        });


         //RATE CODES
        Route::prefix('rate-code')->group(function() {
            Route::get('/', 'RateCodeController@preview_api');
            Route::post('/create', 'RateCodeController@update_store_api');
            Route::get('/{id}', 'RateCodeController@edit');
            Route::patch('/{id}', 'RateCodeController@update_store_api');
            Route::delete('/{id}', 'RateCodeController@delete_api');
        });


        //STATUS
        Route::prefix('status')->group(function () {
            Route::get('/', 'StatusController@preview_api');
            Route::post('/create', 'StatusController@update_store_api');
            Route::get('/{id}', 'StatusController@edit');
            Route::patch('/{id}', 'StatusController@update_store_api');
            Route::delete('/{id}', 'StatusController@delete_api');
        });

    //TRANSITION
    Route::prefix('transition')->group(function () {
        Route::group([
            'middleware' => 'permissions'
        ], function () {
            Route::delete('/{id}', 'TransferController@delete_api');
        });
        Route::group([
            'middleware' => 'permissions:editor'
        ], function () {
            Route::get('/type', 'TransferController@transition_type');
            Route::get('/', 'TransferController@preview_api');
            Route::post('/create', 'TransferController@update_store_api');
            Route::patch('/{id}', 'TransferController@update_store_api');
            Route::get('/{id}', 'TransferController@edit');
        });
    });


    //VEHICLE EXCHANGE
    Route::prefix('vehicle-exchanges')->group(function () {
        Route::group([
            'middleware' => 'permissions'
        ], function () {
            Route::delete('/{id}', 'VehicleExchangeController@delete_api');
        });
        Route::group([
            'middleware' => 'permissions:editor'
        ], function () {
            Route::get('/', 'VehicleExchangeController@preview_api');
            Route::get('/{id}', 'VehicleExchangeController@edit_api');
            Route::post('/create', 'VehicleExchangeController@store_update_api');
            Route::patch('/{id}', 'VehicleExchangeController@store_update_api');
        });
    });



        //PAYMENTS
        Route::prefix('payments')->group(function () {
            Route::get('/methods', 'PaymentsController@getMethods');
            Route::get('/cards', 'PaymentsController@getCards');
            Route::get('/', 'PaymentsController@preview_api');
            Route::get('/{payment_type}', 'PaymentsController@preview_api');
            Route::get('/{payment_type}/{id}', 'PaymentsController@edit');
            Route::post('/{payment_type}/create', 'PaymentsController@update_store_api');
            Route::patch('/{payment_type}/{id}', 'PaymentsController@update_store_api');
            Route::delete('/{payment_type}/{id}', 'PaymentsController@delete_api');
        });

        //PROGRAMS
        Route::prefix('programs')->group(function () {
            Route::get('/', 'ProgramController@preview_api');
            Route::get('/{id}', 'ProgramController@edit');
        });


          //INVOICES
          Route::middleware('permissions')->prefix('invoices')->group(function () {
        Route::post('/badPrinting', 'InvoicesController@badPrinting');
            Route::get('/', 'InvoicesController@preview_api');
            Route::get('/{id}', 'InvoicesController@edit');
            Route::delete('/{id}', 'InvoicesController@delete_api');
            Route::post('/create', 'InvoicesController@create_api');
            Route::patch('/{id}', 'InvoicesController@update_api');
        });



        //COMPANY PREFERENCES
        Route::prefix('company_preferences')->group(function() {
            Route::get('/', 'CompanyPreferencesController@edit');
            Route::post('/', 'CompanyPreferencesController@update_api');
        });

        //USERS
        Route::prefix('users')->group(function () {
            Route::get('/', 'UserController@preview_api');
            Route::get('/{id}', 'UserController@edit');
            Route::post('/create', 'UserController@update_store_api');
            Route::patch('/{id}', 'UserController@update_store_api');
            Route::delete('/{id}', 'UserController@delete_api');
        });

        //SUB ACCOUNTS
        Route::prefix('subaccounts')->group(function() {
            Route::get('/', 'AgentsController@search_subaccount_with_agent_ajax2');
        });

          //CUSTOMER
        Route::prefix('customer')->group(function() {
            Route::get('/', 'TransactionController@search_transactor_ajax2');
        });

        //TAGS
        Route::prefix('tags')->group(function() {
            Route::get('/', 'TagController@preview_api');
        });

    // PDF-Mail
    Route::get('/create-payment-pdf/{id}', 'PDFController@create_payment_api');
    Route::post('/mail-payment-pdf', 'PDFController@mail_payment');//same as v1

    Route::get('/create-invoice-pdf/{id}', 'PDFController@create_invoice_api');
    Route::post('/mail-invoice-pdf', 'PDFController@mail_invoice'); //same as v1

    Route::get('/create-booking-pdf/{id}', 'PDFController@create_booking_api');
    Route::post('/mail-booking-pdf', 'PDFController@mail_booking');//same as v1

    Route::get('/create-quote-pdf/{id}', 'PDFController@create_quote_api');
    Route::post('/mail-quote-pdf', 'PDFController@mail_quote'); //same as v1

    Route::get('/create-rental-pdf/{id}', 'PDFController@create_rental_api');
    Route::post('/mail-rental-pdf', 'PDFController@mail_rental');//same as v1


    });
