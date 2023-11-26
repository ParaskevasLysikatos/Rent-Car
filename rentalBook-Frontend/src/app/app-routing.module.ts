import { NgModule } from '@angular/core';
import { Routes, RouterModule, RouteReuseStrategy, BaseRouteReuseStrategy } from '@angular/router';
import { AdminLayoutComponent } from './admin-layout/admin-layout.component';
import { AdminComponent } from './admin/admin.component';
import { CreateAgentComponent } from './agent/create-agent/create-agent.component';
import { EditAgentComponent } from './agent/edit-agent/edit-agent.component';
import { PreviewAgentComponent } from './agent/preview-agent/preview-agent.component';
import { CreateBookingSourceComponent } from './booking-source/create-booking-source/create-booking-source.component';
import { EditBookingSourceComponent } from './booking-source/edit-booking-source/edit-booking-source.component';
import { PreviewBookingSourceComponent } from './booking-source/preview-booking-source/preview-booking-source.component';
import { CreateBookingComponent } from './booking/create-booking/create-booking.component';
import { EditBookingComponent } from './booking/edit-booking/edit-booking.component';
import { PreviewBookingComponent } from './booking/preview-booking/preview-booking.component';
import { CreateBrandComponent } from './brand/create-brand/create-brand.component';
import { EditBrandComponent } from './brand/edit-brand/edit-brand.component';
import { PreviewBrandComponent } from './brand/preview-brand/preview-brand.component';
import { CalulateFpaComponent } from './calculate-fpa/calculate-fpa.component';
import { CreateCategoriesComponent } from './categories/create-categories/create-categories.component';
import { EditCategoriesComponent } from './categories/edit-categories/edit-categories.component';
import { PreviewCategoriesComponent } from './categories/preview-categories/preview-categories.component';
import { CreateCharacteristicsComponent } from './characteristics/create-characteristics/create-characteristics.component';
import { EditCharacteristicsComponent } from './characteristics/edit-characteristics/edit-characteristics.component';
import { PreviewCharacteristicsComponent } from './characteristics/preview-characteristics/preview-characteristics.component';
import { CreateColorTypeComponent } from './color-type/create-color-type/create-color-type.component';
import { EditColorTypeComponent } from './color-type/edit-color-type/edit-color-type.component';
import { PreviewColorTypeComponent } from './color-type/preview-color-type/preview-color-type.component';
import { CreateCompanyComponent } from './company/create-company/create-company.component';
import { EditCompanyComponent } from './company/edit-company/edit-company.component';
import { PreviewCompanyComponent } from './company/preview-company/preview-company.component';
import { CompanyPreferencesComponent } from './company_preferences/company.component';
import { CreateContactComponent } from './contact/create-contact/create-contact.component';
import { EditContactComponent } from './contact/edit-contact/edit-contact.component';
import { PreviewContactComponent } from './contact/preview-contact/preview-contact.component';
import { CreateDocumentTypeComponent } from './document-type/create-document-type/create-document-type.component';
import { EditDocumentTypeComponent } from './document-type/edit-document-type/edit-document-type.component';
import { PreviewDocumentTypeComponent } from './document-type/preview-document-type/preview-document-type.component';
import { CreateDocumentsComponent } from './documents/create-documents/create-documents.component';
import { EditDocumentsComponent } from './documents/edit-documents/edit-documents.component';
import { PreviewDocumentsComponent } from './documents/preview-documents/preview-documents.component';
import { CreateDriverComponent } from './driver/create-driver/create-driver.component';
import { EditDriverComponent } from './driver/edit-driver/edit-driver.component';
import { PreviewDriverComponent } from './driver/preview-driver/preview-driver.component';
import { HomeComponent } from './home/home.component';
import { CreateInvoicesComponent } from './invoices/create-invoices/create-invoices.component';
import { EditInvoicesComponent } from './invoices/edit-invoices/edit-invoices.component';
import { PreviewInvoicesComponent } from './invoices/preview-invoices/preview-invoices.component';
import { CreateLanguagesComponent } from './languages/create-languages/create-languages.component';
import { EditLanguagesComponent } from './languages/edit-languages/edit-languages.component';
import { PreviewLanguagesComponent } from './languages/preview-languages/preview-languages.component';
import { CreateLocationComponent } from './locations/create-location/create-location.component';
import { EditLocationComponent } from './locations/edit-location/edit-location.component';
import { LocationsComponent } from './locations/preview/locations.component';
import { LoginComponent } from './login/login.component';
import { CreateOptionsComponent } from './options/create-options/create-options.component';
import { EditOptionsComponent } from './options/edit-options/edit-options.component';
import { PreviewOptionsComponent } from './options/preview-options/preview-options.component';
import { CreatePaymentComponent } from './payment/create-payment/create-payment.component';
import { EditPaymentComponent } from './payment/edit-payment/edit-payment.component';
import { PreviewPaymentComponent } from './payment/preview-payment/preview-payment.component';
import { CreatePlaceComponent } from './places/create-place/create-place.component';
import { EditPlaceComponent } from './places/edit-place/edit-place.component';
import { PlacesPreviewComponent } from './places/places-preview/places-preview.component';
import { CreateQuotesComponent } from './quotes/create-quotes/create-quotes.component';
import { EditQuotesComponent } from './quotes/edit-quotes/edit-quotes.component';
import { PreviewQuotesComponent } from './quotes/preview-quotes/preview-quotes.component';
import { CreateRateCodeComponent } from './rate-code/create-rate-code/create-rate-code.component';
import { EditRateCodeComponent } from './rate-code/edit-rate-code/edit-rate-code.component';
import { PreviewRateCodeComponent } from './rate-code/preview-rate-code/preview-rate-code.component';
import { CreateRentalComponent } from './rental/create-rental/create-rental.component';
import { EditRentalComponent } from './rental/edit-rental/edit-rental.component';
import { PreviewRentalComponent } from './rental/preview-rental/preview-rental.component';
import { CreateRolesComponent } from './roles/create-roles/create-roles.component';
import { EditRolesComponent } from './roles/edit-roles/edit-roles.component';
import { PreviewRolesComponent } from './roles/preview-roles/preview-roles.component';
import { CreateStationComponent } from './stations/create-station/create-station.component';
import { EditStationComponent } from './stations/edit-station/edit-station.component';
import { StationsComponent } from './stations/preview/stations.component';
import { CreateTransitionComponent } from './transition/create-transition/create-transition.component';
import { EditTransitionComponent } from './transition/edit-transition/edit-transition.component';
import { PreviewTransitionComponent } from './transition/preview-transition/preview-transition.component';
import { CreateTypesComponent } from './types/create-types/create-types.component';
import { EditTypesComponent } from './types/edit-types/edit-types.component';
import { PreviewTypesComponent } from './types/preview-types/preview-types.component';
import { CreateUserComponent } from './user/create-user/create-user.component';
import { EditUserComponent } from './user/edit-user/edit-user.component';
import { PreviewUserComponent } from './user/preview-user/preview-user.component';
import { CreateVehicleExchangesComponent } from './vehicle-exchanges/create-vehicle-exchanges/create-vehicle-exchanges.component';
import { EditVehicleExchangesComponent } from './vehicle-exchanges/edit-vehicle-exchanges/edit-vehicle-exchanges.component';
import { PreviewVehicleExchangesComponent } from './vehicle-exchanges/preview-vehicle-exchanges/preview-vehicle-exchanges.component';
import { CreateVehicleStatusComponent } from './vehicle-status/create-vehicle-status/create-vehicle-status.component';
import { EditVehicleStatusComponent } from './vehicle-status/edit-vehicle-status/edit-vehicle-status.component';
import { PreviewVehicleStatusComponent } from './vehicle-status/preview-vehicle-status/preview-vehicle-status.component';
import { CreateVehicleComponent } from './vehicle/create-vehicle/create-vehicle.component';
import { EditVehicleComponent } from './vehicle/edit-vehicle/edit-vehicle.component';
import { PreviewVehicleComponent } from './vehicle/preview-vehicle/preview-vehicle.component';
import { CreateVisitComponent } from './visit/create-visit/create-visit.component';
import { EditVisitComponent } from './visit/edit-visit/edit-visit.component';
import { PreviewVisitComponent } from './visit/preview-visit/preview-visit.component';



