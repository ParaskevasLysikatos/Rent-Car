import { RentalSignatureComponent } from './rental/rental-signature/rental-signature.component';

import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NavbarComponent } from './navbar/navbar.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { TableComponent } from './table/table.component';
import { StationsComponent } from './stations/preview/stations.component';
import { LocationsComponent } from './locations/preview/locations.component';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { HttpInterceptorService } from './_services/http-interceptor.service';
import { HomeComponent } from './home/home.component';
import { MaterialModule } from './material/material.module';
import { EditStationComponent } from './stations/edit-station/edit-station.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MAT_FORM_FIELD_DEFAULT_OPTIONS } from '@angular/material/form-field';
import { AdminComponent } from './admin/admin.component';
import { EditLocationComponent } from './locations/edit-location/edit-location.component';
import { StationFormComponent } from './stations/station-form/station-form.component';
import { CreateStationComponent } from './stations/create-station/create-station.component';
import { LoginComponent } from './login/login.component';
import { ToolbarComponent } from './toolbar/toolbar.component';
import { SaveButtonsComponent } from './toolbar/save-buttons/save-buttons.component';
import { SingleFormComponent } from './single-form/single-form.component';
import { LocationFormComponent } from './locations/location-form/location-form.component';
import { CreateLocationComponent } from './locations/create-location/create-location.component';
import { NgxMatSelectSearchModule } from 'ngx-mat-select-search';
import { SelectorComponent } from './_selectors/selector/selector.component';
import { PlacesPreviewComponent } from './places/places-preview/places-preview.component';
import { EditPlaceComponent } from './places/edit-place/edit-place.component';
import { CreatePlaceComponent } from './places/create-place/create-place.component';
import { PlaceFormComponent } from './places/place-form/place-form.component';
import { FlexLayoutModule } from '@angular/flex-layout';
import { CompanyPreferencesComponent } from './company_preferences/company.component';
import { EditorModule, TINYMCE_SCRIPT_SRC } from '@tinymce/tinymce-angular';
import { ConfirmationDialogComponent } from './confirmation-dialog/confirmation-dialog.component';
import { StationSelectorComponent } from './_selectors/station-selector/station-selector.component';
import { FormDialogComponent } from './form-dialog/form-dialog.component';
import { PlaceSelectorComponent } from './_selectors/place-selector/place-selector.component';
import { LocationSelectorComponent } from './_selectors/location-selector/location-selector.component';
import { CreateVehicleComponent } from 'src/app/vehicle/create-vehicle/create-vehicle.component';
import { EditVehicleComponent } from 'src/app/vehicle/edit-vehicle/edit-vehicle.component';
import { VehicleFormComponent } from 'src/app/vehicle/vehicle-form/vehicle-form.component';
import { PreviewVehicleComponent } from 'src/app/vehicle/preview-vehicle/preview-vehicle.component';
import { MultilingualTemplateComponent } from './multilingual-template/multilingual-template.component';
import { CreateRentalComponent } from 'src/app/rental/create-rental/create-rental.component';
import { EditRentalComponent } from 'src/app/rental/edit-rental/edit-rental.component';
import { RentalFormComponent } from 'src/app/rental/rental-form/rental-form.component';
import { PreviewRentalComponent } from 'src/app/rental/preview-rental/preview-rental.component';
import { MomentDateModule } from '@angular/material-moment-adapter';
import { MAT_DATE_FORMATS } from '@angular/material/core';
import { SubAccountSelectorComponent } from './_selectors/sub-account-selector/sub-account-selector.component';
import { CreateAgentComponent } from 'src/app/agent/create-agent/create-agent.component';
import { EditAgentComponent } from 'src/app/agent/edit-agent/edit-agent.component';
import { AgentFormComponent } from 'src/app/agent/agent-form/agent-form.component';
import { PreviewAgentComponent } from 'src/app/agent/preview-agent/preview-agent.component';
import { CreateContactComponent } from 'src/app/contact/create-contact/create-contact.component';
import { EditContactComponent } from 'src/app/contact/edit-contact/edit-contact.component';
import { ContactFormComponent } from 'src/app/contact/contact-form/contact-form.component';
import { PreviewContactComponent } from 'src/app/contact/preview-contact/preview-contact.component';
import { CreateDriverComponent } from 'src/app/driver/create-driver/create-driver.component';
import { EditDriverComponent } from 'src/app/driver/edit-driver/edit-driver.component';
import { DriverFormComponent } from 'src/app/driver/driver-form/driver-form.component';
import { PreviewDriverComponent } from 'src/app/driver/preview-driver/preview-driver.component';
import { AgentSelectorComponent } from './_selectors/agent-selector/agent-selector.component';
import { DriverSelectorComponent } from 'src/app/_selectors/driver-selector/driver-selector.component';
import { ContactSelectorComponent } from 'src/app/_selectors/contact-selector/contact-selector.component';
import { CreateCompanyComponent } from 'src/app/company/create-company/create-company.component';
import { EditCompanyComponent } from 'src/app/company/edit-company/edit-company.component';
import { CompanyFormComponent } from 'src/app/company/company-form/company-form.component';
import { PreviewCompanyComponent } from 'src/app/company/preview-company/preview-company.component';
import { SourceSelectorComponent } from 'src/app/_selectors/source-selector/source-selector.component';
import { CreateBrandComponent } from 'src/app/brand/create-brand/create-brand.component';
import { EditBrandComponent } from 'src/app/brand/edit-brand/edit-brand.component';
import { BrandFormComponent } from 'src/app/brand/brand-form/brand-form.component';
import { PreviewBrandComponent } from 'src/app/brand/preview-brand/preview-brand.component';
import { BrandSelectorComponent } from 'src/app/_selectors/brand-selector/brand-selector.component';
import { CompanySelectorComponent } from 'src/app/_selectors/company-selector/company-selector.component';
import { CreateBookingItemComponent } from 'src/app/booking-item/create-booking-item/create-booking-item.component';
import { EditBookingItemComponent } from 'src/app/booking-item/edit-booking-item/edit-booking-item.component';
import { BookingItemFormComponent } from 'src/app/booking-item/booking-item-form/booking-item-form.component';
import { PreviewBookingItemComponent } from 'src/app/booking-item/preview-booking-item/preview-booking-item.component';
import { DatetimepickerComponent } from './datetimepicker/datetimepicker.component';
import { DecimalPipe } from '@angular/common';
import { DisabledcontrolDirective } from './_directives/disabledcontrol.directive';
import { CreatePaymentComponent } from 'src/app/payment/create-payment/create-payment.component';
import { EditPaymentComponent } from 'src/app/payment/edit-payment/edit-payment.component';
import { PaymentFormComponent } from 'src/app/payment/payment-form/payment-form.component';
import { PreviewPaymentComponent } from 'src/app/payment/preview-payment/preview-payment.component';
import { CreateUserComponent } from 'src/app/user/create-user/create-user.component';
import { EditUserComponent } from 'src/app/user/edit-user/edit-user.component';
import { UserFormComponent } from 'src/app/user/user-form/user-form.component';
import { PreviewUserComponent } from 'src/app/user/preview-user/preview-user.component';
import { UserSelectorComponent } from 'src/app/_selectors/user-selector/user-selector.component';
import { FloatInputComponent } from './_inputs/float-input/float-input.component';
import { VehicleSelectorComponent } from 'src/app/_selectors/vehicle-selector/vehicle-selector.component';
import { GroupSelectorComponent } from 'src/app/_selectors/group-selector/group-selector.component';
import { TagSelectorComponent } from './_selectors/tag-selector/tag-selector.component';
import { AutocompleteSelectorComponent } from './_selectors/autocomplete-selector/autocomplete-selector.component';
import { CreateLicencePlateComponent } from 'src/app/licence-plate/create-licence-plate/create-licence-plate.component';
import { EditLicencePlateComponent } from 'src/app/licence-plate/edit-licence-plate/edit-licence-plate.component';
import { LicencePlateFormComponent } from 'src/app/licence-plate/licence-plate-form/licence-plate-form.component';
import { PreviewLicencePlateComponent } from 'src/app/licence-plate/preview-licence-plate/preview-licence-plate.component';
import { CreateTypesComponent } from 'src/app/types/create-types/create-types.component';
import { EditTypesComponent } from 'src/app/types/edit-types/edit-types.component';
import { TypesFormComponent } from 'src/app/types/types-form/types-form.component';
import { PreviewTypesComponent } from 'src/app/types/preview-types/preview-types.component';
import { CreateCharacteristicsComponent } from 'src/app/characteristics/create-characteristics/create-characteristics.component';
import { EditCharacteristicsComponent } from 'src/app/characteristics/edit-characteristics/edit-characteristics.component';
import { CharacteristicsFormComponent } from 'src/app/characteristics/characteristics-form/characteristics-form.component';
import { PreviewCharacteristicsComponent } from 'src/app/characteristics/preview-characteristics/preview-characteristics.component';
import { CreateCategoriesComponent } from 'src/app/categories/create-categories/create-categories.component';
import { EditCategoriesComponent } from 'src/app/categories/edit-categories/edit-categories.component';
import { CategoriesFormComponent } from 'src/app/categories/categories-form/categories-form.component';
import { PreviewCategoriesComponent } from 'src/app/categories/preview-categories/preview-categories.component';
import { CreateOptionsComponent } from 'src/app/options/create-options/create-options.component';
import { EditOptionsComponent } from 'src/app/options/edit-options/edit-options.component';
import { OptionsFormComponent } from 'src/app/options/options-form/options-form.component';
import { PreviewOptionsComponent } from 'src/app/options/preview-options/preview-options.component';
import { CreateVisitComponent } from 'src/app/visit/create-visit/create-visit.component';
import { EditVisitComponent } from 'src/app/visit/edit-visit/edit-visit.component';
import { VisitFormComponent } from 'src/app/visit/visit-form/visit-form.component';
import { PreviewVisitComponent } from 'src/app/visit/preview-visit/preview-visit.component';
import { CreateInvoicesComponent } from 'src/app/invoices/create-invoices/create-invoices.component';
import { EditInvoicesComponent } from 'src/app/invoices/edit-invoices/edit-invoices.component';
import { InvoicesFormComponent } from 'src/app/invoices/invoices-form/invoices-form.component';
import { PreviewInvoicesComponent } from 'src/app/invoices/preview-invoices/preview-invoices.component';
import { PaymentSelectorComponent } from 'src/app/_selectors/payment-selector/payment-selector.component';
import { CalulateFpaComponent } from './calculate-fpa/calculate-fpa.component';
import { CreateLanguagesComponent } from 'src/app/languages/create-languages/create-languages.component';
import { EditLanguagesComponent } from 'src/app/languages/edit-languages/edit-languages.component';
import { LanguagesFormComponent } from 'src/app/languages/languages-form/languages-form.component';
import { PreviewLanguagesComponent } from 'src/app/languages/preview-languages/preview-languages.component';
import { CreateBookingSourceComponent } from 'src/app/booking-source/create-booking-source/create-booking-source.component';
import { EditBookingSourceComponent } from 'src/app/booking-source/edit-booking-source/edit-booking-source.component';
import { BookingSourceFormComponent } from 'src/app/booking-source/booking-source-form/booking-source-form.component';
import { PreviewBookingSourceComponent } from 'src/app/booking-source/preview-booking-source/preview-booking-source.component';
import { CreateRateCodeComponent } from 'src/app/rate-code/create-rate-code/create-rate-code.component';
import { EditRateCodeComponent } from 'src/app/rate-code/edit-rate-code/edit-rate-code.component';
import { RateCodeFormComponent } from 'src/app/rate-code/rate-code-form/rate-code-form.component';
import { PreviewRateCodeComponent } from 'src/app/rate-code/preview-rate-code/preview-rate-code.component';
import { CreateRolesComponent } from 'src/app/roles/create-roles/create-roles.component';
import { EditRolesComponent } from 'src/app/roles/edit-roles/edit-roles.component';
import { RolesFormComponent } from 'src/app/roles/roles-form/roles-form.component';
import { PreviewRolesComponent } from 'src/app/roles/preview-roles/preview-roles.component';
import { NotificationComponent } from './notification/notification.component';
import { CreateDocumentTypeComponent } from 'src/app/document-type/create-document-type/create-document-type.component';
import { EditDocumentTypeComponent } from 'src/app/document-type/edit-document-type/edit-document-type.component';
import { DocumentTypeFormComponent } from 'src/app/document-type/document-type-form/document-type-form.component';
import { PreviewDocumentTypeComponent } from 'src/app/document-type/preview-document-type/preview-document-type.component';
import { CreateDocumentsComponent } from 'src/app/documents/create-documents/create-documents.component';
import { EditDocumentsComponent } from 'src/app/documents/edit-documents/edit-documents.component';
import { DocumentsFormComponent } from 'src/app/documents/documents-form/documents-form.component';
import { PreviewDocumentsComponent } from 'src/app/documents/preview-documents/preview-documents.component';
import { RentalSelectorComponent } from 'src/app/_selectors/rental-selector/rental-selector.component';
import { CustomerSelectorComponent } from 'src/app/_selectors/customer-selector/customer-selector.component';
import { CreateVehicleStatusComponent } from 'src/app/vehicle-status/create-vehicle-status/create-vehicle-status.component';
import { EditVehicleStatusComponent } from 'src/app/vehicle-status/edit-vehicle-status/edit-vehicle-status.component';
import { VehicleStatusFormComponent } from 'src/app/vehicle-status/vehicle-status-form/vehicle-status-form.component';
import { PreviewVehicleStatusComponent } from 'src/app/vehicle-status/preview-vehicle-status/preview-vehicle-status.component';
import { CreateColorTypeComponent } from 'src/app/color-type/create-color-type/create-color-type.component';
import { EditColorTypeComponent } from 'src/app/color-type/edit-color-type/edit-color-type.component';
import { ColorTypeFormComponent } from 'src/app/color-type/color-type-form/color-type-form.component';
import { PreviewColorTypeComponent } from 'src/app/color-type/preview-color-type/preview-color-type.component';

