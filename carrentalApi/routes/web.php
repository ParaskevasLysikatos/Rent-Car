<?php

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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => ['setlocale']], function () {

    Auth::routes();

    Route::group([
        'middleware' => ['auth']
    ], function () {

        Route::get('/', 'HomeController@index')->name('home');

        Route::get('/home', 'HomeController@index')->name('home');

        Route::get('/tax-exemption', function (){
            return view('template-parts.tax-exemption');
        })->name('tax-exemption');

        //CATEGORIES
        Route::middleware('permissions')->prefix('categories')->group(function () {
            Route::get('/', 'CategoriesController@preview')->name('categories');
            Route::get('/create', 'CategoriesController@create_view')->name('create_category_view');
            Route::get('/search', 'CategoriesController@search')->name('search_category');
            Route::get('/edit', 'CategoriesController@create_view')->name('edit_category_view');
            Route::post('/create', 'CategoriesController@create')->name('create_category');
            Route::post('/delete', 'CategoriesController@delete')->name('delete_categories');
            Route::post('/delete_icon', 'CategoriesController@delete_icon')->name('delete_category_icon');
        });

        //CHARACTERISTICS
        Route::prefix('characteristics')->group(function () {
            Route::post('/search', 'CharacteristicsController@preview')->name('search_characteristic_ajax');
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'CharacteristicsController@preview')->name('characteristics');
                Route::get('/create', 'CharacteristicsController@create_view')->name('create_characteristic_view');
                Route::get('/search', 'CharacteristicsController@search')->name('search_characteristic');
                Route::get('/edit', 'CharacteristicsController@create_view')->name('edit_characteristic_view');
                Route::post('/create', 'CharacteristicsController@create')->name('create_characteristic');
                Route::post('/delete', 'CharacteristicsController@delete')->name('delete_characteristics');
                Route::post('/delete_icon', 'CharacteristicsController@delete_icon')->name('delete_characteristic_icon');
            });
        });

        //OPTIONS
        Route::middleware('permissions')->prefix('options/{option_type}')->group(function () {
            Route::post('/search', 'OptionsController@search_ajax')->name('search_option_ajax');
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'OptionsController@preview')->name('options');
                Route::get('/create', 'OptionsController@create_view')->name('create_option_view');
                Route::get('/search', 'OptionsController@search')->name('search_option');
                Route::get('/edit', 'OptionsController@create_view')->name('edit_option_view');
                Route::post('/create', 'OptionsController@create')->name('create_option');
                Route::post('/delete', 'OptionsController@delete')->name('delete_options');
                Route::post('/delete_icon', 'OptionsController@delete_icon')->name('delete_option_icon');
            });
        });

        //TYPES
        Route::prefix('types')->group(function () {
            Route::post('/search', 'TypesController@search_ajax')->name('search_group_ajax');
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'TypesController@preview')->name('types');
                Route::get('/create', 'TypesController@create_view')->name('create_type_view');
                Route::get('/search', 'TypesController@search')->name('search_type');
                Route::get('/edit', 'TypesController@create_view')->name('edit_type_view');
                Route::post('/create', 'TypesController@create')->name('create_type');
                Route::post('/delete', 'TypesController@delete')->name('delete_types');
                Route::post('/delete_type_image', 'TypesController@delete_type_image')->name('delete_type_image');
            });
        });

        //LANGUAGES
        Route::middleware('permissions')->prefix('languages')->group(function () {
            Route::get('/', 'LanguagesController@preview')->name('languages');
            Route::get('/create', 'LanguagesController@create_view')->name('create_language_view');
            Route::get('/search', 'LanguagesController@search')->name('search_language');
            Route::get('/edit', 'LanguagesController@create_view')->name('edit_language_view');
            Route::post('/create', 'LanguagesController@create')->name('create_language');
            Route::post('/delete', 'LanguagesController@delete')->name('delete_languages');
            Route::post('/delete_language_image', 'LanguagesController@delete_language_image')->name('delete_language_image');
            Route::post('/order', 'LanguagesController@order')->name('languages_order');
        });

        //LOCATIONS
        Route::prefix('locations')->group(function () {
            Route::get('/search', 'LocationsController@search')->name('search_location');
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'LocationsController@preview')->name('locations');
                Route::get('/create', 'LocationsController@create_view')->name('create_location_view');
                Route::get('/edit', 'LocationsController@create_view')->name('edit_location_view');
                Route::post('/create', 'LocationsController@create')->name('create_location');
                Route::post('/delete', 'LocationsController@delete')->name('delete_locations');
            });
        });

        //STATIONS
        Route::prefix('stations')->group(function () {
            Route::post('/search', 'StationsController@search_ajax')->name('search_station_ajax');
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'StationsController@preview')->name('stations');
                Route::get('/create', 'StationsController@create_view')->name('create_station_view');
                Route::get('/search', 'StationsController@search')->name('search_station');
                Route::get('/edit', 'StationsController@create_view')->name('edit_station_view');
                Route::post('/create', 'StationsController@create')->name('create_station');
                Route::post('/delete', 'StationsController@delete')->name('delete_stations');
            });
        });

        //PLACES
        Route::prefix('places')->group(function () {
            Route::post('/search', 'PlacesController@search_ajax')->name('search_place_ajax');
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'PlacesController@preview')->name('places');
                Route::get('/populate', 'PlacesController@populate')->name('populate_places');
                Route::get('/create', 'PlacesController@create_view')->name('create_place_view');
                Route::get('/search', 'PlacesController@search')->name('search_place');
                Route::get('/edit', 'PlacesController@create_view')->name('edit_place_view');
                Route::post('/create', 'PlacesController@create')->name('create_place');
                Route::post('/delete', 'PlacesController@delete')->name('delete_places');
            });
        });

        //USERS
        Route::middleware('permissions')->prefix('users')->group(function () {
            Route::get('/', 'UserController@preview')->name('users');
            Route::get('/create', 'UserController@create_view')->name('create_user_view');
            Route::get('/search', 'UserController@search')->name('search_user');
            Route::get('/edit', 'UserController@create_view')->name('edit_user_view');
            Route::post('/create', 'UserController@create')->name('create_user');
            Route::post('/delete', 'UserController@delete')->name('delete_users');
            Route::post('/search', 'UserController@search_ajax')->name('search_user_ajax');

        });

        //ROLES
        Route::middleware('permissions')->prefix('roles')->group(function () {
            Route::get('/', 'RolesController@preview')->name('roles');
            Route::get('/create', 'RolesController@create_view')->name('create_role_view');
            Route::get('/search', 'RolesController@search')->name('search_role');
            Route::get('/edit', 'RolesController@create_view')->name('edit_role_view');
            Route::post('/create', 'RolesController@create')->name('create_role');
            Route::post('/delete', 'RolesController@delete')->name('delete_roles');
        });

        //CARS-VEHICLES
        Route::prefix('cars')->group(function () {
            Route::get('/', 'CarController@preview')->name('cars');

            Route::post('/search', 'CarController@search_ajax')->name('search_vehicle_ajax');
            Route::post('/getData', 'CarController@get_data_ajax')->name('get_vehicle_data_ajax');
            Route::post('/transfer_car', 'TransferController@transfer_car')->name('transfer_car');
            Route::get('/{id}/view', 'CarController@view')->name('view_car');
            Route::get('/edit', 'CarController@create_view')->name('edit_car_view');

            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/create', 'CarController@create_view')->name('create_car_view');
                Route::get('/search', 'CarController@search')->name('search_car');

                Route::post('/create', 'CarController@create')->name('create_car');
                Route::post('/delete', 'CarController@delete')->name('delete_cars');
                Route::post('/delete_plate', 'CarController@delete_plate')->name('delete_plate');
                Route::post('/create_plate', 'CarController@create_plate')->name('create_plate');
                Route::post('/delete_car_image', 'CarController@delete_car_image')->name('delete_car_image');

                Route::post('/create_fee', 'CarController@create_fee')->name('create_fee');
                Route::post('/update_fee', 'CarController@update_fee')->name('update_fee');
                Route::post('/delete_fee', 'CarController@delete_fee')->name('delete_fee');
                Route::post('/choose_location', 'CarController@choose_location')->name('choose_location');
                Route::post('/transfer_cars', 'CarController@transfer_cars')->name('transfer_cars');
                Route::post('/display_maintenances', 'CarController@display_maintenances')->name('display_maintenances');
                Route::post('/update_maintenances', 'CarController@update_maintenances')->name('update_maintenances');

                Route::get('/photoshoot', 'CarController@photoshoot')->name('photoshoot');
                Route::post('/document_upload_image', 'CarController@document_upload_image')->name('document_upload_image');
            });
        });

        //CONTACTS
        Route::prefix('contacts')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::post('/delete', 'ContactController@delete')->name('delete_contacts');
            });
            Route::group([
                'middleware' => 'permissions:editor'
            ], function () {
                Route::get('/', 'ContactController@preview')->name('contacts');
                Route::get('/create', 'ContactController@create_view')->name('create_contact_view');
                // Route::get('/search', 'ContactController@search')->name('search_contact');
                Route::get('/edit', 'ContactController@create_view')->name('edit_contact_view');
                Route::post('/create', 'ContactController@create')->name('create_contact');
                // Route::post('/searchCompany', 'ContactController@searchData')->name('search_for_company');
                // Route::post('/addCompany', 'ContactController@addCompany')->name('contact_add_company');
                // Route::post('/deleteCompany', 'ContactController@deleteCompany')->name('contact_delete_company');
                Route::post('/search', 'ContactController@search_ajax')->name('search_contact_ajax');
            });
        });

        //DRIVERS
        Route::prefix('drivers')->group(function () {
            Route::post('/search', 'DriversController@search_ajax')->name('search_driver_ajax');
            Route::post('/search_with_contacts', 'DriversController@search_with_contacts_ajax')->name('search_driver_with_contacts_ajax');
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::post('/delete', 'DriversController@delete')->name('delete_drivers');
            });
            Route::group([
                'middleware' => 'permissions:editor'
            ], function () {
                Route::get('/', 'DriversController@preview')->name('drivers');
                Route::get('/create', 'DriversController@create_view')->name('create_driver_view');
                Route::get('/search', 'DriversController@search')->name('search_driver');
                Route::get('/edit', 'DriversController@create_view')->name('edit_driver_view');
                Route::post('/create', 'DriversController@create')->name('create_driver');
                Route::post('/searchCompany', 'DriversController@searchData')->name('search_for_company');
                Route::post('/addCompany', 'DriversController@addCompany')->name('driver_add_company');
                Route::post('/deleteCompany', 'DriversController@deleteCompany')->name('driver_delete_company');
            });
        });

        //COMPANIES
        Route::prefix('companies')->group(function () {
            Route::post('/search', 'CompaniesController@search_ajax')->name('search_company_ajax');
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::post('/delete', 'CompaniesController@delete')->name('delete_companies');
            });
            Route::group([
                'middleware' => 'permissions:editor'
            ], function () {
                Route::get('/', 'CompaniesController@preview')->name('companies');
                Route::get('/create', 'CompaniesController@create_view')->name('create_company_view');
                Route::get('/search', 'CompaniesController@search')->name('search_company');
                Route::get('/edit', 'CompaniesController@create_view')->name('edit_company_view');
                Route::post('/create', 'CompaniesController@create')->name('create_company');
                Route::post('/search_driver', 'CompaniesController@searchData')->name('search_for_driver');
                Route::post('/addDriver', 'CompaniesController@addDriver')->name('company_add_driver');
                Route::post('/deleteDriver', 'CompaniesController@deleteDriver')->name('company_delete_driver');
            });
        });

        //TRANSITION
        Route::prefix('transition')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::post('/delete', 'TransferController@delete')->name('delete_transfers');
            });
            Route::group([
                'middleware' => 'permissions:editor'
            ], function () {
                Route::get('/', 'TransferController@preview')->name('transfers');
                Route::get('/create', 'TransferController@create_view')->name('create_transfer_view');
                Route::get('/search', 'TransferController@search')->name('search_transfer');
                Route::get('/edit', 'TransferController@create_view')->name('edit_transfer_view');
            });
        });

        //Affiliate-AGENTS
        Route::prefix('affiliate')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/create', 'AgentsController@create_view')->name('create_agent_view');
                Route::post('/create', 'AgentsController@create')->name('create_agent');
                Route::post('/delete', 'AgentsController@delete')->name('delete_agents');
            });
            Route::group([
                'middleware' => 'permissions:editor'
            ], function () {
                Route::get('/edit', 'AgentsController@create_view')->name('edit_agent_view');
                Route::get('/', 'AgentsController@preview')->name('agents');
                Route::get('/search', 'AgentsController@search')->name('search_agent');
                Route::post('/search', 'AgentsController@search_agent_ajax')->name('search_agent_ajax');
                Route::post('search_subaccount', 'AgentsController@search_subaccount_ajax')->name('search_sub_account_ajax');
                Route::post('search_subaccount_with_agent', 'AgentsController@search_subaccount_with_agent_ajax')->name('search_sub_account_with_agent_ajax');
            });
        });

        //DOCUMENT TYPES
        Route::prefix('documentType')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'DocumentTypeController@preview')->name('documentTypes');
                Route::get('/create', 'DocumentTypeController@create_view')->name('create_documentType_view');
                Route::get('/search', 'DocumentTypeController@search')->name('search_documentType');
                Route::get('/edit', 'DocumentTypeController@create_view')->name('edit_documentType_view');
                Route::post('/create', 'DocumentTypeController@create')->name('create_documentType');
                Route::post('/delete', 'DocumentTypeController@delete')->name('delete_documentTypes');
            });
        });

        //DOCUMENTS
        Route::prefix('document')->group(function () {
            Route::get('/', 'DocumentController@preview')->name('documents');
            Route::get('/create', 'DocumentController@create_view')->name('create_document_view');
            Route::get('/search', 'DocumentController@search')->name('search_document');
            Route::get('/edit', 'DocumentController@create_view')->name('edit_document_view');
            Route::post('/create', 'DocumentController@create')->name('create_document');
            Route::post('/delete', 'DocumentController@delete')->name('delete_documents');
        });

        //BRANDS
        Route::prefix('brand')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'BrandsController@preview')->name('brands');
                Route::get('/create', 'BrandsController@create_view')->name('create_brand_view');
                Route::get('/search', 'BrandsController@search')->name('search_brand');
                Route::get('/edit', 'BrandsController@create_view')->name('edit_brand_view');
                Route::post('/create', 'BrandsController@create')->name('create_brand');
                Route::post('/delete', 'BrandsController@delete')->name('delete_brands');
                Route::post('/delete_icon', 'BrandsController@delete_icon')->name('delete_brand_icon');
            });
        });

        //VISIT
        Route::prefix('visit')->group(function () {
            Route::get('/', 'VisitController@preview')->name('visits');
            Route::get('/create', 'VisitController@create_view')->name('create_visit_view');
            Route::get('/search', 'VisitController@search')->name('search_visit');
            Route::get('/edit', 'VisitController@create_view')->name('edit_visit_view');
            Route::post('/create', 'VisitController@create')->name('create_visit');
            Route::post('/delete', 'VisitController@delete')->name('delete_visits');
        });


        //QUOTES
        Route::prefix('quote')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::post('/delete', 'QuoteController@delete')->name('delete_quotes');
            });
            Route::group([
                'middleware' => 'permissions:editor'
            ], function () {
                Route::get('/', 'QuoteController@preview')->name('quotes');
                Route::get('/create', 'QuoteController@create_view')->name('create_quote_view');
                Route::get('/edit', 'QuoteController@create_view')->name('edit_quote_view');
                Route::post('/create', 'QuoteController@create')->name('create_quote');
            });
        });

        //BOOKING
        Route::prefix('booking')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::post('/delete', 'BookingController@delete')->name('delete_bookings');
            });
            Route::group([
                'middleware' => 'permissions:editor'
            ], function () {
                Route::get('/', 'BookingController@preview')->name('bookings');
                Route::get('/create', 'BookingController@create_view')->name('create_booking_view');
                Route::get('/search', 'BookingController@search')->name('search_booking');
                Route::get('/edit', 'BookingController@create_view')->name('edit_booking_view');
                Route::post('/create', 'BookingController@create')->name('create_booking');
            });
        });


        //RENTAL
        Route::prefix('rental')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::post('/delete', 'RentalController@delete')->name('delete_rentals');
            });
            Route::group([
                'middleware' => 'permissions:editor'
            ], function () {
                Route::get('/', 'RentalController@preview')->name('rentals');
                Route::get('/create', 'RentalController@create_view')->name('create_rental_view');
                Route::get('/edit', 'RentalController@create_view')->name('edit_rental_view');
                Route::post('/create', 'RentalController@create')->name('create_rental');
                Route::post('/search_ajax', 'RentalController@search_ajax')->name('search_rental_ajax');
                Route::get('/{rental_id}/exchange', 'VehicleExchangeController@create')->name('exchange_view');
                Route::post('/{rental_id}/exchange', 'VehicleExchangeController@store')->name('exchange_car');

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
        });

        //VEHICLE EXCHANGE
        Route::prefix('vehicle-exchanges')->group(function () {
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::post('/delete', 'VehicleExchangeController@delete')->name('delete_exchanges');
            });
            Route::group([
                'middleware' => 'permissions:editor'
            ], function () {
                Route::get('/', 'VehicleExchangeController@preview')->name('exchanges');
                Route::get('/create', 'VehicleExchangeController@create_view')->name('create_exchange_view');
                Route::get('/edit', 'VehicleExchangeController@edit')->name('edit_exchange_view');
                Route::post('/create', 'VehicleExchangeController@create')->name('create_exchange');
                Route::post('/search_ajax', 'VehicleExchangeController@search_ajax')->name('search_exchange_ajax');
            });
        });

        //BOOKING SOURCE
        Route::prefix('booking_source')->group(function () {
            Route::post('/search_ajax', 'BookingSourceController@search_ajax')->name('search_source_ajax');
            Route::group([
                'middleware' => 'permissions'
            ], function () {
                Route::get('/', 'BookingSourceController@preview')->name('booking_sources');
                Route::get('/create', 'BookingSourceController@create_view')->name('create_booking_souce_view');
                Route::get('/edit', 'BookingSourceController@create_view')->name('edit_booking_source_view');
                Route::post('/create', 'BookingSourceController@create')->name('create_booking_source');
                Route::post('/delete', 'BookingSourceController@delete')->name('delete_booking_sources');
            });
        });


        //PAYMENTS
        Route::prefix('payments')->group(function () {
            Route::post('/create', 'PaymentsController@create')->name('create_payment')->middleware('permissions');
            Route::prefix('/{payment_type}')->group(function () {
                Route::post('/search_ajax', 'PaymentsController@search_ajax')->name('search_payment_ajax');
                Route::post('/delete', 'PaymentsController@delete')->name('delete_payments')->middleware('permissions');
                Route::get('/', 'PaymentsController@preview')->name('payments')->middleware('permissions:editor');
                Route::get('/create', 'PaymentsController@create_view')->name('create_payment_view')->middleware('permissions:editor');
                Route::get('/edit', 'PaymentsController@create_view')->name('edit_payment_view')->middleware('permissions');
            });
        });

            //INVOICES
        Route::middleware('permissions')->prefix('invoices')->group(function () {
            Route::get('/', 'InvoicesController@preview')->name('invoices');
            Route::get('/create', 'InvoicesController@create_view')->name('create_invoice_view');
            Route::get('/edit', 'InvoicesController@create_view')->name('edit_invoice_view');
            Route::post('/delete', 'InvoicesController@delete')->name('delete_invoices');
            Route::post('/create', 'InvoicesController@create')->name('create_invoice');
            Route::post('/update', 'InvoicesController@update')->name('update_invoice');

            Route::post('/check_range', 'InvoicesController@check_range')->name('search_range_ajax');
            Route::get('/invoice/{invoice}','InvoicesController@single_invoice')->name('single_invoice');

        });

        //STATUS
        Route::prefix('status')->group(function () {
            Route::get('/', 'StatusController@preview')->name('status');
            Route::get('/create', 'StatusController@create_view')->name('create_status_view');
            Route::get('/search', 'StatusController@search')->name('search_status');
            Route::get('/edit', 'StatusController@create_view')->name('edit_status_view');
            Route::post('/create', 'StatusController@create')->name('create_status');
            Route::post('/delete', 'StatusController@delete')->name('delete_status');
            Route::post('/search', 'StatusController@search_ajax')->name('search_status_ajax');
        });

        //RATE CODES
        Route::prefix('rate-code')->group(function() {
            Route::get('/', 'RateCodeController@preview')->name('rate_codes');
            Route::get('/create', 'RateCodeController@create_view')->name('create_rate_codes_view');
            Route::get('/search', 'RateCodeController@search')->name('search_rate_codes');
            Route::get('/edit', 'RateCodeController@create_view')->name('edit_rate_codes_view');
            Route::post('/create', 'RateCodeController@create')->name('create_rate_code');
            Route::post('/delete', 'RateCodeController@delete')->name('delete_rate_codes');
            Route::post('/search', 'RateCodeController@search_ajax')->name('search_rate_codes_ajax');
        });

        //INSURANCES
        Route::prefix('insurance')->group(function() {
            Route::get('/', 'InsuranceController@preview')->name('insurances');
            Route::get('/create', 'InsuranceController@create_view')->name('create_insurances_view');
            Route::get('/search', 'InsuranceController@search')->name('search_insurances');
            Route::get('/edit', 'InsuranceController@create_view')->name('edit_insurances_view');
            Route::post('/create', 'InsuranceController@create')->name('create_insurance');
            Route::post('/delete', 'InsuranceController@delete')->name('delete_insurances');
            Route::post('/search', 'InsuranceController@search_ajax')->name('search_insurances_ajax');
        });

        Route::prefix('image')->group(function() {
            Route::post('/remove-image-link', 'ImageController@removeImageLink')->name('remove_image_link');
        });

        Route::prefix('tags')->group(function() {
            Route::post('/search', 'TagController@search')->name('search_tag_ajax');
        });

        Route::prefix('transaction')->group(function() {
            Route::post('/search_transactor_ajax', 'TransactionController@search_transactor_ajax')->name('search_transactor_ajax');
            Route::post('/search_transactor_from_rental_ajax', 'TransactionController@search_transactor_from_rental_ajax')->name('search_rental_transactor_ajax');
        });

        Route::middleware('permissions')->prefix('company_preferences')->group(function() {
            Route::get('/', 'CompanyPreferencesController@create_view')->name('company_preferences');
            Route::post('/create', 'CompanyPreferencesController@create')->name('create_company_preferences');
        });

        //COLOR TYPES
        Route::middleware('permissions')->prefix('color_types')->group(function() {
            Route::get('/', 'ColorTypesController@index')->name('color_types');
            Route::get('/create', 'ColorTypesController@create')->name('create_color_type_view');
            Route::post('/create', 'ColorTypesController@store')->name('create_color_type');
            Route::get('/edit', 'ColorTypesController@create')->name('edit_color_type');
            Route::post('/delete', 'ColorTypesController@delete')->name('delete_color_type');
        });

        Route::get('/scanner', 'ScannerController@index')->name('scanner');

        //Generate QR Code for cars by id
        Route::get('/qrcode/cars', 'QRCodeController@cars')->name('qrcode_genaerate_cars');
        Route::get('/qrcode/{id}', 'QRCodeController@index')->name('qrcode_genaerate');



        Route::get('/create-payment-pdf/{model}/{id}', 'PDFController@create_payment')->name('create_payment_pdf');
        Route::post('/mail-payment-pdf/', 'PDFController@mail_payment')->name('mail_payment_pdf');
        Route::get('/create-invoice-pdf/{model}/{id}', 'PDFController@create_invoice')->name('create_invoice_pdf');
        Route::post('/mail-invoice-pdf/', 'PDFController@mail_invoice')->name('mail_invoice_pdf');
        Route::get('/create-booking-pdf/{model}/{id}', 'PDFController@create_booking')->name('create_booking_pdf');
        Route::post('/mail-booking-pdf/', 'PDFController@mail_booking')->name('mail_booking_pdf');
        Route::get('/create-quote-pdf/{model}/{id}', 'PDFController@create_quote')->name('create_quote_pdf');
        Route::post('/mail-quote-pdf/', 'PDFController@mail_quote')->name('mail_quote_pdf');
        Route::get('/create-rental-pdf/{model}/{id}', 'PDFController@create_rental')->name('create_rental_pdf');
        Route::post('/mail-rental-pdf/', 'PDFController@mail_rental')->name('mail_rental_pdf');

        Route::get('/single_printer', 'PDFController@getSinglePrinter')->name('get_single_printer');
        Route::get('/edit_modal', 'CommonController@editModal')->name('ajax_edit_modal');
        Route::get('/add_modal', 'CommonController@addModal')->name('ajax_add_modal');
        Route::get('/available-ids', 'CommonController@availableModels')->name('ajax_available_ids');
    });
});






Route::get('/live_export_pdf', 'PDFController@live_export_pdf')->name('live_export_pdf');

Route::get('/scanner-redirect', 'ScannerController@redirect')->name('scanner_redirect');

Route::post('/pagination', 'PaginationController@pages')->name('pagination');

Route::get('/', function () {
    return redirect(app()->getLocale());
})->name('homepage');