import { AuthGuard } from './_helpers/auth.guard';
import { CandeactivateGuard } from './_helpers/candeactivate.guard';
import { PreviewResolver } from './_services/preview.resolver';

const routes: Routes = [
  {
    path: 'login',
    component: LoginComponent
  },
  {
    path: '',
    redirectTo: '/home',
    pathMatch: 'full'
  },
  {
    path: '',
    component: AdminLayoutComponent,
    canActivate: [AuthGuard],
    children: [
      {
        path: 'company',
        data: { title: 'Η Εταιρεία μου' },
        component: CompanyPreferencesComponent,
        canDeactivate: [CandeactivateGuard]
      },
      {
        path: 'vehicles',
        data: { title: 'Λίστα Οχημάτων' },
        children: [
          {
            path: '',
            component: PreviewVehicleComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            component: CreateVehicleComponent,
            data: { title: 'Δημιουργία Οχήματος' },
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            component: EditVehicleComponent,
            data: { title: 'Επεξεργασία Οχήματος' },
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'contacts',
        data: { title: 'Επαφές' },
        children: [
          {
            path: '',
            component: PreviewContactComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Επαφής' },
            component: CreateContactComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Επαφής' },
            component: EditContactComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'drivers',
        data: { title: 'Οδηγοί' },
        children: [
          {
            path: '',
            component: PreviewDriverComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Οδηγού' },
            component: CreateDriverComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Δημιουργία Οδηγού' },
            component: EditDriverComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'companies',
        data: { title: 'Εταιρείες' },
        children: [
          {
            path: '',
            component: PreviewCompanyComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Εταιρείας' },
            component: CreateCompanyComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Εταιρείας' },
            component: EditCompanyComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'types',
        data: { title: 'Groups' },
        children: [
          {
            path: '',
            component: PreviewTypesComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            data: { title: 'Δημιουργία Group' },
            path: 'create',
            component: CreateTypesComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            data: { title: 'Επεξεργασία Group' },
            path: ':id',
            component: EditTypesComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'characteristics',
        data: { title: 'Χαρακτηριστικά Αυτοκινήτου' },
        children: [
          {
            path: '',
            component: PreviewCharacteristicsComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            data: { title: 'Δημιουργία Χαρακτηριστικού Αυτοκινήτου' },
            path: 'create',
            component: CreateCharacteristicsComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            data: { title: 'Επεξεργασία Χαρακτηριστικού Αυτοκινήτου' },
            path: ':id',
            component: EditCharacteristicsComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'options/:type',
         // data: { title: 'Δημιουργία Group' },
        children: [
          {
            path: '',
            component: PreviewOptionsComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            component: CreateOptionsComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            component: EditOptionsComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'categories',
        data: { title: 'Κατηγορίες' },
        children: [
          {
            path: '',
            component: PreviewCategoriesComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Κατηγορίας' },
            component: CreateCategoriesComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Κατηγορίας' },
            component: EditCategoriesComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'transition',
        data: { title: 'Μετακινήσεις' },
        children: [
          {
            path: '',
            component: PreviewTransitionComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            data: { title: 'Δημιουργία Μετακίνησης' },
            path: 'create',
            component: CreateTransitionComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            data: { title: 'Επεξεργασία Μετακίνησης' },
            path: ':id',
            component: EditTransitionComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
       {
        path: 'vehicle-exchanges',
         data: { title: 'Αντικαταστάσεις Οχημάτων' },
        children: [
          {
            path: '',
            component: PreviewVehicleExchangesComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            data: { title: 'Δημιουργία Αντικαταστάσης Οχημάτος' },
            path: 'create',
            component: CreateVehicleExchangesComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            data: { title: 'Επεξεργασία Αντικαταστάσης Οχημάτος' },
            path: ':id',
            component: EditVehicleExchangesComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'document-types',
        data: { title: 'Document Types' },
        children: [
          {
            path: '',
            component: PreviewDocumentTypeComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            data: { title: 'Δημιουργία Document Type' },
            path: 'create',
            component: CreateDocumentTypeComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            data: { title: 'Επεξεργασία Document Type' },
            path: ':id',
            component: EditDocumentTypeComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'documents',
        data: { title: 'Documents' },
        children: [
          {
            path: '',
            component: PreviewDocumentsComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            data: { title: 'Δημιουργία Document' },
            path: 'create',
            component: CreateDocumentsComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            data: { title: 'Επεξεργασία Document' },
            path: ':id',
            component: EditDocumentsComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'status',
        data: { title: 'Καταστάσεις Οχήματος' },
        children: [
          {
            path: '',
            component: PreviewVehicleStatusComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Κατάστασης Οχήματος' },
            component: CreateVehicleStatusComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Κατάστασης Οχήματος' },
            component: EditVehicleStatusComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'brands',
        data: { title: 'Brands' },
        children: [
          {
            path: '',
            component: PreviewBrandComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            data: { title: 'Δημιουργία Brand' },
            path: 'create',
            component: CreateBrandComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            data: { title: 'Επεξεργασία Brand' },
            path: ':id',
            component: EditBrandComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'color_types',
        data: { title: 'Χρώματα' },
        children: [
          {
            path: '',
            component: PreviewColorTypeComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Χρώματος' },
            component: CreateColorTypeComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Χρώματος' },
            component: EditColorTypeComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'visit',
        data: { title: 'Επισκέψεις' },
        children: [
          {
            path: '',
            component: PreviewVisitComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Επισκέψης' },
            component: CreateVisitComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Επισκέψης' },
            component: EditVisitComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'quotes',
        data: { title: 'Προσφορές' },
        children: [
          {
            path: '',
            component: PreviewQuotesComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Προσφοράς' },
            component: CreateQuotesComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Προσφοράς' },
            component: EditQuotesComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'bookings',
        data: { title: 'Κρατήσεις' },
        children: [
          {
            path: '',
            component: PreviewBookingComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Κρατήσης' },
            component: CreateBookingComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Κρατήσης' },
            component: EditBookingComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'rentals',
        data: { title: 'Μισθώσεις' },
        children: [
          {
            path: '',
            component: PreviewRentalComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Μίσθωσης' },
            component: CreateRentalComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            data: { title: 'Επεξεργασία Μίσθωσης' },
            path: ':id',
            component: EditRentalComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'payments/:type',
        //data: { title: 'Μισθώσεις' },
        children: [
          {
            path: '',
            component: PreviewPaymentComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            component: CreatePaymentComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            component: EditPaymentComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'invoices',
        data: { title: 'Παραστατικά' },
        children: [
          {
            path: '',
            component: PreviewInvoicesComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Παραστατικού' },
            component: CreateInvoicesComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Παραστατικού' },
            component: EditInvoicesComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'tax-exemption',
        data: { title: 'Υπολογισμός ΦΠΑ' },
        children: [
          {
            path: '',
            component: CalulateFpaComponent
          }
        ]
      },
      {
        path: 'agents',
        data: { title: 'Πράκτορες' },
        children: [
          {
            path: '',
            component: PreviewAgentComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            data: { title: 'Δημιουργία Πράκτορα' },
            path: 'create',
            component: CreateAgentComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Πράκτορα' },
            component: EditAgentComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'locations',
        data: { title: 'Περιοχές' },
        children: [
          {
            path: '',
            component: LocationsComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            data: { title: 'Δημιουργία Περιοχής' },
            path: 'create',
            component: CreateLocationComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Περιοχής' },
            component: EditLocationComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'stations',
        data: { title: 'Σταθμοί' },
        children: [
          {
            path: '',
            component: StationsComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Σταθμού' },
            component: CreateStationComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Σταθμού' },
            component: EditStationComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'places',
        data: { title: 'Τοποθεσίες' },
        children: [
          {
            path: '',
            component: PlacesPreviewComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Τοποθεσίας' },
            component: CreatePlaceComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Τοποθεσίες' },
            component: EditPlaceComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'languages',
        data: { title: 'Γλώσσες' },
        children: [
          {
            path: '',
            component: PreviewLanguagesComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Γλώσσας' },
            component: CreateLanguagesComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Γλώσσας' },
            component: EditLanguagesComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'booking-sources',
        data: { title: 'Πηγές Κρατήσεων' },
        children: [
          {
            path: '',
            component: PreviewBookingSourceComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Πηγής Κρατήσεων' },
            component: CreateBookingSourceComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Πηγής Κρατήσεων' },
            component: EditBookingSourceComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'rate-codes',
        data: { title: 'Rate Codes' },
        children: [
          {
            path: '',
            component: PreviewRateCodeComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Rate Codes' },
            component: CreateRateCodeComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Rate Codes' },
            component: EditRateCodeComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'users',
        data: { title: 'Χρήστες' },
        children: [
          {
            path: '',
            component: PreviewUserComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Χρήστη' },
            component: CreateUserComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Χρήστη' },
            component: EditUserComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'roles',
        data: { title: 'Ρόλοι' },
        children: [
          {
            path: '',
            component: PreviewRolesComponent,
            resolve: { tableData: PreviewResolver },
            runGuardsAndResolvers: 'paramsOrQueryParamsChange'
          },
          {
            path: 'create',
            data: { title: 'Δημιουργία Ρόλου' },
            component: CreateRolesComponent,
            canDeactivate: [CandeactivateGuard]
          },
          {
            path: ':id',
            data: { title: 'Επεξεργασία Ρόλου' },
            component: EditRolesComponent,
            canDeactivate: [CandeactivateGuard]
          }
        ]
      },
      {
        path: 'home',
        data: { title: 'Αρχική' },
        component: HomeComponent,
        pathMatch: 'full'
      },
    ]
  },
  {
    path: '**',
    redirectTo: '/home',
    pathMatch: 'full'
  }

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