import { TimepickerComponent } from './timepicker/timepicker.component';
import { VexModule } from 'src/@vex/vex.module';
import { AdminLayoutComponent } from './admin-layout/admin-layout.component';
import { LayoutModule } from 'src/@vex/layout/layout.module';
import { SidenavModule } from 'src/@vex/layout/sidenav/sidenav.module';
import { ToolbarModule } from 'src/@vex/layout/toolbar/toolbar.module';
import { FooterModule } from 'src/@vex/layout/footer/footer.module';
import { ConfigPanelModule } from 'src/@vex/components/config-panel/config-panel.module';
import { SidebarModule } from 'src/@vex/components/sidebar/sidebar.module';
import { QuickpanelModule } from 'src/@vex/layout/quickpanel/quickpanel.module';
import { IconModule } from '@visurel/iconify-angular';
import { HeaderComponent } from './header/header.component';
import { NavigationItemModule } from 'src/@vex/components/navigation-item/navigation-item.module';
import { PageLayoutComponent } from './page-layout/page-layout.component';
import { ProgressBarModule } from 'src/@vex/components/progress-bar/progress-bar.module';
import { BreadcrumbsModule } from 'src/@vex/components/breadcrumbs/breadcrumbs.module';
import { PageLayoutModule } from 'src/@vex/components/page-layout/page-layout.module';
import { BreadcrumbsComponent } from './breadcrumbs/breadcrumbs.component';
import { ScrollbarModule } from 'src/@vex/components/scrollbar/scrollbar.module';
import { SidenavItemModule } from 'src/@vex/layout/sidenav/sidenav-item/sidenav-item.module';
import { CreateTransitionComponent } from 'src/app/transition/create-transition/create-transition.component';
import { EditTransitionComponent } from 'src/app/transition/edit-transition/edit-transition.component';
import { TransitionFormComponent } from 'src/app/transition/transition-form/transition-form.component';
import { PreviewTransitionComponent } from 'src/app/transition/preview-transition/preview-transition.component';
import { CreateBookingComponent } from 'src/app/booking/create-booking/create-booking.component';
import { EditBookingComponent } from 'src/app/booking/edit-booking/edit-booking.component';
import { BookingFormComponent } from 'src/app/booking/booking-form/booking-form.component';
import { PreviewBookingComponent } from 'src/app/booking/preview-booking/preview-booking.component';
import { CreateVehicleExchangesComponent } from 'src/app/vehicle-exchanges/create-vehicle-exchanges/create-vehicle-exchanges.component';
import { EditVehicleExchangesComponent } from 'src/app/vehicle-exchanges/edit-vehicle-exchanges/edit-vehicle-exchanges.component';
import { VehicleExchangesFormComponent } from 'src/app/vehicle-exchanges/vehicle-exchanges-form/vehicle-exchanges-form.component';
import { PreviewVehicleExchangesComponent } from 'src/app/vehicle-exchanges/preview-vehicle-exchanges/preview-vehicle-exchanges.component';
import { DocumentUploadComponent } from './document-upload/document-upload.component';
import { ImageUploadComponent } from './image-upload/image-upload.component';
import { CreatePeriodicFeeComponent } from 'src/app/periodic-fee/create-periodic-fee/create-periodic-fee.component';
import { EditPeriodicFeeComponent } from 'src/app/periodic-fee/edit-periodic-fee/edit-periodic-fee.component';
import { PeriodicFeeFormComponent } from 'src/app/periodic-fee/periodic-fee-form/periodic-fee-form.component';
import { PreviewPeriodicFeeComponent } from 'src/app/periodic-fee/preview-periodic-fee/preview-periodic-fee.component';
import { PrintPaymentComponent } from './payment/print-payment/print-payment.component';
import { NgxExtendedPdfViewerModule } from 'ngx-extended-pdf-viewer';
import { PrintCheckboxComponent } from './print-checkbox/print-checkbox.component';
import { InvoicePrintComponent } from './invoices/invoice-print/invoice-print.component';
import { SummaryChargesComponent } from './summary-charges/summary-charges.component';
import { PrintRentalComponent } from './rental/print-rental/print-rental.component';
import { MatTooltipModule } from '@angular/material/tooltip';
import { MatTabsModule } from '@angular/material/tabs';
import { PositiveNumbersDirective } from './_directives/positiveNumbers.directive';
import { PrintBookingComponent } from './booking/print-booking/print-booking.component';
import { CancelReasonComponent } from './booking/cancel-reason/cancel-reason.component';
import { CreateRentalModalComponent } from './booking/create-rental-modal/create-rental-modal.component';
import { CreateQuotesComponent } from 'src/app/quotes/create-quotes/create-quotes.component';
import { EditQuotesComponent } from 'src/app/quotes/edit-quotes/edit-quotes.component';
import { QuotesFormComponent } from 'src/app/quotes/quotes-form/quotes-form.component';
import { PreviewQuotesComponent } from 'src/app/quotes/preview-quotes/preview-quotes.component';
import { PrintQuoteComponent } from './quotes/print-quote/print-quote.component';
import { CreateBookingModalComponent } from './quotes/create-booking-modal/create-booking-modal.component';
import { DrCustSelectorComponent } from './_selectors/dr-cust-selector/dr-cust-selector.component';
import { MyLoadingComponent } from './my-loading/my-loading.component';
import { MyLoadingService } from './my-loading/my-loading.service';
import { ToUpperCaseDirective } from './_directives/uppercase.directive';
import { LanguageDirective } from './_directives/languageDetect.directive';
import { FuelRangeDirective } from './_directives/fuelRange.directive';
import { BookingSelectorComponent } from './_selectors/booking-selector/booking-selector.component';
import { DriverEmpSelectorComponent } from './_selectors/driverEmp-selector/driverEmp-selector.component';
import { PaymentTranslatePipe } from './_directives/paymentTranslate.pipe';
import { SafePipe } from './_directives/safeUrl.pipe';
import { SignaturePadModule } from 'angular2-signaturepad';


