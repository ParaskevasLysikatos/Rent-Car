import { coerceBooleanProperty } from '@angular/cdk/coercion';
import { Platform } from '@angular/cdk/platform';
import { DOCUMENT } from '@angular/common';
import { Component, Inject, LOCALE_ID, Renderer2 } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Settings } from 'luxon';
import { filter, map } from 'rxjs';
import { ConfigName } from 'src/@vex/interfaces/config-name.model';
import { ConfigService } from 'src/@vex/services/config.service';
import { LayoutService } from 'src/@vex/services/layout.service';
import { NavigationService } from 'src/@vex/services/navigation.service';
import { SplashScreenService } from 'src/@vex/services/splash-screen.service';
import { Style, StyleService } from 'src/@vex/services/style.service';
import icHome from '@iconify/icons-ic/home';
import icMenuBook from '@iconify/icons-ic/twotone-menu-book';
import icCarRental from '@iconify/icons-ic/twotone-car-rental';
import icQuotes from '@iconify/icons-ic/search';
import icVehicles from '@iconify/icons-ic/directions-car';
import icAccountBalance from '@iconify/icons-ic/account-balance';
import icCustomers from '@iconify/icons-ic/attach-money';
import icPartner from '@iconify/icons-ic/twotone-handshake';
import icBuild from '@iconify/icons-ic/build';
import icBusiness from '@iconify/icons-ic/business';
import icSettings from '@iconify/icons-ic/settings';
import icFunctions from '@iconify/icons-fa-solid/suitcase';
import { IconService } from '@visurel/iconify-angular';
import { eaIcons } from 'src/ea-icons/ea-icons';
import { AuthService } from './_services/auth.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'rentalbook-frontend';

  constructor(private configService: ConfigService,
              private styleService: StyleService,
              private renderer: Renderer2,
              private platform: Platform,
              @Inject(DOCUMENT) private document: Document,
              @Inject(LOCALE_ID) private localeId: string,
              private layoutService: LayoutService,
              private route: ActivatedRoute,
              private navigationService: NavigationService,
              private splashScreenService: SplashScreenService,
              private iconSrv: IconService,
              private authService: AuthService
              ) {
   // this.authService.getUser();
    // this.authService.user$.subscribe(res => console.log(res));
    // iconSrv.registerAll(JSON.parse(eaIcons));
    for(const icon of Object.keys(eaIcons)) {
      const iconObject = eaIcons[icon];
      iconSrv.register('ea-'+icon, iconObject);
    }
    Settings.defaultLocale = this.localeId;

    if (this.platform.BLINK) {
      this.renderer.addClass(this.document.body, 'is-blink');
    }

    /**
    * Customize the template to your needs with the ConfigService
    * Example:
    *  this.configService.updateConfig({
    *    sidenav: {
    *      title: 'Custom App',
    *      imageUrl: '//placehold.it/100x100',
    *      showCollapsePin: false
    *    },
    *    footer: {
    *      visible: false
    *    }
    *  });
    */

    this.configService.updateConfig({
      sidenav: {
        title: 'RentalBook',
        imageUrl: 'assets/logo.svg',
        showCollapsePin: false,
      },
      toolbar: {
        fixed: false
      },
      footer: {
        visible: false
      }
    });

    /**
    * Config Related Subscriptions
    * You can remove this if you don't need the functionality of being able to enable specific configs with queryParams
    * Example: example.com/?layout=apollo&style=default
    */
    this.route.queryParamMap.pipe(
      filter(queryParamMap => queryParamMap.has('rtl')),
      map(queryParamMap => coerceBooleanProperty(queryParamMap.get('rtl'))),
    ).subscribe(isRtl => {
      this.configService.updateConfig({
        rtl: isRtl
      });
    });

    this.route.queryParamMap.pipe(
      filter(queryParamMap => queryParamMap.has('layout'))
    ).subscribe(queryParamMap => this.configService.setConfig(queryParamMap.get('layout') as ConfigName));

    this.route.queryParamMap.pipe(
      filter(queryParamMap => queryParamMap.has('style'))
    ).subscribe(queryParamMap => this.styleService.setStyle(queryParamMap.get('style') as Style));

    this.navigationService.items = [
      {
        type: 'subheading',
        label: 'DASHBOARD',
        children: [
          {
            type: 'link',
            label: 'Αρχική',
            icon: icHome,
            route: '/home'
          },
        ]
      },
      {
        type: 'subheading',
        label: 'ΚΡΑΤΗΣΕΙΣ',
        children: [
          {
            type: 'dropdown',
            label: 'Προσφορές',
            icon: icQuotes,
            children: [
              {
                type: 'link',
                label: 'Προσφορές',
                route: '/quotes',
                routerLinkActiveOptions: { exact: true }
              },
              {
                type: 'link',
                label: 'Νέα προσφορά',
                route: '/quotes/create'
              },
            ]
          },
          {
            type: 'dropdown',
            label: 'Κρατήσεις',
            icon: icMenuBook,
            children: [
              {
                type: 'link',
                label: 'Κρατήσεις',
                route: '/bookings',
                routerLinkActiveOptions: { exact: true }
              },
              {
                type: 'link',
                label: 'Νέα κράτηση',
                route: '/bookings/create'
              },
            ]
          },
          {
            type: 'dropdown',
            label: 'Μισθώσεις',
            icon: icCarRental,
            children: [
              {
                type: 'link',
              //  label: 'Όλες οι Μισθώσεις',
                label: 'Μισθώσεις',
                route: '/rentals',
                routerLinkActiveOptions: { exact: true }
              },
              // {
              //   type: 'link',
              //   label: 'Ενεργές Μισθώσεις',
              //   route: '/rentals/active',
              // },
              {
                type: 'link',
                label: 'Νέα μίσθωση',
                route: '/rentals/create'
              },
            ]
          },
        ]
      },
      {
        type: 'subheading',
        label: 'ΛΟΓΙΣΤΗΡΙΟ',
        children: [
          {
            type: 'dropdown',
            label: 'Οικονομικά',
            icon: icAccountBalance,
            children: [
              {
                type: 'link',
                label: 'Πληρωμές',
                route: '/payments/payment'
              },
              {
                type: 'link',
                label: 'Παραστατικά',
                route: '/invoices'
              },
              {
                type: 'link',
                label: 'Υπολογισμός ΦΠΑ',
                route: '/tax-exemption'
              }
            ]
          },
        ]
      },
      {
        type: 'subheading',
        label: 'ΣΥΝΕΡΓΑΤΕΣ',
        children: [
          {
            type: 'dropdown',
            label: 'Πελάτες',
            icon: icCustomers,
            children: [
              {
                type: 'link',
                label: 'Επαφές',
                route: '/contacts'
              },
              {
                type: 'link',
                label: 'Οδηγοί',
                route: '/drivers'
              }, {
                type: 'link',
                label: 'Εταιρείες',
                route: '/companies'
              }
            ]
          },
          {
            type: 'dropdown',
            label: 'Συνεργάτες',
            icon: icPartner,
            children: [
              {
                type: 'link',
                label: 'Πράκτορες',
                route: '/agents'
              }
            ]
          },
        ]
      },
      {
        type: 'subheading',
        label: 'ΣΤΟΛΟΣ',
        children: [
          {
            type: 'dropdown',
            label: 'Λειτουργίες',
            icon: icFunctions,
            children: [
              {
                type: 'link',
                label: 'Μετακινήσεις',
                route: '/transition'
              },
               {
                type: 'link',
                label: 'Αντικαταστάσεις Οχημάτων',
                route: '/vehicle-exchanges'
              },
            ]
          },
          {
            type: 'dropdown',
            label: 'Στόλος Οχημάτων',
            icon: iconSrv.get('ea-vehicle2'),
            children: [
              {
                type: 'link',
                label: 'Λίστα Οχημάτων',
                route: '/vehicles'
              },
              {
                type: 'link',
                label: 'Groups',
                route: '/types'
              },
              {
                type: 'link',
                label: 'Παροχές/Αξεσουάρ',
                route: '/options/extras'
              },
              {
                type: 'link',
                label: 'Χαρακτηριστικά Αυτοκινήτου',
                route: '/characteristics'
              },
              {
                type: 'link',
                label: 'Κατηγορίες',
                route: '/categories'
              },
              {
                type: 'link',
                label: 'Ασφάλειες',
                route: '/options/insurances'
              },
              {
                type: 'link',
                label: 'Υπηρεσίες',
                route: '/options/transport'
              },
            ]
          },
          {
            type: 'dropdown',
            label: 'Συνεργείο',
            icon: icBuild,
            children: [
              {
                type: 'link',
                label: 'Επισκέψεις',
                route: '/visit'
              }
            ]
          },
          {
            type: 'dropdown',
            label: 'Εταιρεία',
            icon: icBusiness,
            children: [
              {
                type: 'link',
                label: 'Η Εταιρεία μου',
                route: '/company'
              },
              {
                type: 'link',
                label: 'Περιοχές',
                route: '/locations'
              },
              {
                type: 'link',
                label: 'Σταθμοί',
                // icon: '',
                route: '/stations'
              },
              {
                type: 'link',
                label: 'Τοποθεσίες',
                // icon: '',
                route: '/places'
              }
            ]
          }
        ]
      },

      {
        type: 'subheading',
        label: 'ΣΥΣΤΗΜΑ',
        children: [
          {
            type: 'dropdown',
            label: 'Ρυθμίσεις',
            icon: icSettings,
            children: [
              {
                type: 'link',
                label: 'Γλώσσες',
                route: '/languages'
              },
              {
                type: 'link',
                label: 'Πηγές Κρατήσεων',
                route: '/booking-sources'
              },
              {
                type: 'link',
                label: 'Rate Codes',
                route: '/rate-codes'
              },
              {
                type: 'link',
                label: 'Χρήστες',
                route: '/users'
              },
              {
                type: 'link',
                label: 'Ρόλοι',
                route: '/roles'
              },
              {
                type: 'link',
                label: 'Document Types',
                route: '/document-types'
              },
              {
                type: 'link',
                label: 'Documents',
                route: '/documents'
              },
              {
                type: 'link',
                label: 'Καταστάσεις Οχήματος',
                route: '/status'
              },
              {
                type: 'link',
                label: 'Brands',
                route: '/brands'
              },
              {
                type: 'link',
                label: 'Χρώματα',
                route: '/color_types'
              },
            ]
          },
        ]
      },
    ];
  }
}
