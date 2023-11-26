import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, Data, NavigationEnd, Router } from '@angular/router';
import { BehaviorSubject, filter } from 'rxjs';
import { ICrumb } from '../breadcrumbs/crumb.interface';

@Injectable({
  providedIn: 'root'
})
export class BreadcrumbService {

  // Subject emitting the breadcrumb hierarchy
  private readonly _breadcrumbs$ = new BehaviorSubject<ICrumb[]>([]);

  // Observable exposing the breadcrumb hierarchy
  readonly breadcrumbs$ = this._breadcrumbs$.asObservable();

  constructor(private router: Router) {
    this.router.events.pipe(
      // Filter the NavigationEnd events as the breadcrumb is updated only when the route reaches its end
      filter((event) => event instanceof NavigationEnd)
    ).subscribe(event => {
      // Construct the breadcrumb hierarchy
      const root = this.router.routerState.snapshot.root;
      var breadcrumbs: ICrumb[] = [];
      this.addBreadcrumb(root, [], breadcrumbs);

//--needed to make unique the breadcrumbs for custom urls like payments and types
      var resArr = [];
      breadcrumbs.filter(function (item) {
        var i = resArr.findIndex(x => (x.label == item.label));
        if (i <= -1) {
          resArr.push(item);
        }
        return null;
      });
      breadcrumbs = resArr;

      // Emit the new hierarchy
      this._breadcrumbs$.next(breadcrumbs);
      // console.warn(breadcrumbs);
    });
  }

  private addBreadcrumb(route: ActivatedRouteSnapshot, parentUrl: string[], breadcrumbs: ICrumb[]) {
    if (route) {
      // Construct the route URL
      let routeUrl = parentUrl.concat(route.url.map(url => url.path));
      //console.log(routeUrl);
      var breadcrumb = null;
      if (routeUrl[0] == 'payments' && route.url && routeUrl.join('/') != parentUrl.join('/')) {
          if (routeUrl[1] == 'payment') {
            breadcrumb = {
              label: this.getLabel({ title: 'Εισπράξεις' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
                  if (routeUrl[2] == 'create') {
                    breadcrumb = {
                      label: this.getLabel({ title: 'Δημιουργία Είσπραξης' }),
                      link: '/' + routeUrl.join('/')
                    };
                    breadcrumbs.push(breadcrumb);
                  } else if (routeUrl[2]) {
                    breadcrumb = {
                      label: this.getLabel({ title: 'Επεξεργασία Είσπραξης' }),
                      link: '/' + routeUrl.join('/')
                    };
                    breadcrumbs.push(breadcrumb);
                  }
        }
        else if (routeUrl[1] == 'refund') {
            breadcrumb = {
              label: this.getLabel({ title: 'Επιστροφές Χρημάτων' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
            if (routeUrl[2] == 'create') {
              breadcrumb = {
                label: this.getLabel({ title: 'Δημιουργία Επιστροφής Χρημάτων' }),
                link: '/' + routeUrl.join('/')
              };
              breadcrumbs.push(breadcrumb);
            } else if (routeUrl[2]) {
              breadcrumb = {
                label: this.getLabel({ title: 'Επεξεργασία Επιστροφές Χρημάτων' }),
                link: '/' + routeUrl.join('/')
              };
              breadcrumbs.push(breadcrumb);
            }

        }
        else if (routeUrl[1] == 'pre-auth') {
            breadcrumb = {
              label: this.getLabel({ title: 'Εγγυήσεις' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
            if (routeUrl[2] == 'create') {
              breadcrumb = {
                label: this.getLabel({ title: 'Δημιουργία Εγγύησης' }),
                link: '/' + routeUrl.join('/')
              };
              breadcrumbs.push(breadcrumb);
            } else if (routeUrl[2]) {
              breadcrumb = {
                label: this.getLabel({ title: 'Επεξεργασία Εγγύησης' }),
                link: '/' + routeUrl.join('/')
              };
              breadcrumbs.push(breadcrumb);
            }

        }
        else {
            breadcrumb = {
              label: this.getLabel({ title: 'Επιστροφές Χρημάτων Εγγυήσεων' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
            if (routeUrl[2] == 'create') {
              breadcrumb = {
                label: this.getLabel({ title: 'Δημιουργία Επιστροφής Χρημάτων Εγγύησης' }),
                link: '/' + routeUrl.join('/')
              };
              breadcrumbs.push(breadcrumb);
            } else if (routeUrl[2]) {
              breadcrumb = {
                label: this.getLabel({ title: 'Επεξεργασία Επιστροφής Χρημάτων Εγγύησης' }),
                link: '/' + routeUrl.join('/')
              };
              breadcrumbs.push(breadcrumb);
            }
        }

      }
      else if (routeUrl[0] == 'options' && route.url && routeUrl.join('/') != parentUrl.join('/')) {
        if (routeUrl[1] == 'extras') {
          breadcrumb = {
            label: this.getLabel({ title: 'Παροχές/Αξεσουάρ' }),
            link: '/' + routeUrl.join('/')
          };
          breadcrumbs.push(breadcrumb);
          if (routeUrl[2] == 'create') {
            breadcrumb = {
              label: this.getLabel({ title: 'Δημιουργία Παροχής/Αξεσουάρ' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
          } else if (routeUrl[2]) {
            breadcrumb = {
              label: this.getLabel({ title: 'Επεξεργασία Παροχής/Αξεσουάρ' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
          }
        }
       else if (routeUrl[1] == 'insurances') {
          breadcrumb = {
            label: this.getLabel({ title: 'Ασφάλειες' }),
            link: '/' + routeUrl.join('/')
          };
          breadcrumbs.push(breadcrumb);
          if (routeUrl[2] == 'create') {
            breadcrumb = {
              label: this.getLabel({ title: 'Δημιουργία Ασφάλειας' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
          } else if (routeUrl[2]) {
            breadcrumb = {
              label: this.getLabel({ title: 'Επεξεργασία Ασφάλειας' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
          }
        }
        else if (routeUrl[1] == 'transport') {
          breadcrumb = {
            label: this.getLabel({ title: 'Υπηρεσίες' }),
            link: '/' + routeUrl.join('/')
          };
          breadcrumbs.push(breadcrumb);
          if (routeUrl[2] == 'create') {
            breadcrumb = {
              label: this.getLabel({ title: 'Δημιουργία Υπηρεσίας' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
          } else if (routeUrl[2]) {
            breadcrumb = {
              label: this.getLabel({ title: 'Επεξεργασία Υπηρεσίας' }),
              link: '/' + routeUrl.join('/')
            };
            breadcrumbs.push(breadcrumb);
          }
        }
      }
      // Add an element for the current route part
      else if (route.url && routeUrl.join('/') != parentUrl.join('/')) {
        let breadcrumb = {
          label: this.getLabel(route.data),
          link: '/' + routeUrl.join('/')
        };
        breadcrumbs.push(breadcrumb);
      }

     // console.log(breadcrumbs);
      // Add another element for the next route part
      this.addBreadcrumb(route.firstChild, routeUrl, breadcrumbs);
    }
  }

  private getLabel(data: Data) {
    // The breadcrumb can be defined as a static string or as a function to construct the breadcrumb element out of the route data
    return typeof data.title === 'function' ? data.title(data) : data.title;
  }



  //   capitalize(str):string {
  //   return str[0].toUpperCase() + str.slice(1)
  // }

}