@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    SidebarComponent,
    TableComponent,
    StationsComponent,
    LocationsComponent,
    HomeComponent,
    EditStationComponent,
    AdminComponent,
    EditLocationComponent,
    StationFormComponent,
    CreateStationComponent,
    LoginComponent,
    ToolbarComponent,
    SaveButtonsComponent,
    SingleFormComponent,
    LocationFormComponent,
    CreateLocationComponent,
    SelectorComponent,
    PlacesPreviewComponent,
    EditPlaceComponent,
    CreatePlaceComponent,
    PlaceFormComponent,
    CompanyPreferencesComponent,
    ConfirmationDialogComponent,
    StationSelectorComponent,
    FormDialogComponent,
    PlaceSelectorComponent,
    LocationSelectorComponent,
    CreateVehicleComponent,
    EditVehicleComponent,
    VehicleFormComponent,
    PreviewVehicleComponent,
    MultilingualTemplateComponent,
    CreateRentalComponent,
    EditRentalComponent,
    RentalFormComponent,
    PreviewRentalComponent,
    SubAccountSelectorComponent,
    CreateAgentComponent,
    EditAgentComponent,
    AgentFormComponent,
    PreviewAgentComponent,
    CreateContactComponent,
    EditContactComponent,
    ContactFormComponent,
    PreviewContactComponent,
    CreateDriverComponent,
    EditDriverComponent,
    DriverFormComponent,
    PreviewDriverComponent,
    AgentSelectorComponent,
    DriverSelectorComponent,
    DriverEmpSelectorComponent,
    ContactSelectorComponent,
    CreateCompanyComponent,
    EditCompanyComponent,
    CompanyFormComponent,
    PreviewCompanyComponent,
    SourceSelectorComponent,
    CreateBrandComponent,
    EditBrandComponent,
    BrandFormComponent,
    PreviewBrandComponent,
    BrandSelectorComponent,
    CompanySelectorComponent,
    CreateBookingItemComponent,
    EditBookingItemComponent,
    BookingItemFormComponent,
    PreviewBookingItemComponent,
    DatetimepickerComponent,
    DisabledcontrolDirective,
    CreatePaymentComponent,
    EditPaymentComponent,
    PaymentFormComponent,
    PreviewPaymentComponent,
    CreateUserComponent,
    EditUserComponent,
    UserFormComponent,
    PreviewUserComponent,
    UserSelectorComponent,
    FloatInputComponent,
    VehicleSelectorComponent,
    GroupSelectorComponent,
    TagSelectorComponent,
    AutocompleteSelectorComponent,
    CreateLicencePlateComponent,
    EditLicencePlateComponent,
    LicencePlateFormComponent,
    PreviewLicencePlateComponent,
    CreateTypesComponent,
    EditTypesComponent,
    TypesFormComponent,
    PreviewTypesComponent,
    CreateCharacteristicsComponent,
    EditCharacteristicsComponent,
    CharacteristicsFormComponent,
    PreviewCharacteristicsComponent,
    CreateCategoriesComponent,
    EditCategoriesComponent,
    CategoriesFormComponent,
    PreviewCategoriesComponent,
    CreateOptionsComponent,
    EditOptionsComponent,
    OptionsFormComponent,
    PreviewOptionsComponent,
    CreateVisitComponent,
    EditVisitComponent,
    VisitFormComponent,
    PreviewVisitComponent,
    CreateInvoicesComponent,
    EditInvoicesComponent,
    InvoicesFormComponent,
    PreviewInvoicesComponent,
    PaymentSelectorComponent,
    CalulateFpaComponent,
    CreateLanguagesComponent,
    EditLanguagesComponent,
    LanguagesFormComponent,
    PreviewLanguagesComponent,
    CreateBookingSourceComponent,
    EditBookingSourceComponent,
    BookingSourceFormComponent,
    PreviewBookingSourceComponent,
    CreateRateCodeComponent,
    EditRateCodeComponent,
    RateCodeFormComponent,
    PreviewRateCodeComponent,
    CreateRolesComponent,
    EditRolesComponent,
    RolesFormComponent,
    PreviewRolesComponent,
    NotificationComponent,
    CreateDocumentTypeComponent,
    EditDocumentTypeComponent,
    DocumentTypeFormComponent,
    PreviewDocumentTypeComponent,
    CreateDocumentsComponent,
    EditDocumentsComponent,
    DocumentsFormComponent,
    PreviewDocumentsComponent,
    RentalSelectorComponent,
    CustomerSelectorComponent,
    CreateVehicleStatusComponent,
    EditVehicleStatusComponent,
    VehicleStatusFormComponent,
    PreviewVehicleStatusComponent,
    TimepickerComponent,
    CreateColorTypeComponent,
    EditColorTypeComponent,
    ColorTypeFormComponent,
    PreviewColorTypeComponent,
    AdminLayoutComponent,
    HeaderComponent,
    PageLayoutComponent,
    BreadcrumbsComponent,
    CreateTransitionComponent,
    EditTransitionComponent,
    TransitionFormComponent,
    PreviewTransitionComponent,
    CreateBookingComponent,
    EditBookingComponent,
    BookingFormComponent,
    PreviewBookingComponent,
    CreateVehicleExchangesComponent,
    EditVehicleExchangesComponent,
    VehicleExchangesFormComponent,
    PreviewVehicleExchangesComponent,
    DocumentUploadComponent,
    ImageUploadComponent,
    CreatePeriodicFeeComponent,
    EditPeriodicFeeComponent,
    PeriodicFeeFormComponent,
    PreviewPeriodicFeeComponent,
    PrintPaymentComponent,
    PrintCheckboxComponent,
    InvoicePrintComponent,
    SummaryChargesComponent,
    PrintRentalComponent,
    PositiveNumbersDirective,
    ToUpperCaseDirective,
    PrintBookingComponent,
    CancelReasonComponent,
    CreateRentalModalComponent,
    CreateQuotesComponent,
    EditQuotesComponent,
    QuotesFormComponent,
    PreviewQuotesComponent,
    PrintQuoteComponent,
    CreateBookingModalComponent,
    DrCustSelectorComponent,
    MyLoadingComponent,
    LanguageDirective,
    FuelRangeDirective,
    BookingSelectorComponent,
    PaymentTranslatePipe,
    SafePipe,
    RentalSignatureComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    HttpClientModule,
    MaterialModule,
    FormsModule,
    ReactiveFormsModule,
    NgxMatSelectSearchModule,
    FlexLayoutModule,
    EditorModule,
    MomentDateModule,
    VexModule,
    LayoutModule,
    SidenavModule,
    ToolbarModule,
    FooterModule,
    ConfigPanelModule,
    SidebarModule,
    QuickpanelModule,
    IconModule,
    NavigationItemModule,
    ProgressBarModule,
    BreadcrumbsModule,
    PageLayoutModule,
    ScrollbarModule,
    SidenavItemModule,
    NgxExtendedPdfViewerModule,
    MatTooltipModule,
    MatTabsModule,
    SignaturePadModule 
  ],
  providers: [
    DecimalPipe,
    {
      provide: HTTP_INTERCEPTORS,
      useClass: HttpInterceptorService,
      multi: true
    },
    {
      provide: MAT_FORM_FIELD_DEFAULT_OPTIONS,
      useValue: {
        floatLabel: 'auto',
        appearance: 'fill'
      }
    },
    {
      provide: MAT_DATE_FORMATS,
      useValue: {
        parse: {
          dateInput: ['DDMMYYYY'],
        },
        display: {
          dateInput: 'DD/MM/YYYY',
          monthYearLabel: 'DD/MM/YYYY',
          dateA11yLabel: 'DD/MM/YYYY',
          monthYearA11yLabel: 'DD/MM/YYYY',
        },
      }
    },
    {
      provide: TINYMCE_SCRIPT_SRC,
      useValue: 'https://cdn.tiny.cloud/1/7ugqpuisu6b9vjlyt3f6s7nollxyniqh09nnlk5uahil19lh/tinymce/5/tinymce.min.js'
    },
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
