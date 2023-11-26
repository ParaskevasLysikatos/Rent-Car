import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { skip } from 'rxjs/operators';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IOptionsCollection } from '../options-collection.interface';
import { OptionsService } from '../options.service';

@Component({
  selector: 'app-preview-options',
  templateUrl: './preview-options.component.html',
  styleUrls: ['./preview-options.component.scss'],
  providers: [{provide: ApiService, useClass: OptionsService}]
})
export class PreviewOptionsComponent extends PreviewComponent<IOptionsCollection> {
  displayedColumns = ['profilesT','profilesD','cost_max','cost_daily','items_max','icon','default_on','order', 'actions'];
  yesOrNo:any;
  type!: string|null;
  protected apiSrv!: OptionsService<IOptionsCollection>;
  @ViewChild('Icon', { static: true }) icon: TemplateRef<any>;

  constructor(protected injector: Injector,public urlSrv: Router,public optionsSrv: OptionsService<IOptionsCollection>) {
    super(injector);
  }
  ngOnInit(){
    super.ngOnInit();
    this.columns = [
      {
        columnDef: 'profilesT',
        header: 'Τίτλος',
        cell: (element: IOptionsCollection) => `${element.profiles.el.title}`,
        hasFilter: true,
        filterField: 'title',
      },
      {
        columnDef: 'profilesD',
        header: 'Περιγραφή',
        cell: (element: IOptionsCollection) => `${element.profiles.el.description ?? 'Μη Μεταφρασμένο'}`,
        hasFilter: true,
        filterField: 'description',
      },
      {
        columnDef: 'cost_max',
        header: 'Μέγιστο κόστος',
        cell: (element: IOptionsCollection) => `${element.cost_max}`
      },
      {
        columnDef: 'cost_daily',
        header: 'Κόστος ανά ημέρα',
        cell: (element: IOptionsCollection) => `${element.cost_daily}` + `${element.active_daily_cost==true? '  (Ενεργό)':''}`
      },
      {
        columnDef: 'items_max',
        header: 'Μέγιστη ποσότητα',
        cell: (element: IOptionsCollection) => `${element.items_max}`
      },
      {
        columnDef: 'icon',
        header: 'Εικονίδιο',
       // cell: (element: IOptionsCollection) => `${element.icon}`
        cellTemplate: this.icon
      },
      {
        columnDef: 'default_on',
        header: 'Πάντα επιλεγμένο',
        cell: (element: IOptionsCollection) => `${element.default_on==true?this.yesOrNo='Ναι':this.yesOrNo='Όχι'}`
      },
      {
        columnDef: 'order',
        header: 'Σειρά',
        cell: (element: IOptionsCollection) => `${element.order}`
      }
    ];
  }

  ngAfterContentChecked(): void {
    //Called after every check of the component's or directive's content.
    //Add 'implements AfterContentChecked' to the class.
    this.type = this.urlSrv.url.split('/')[2];
    if(this.type.includes('?')){
      this.type = this.type.split('?')[0];
    }

  }


  // ngOnInit() {
  //   super.ngOnInit();
  //   this.type = this.urlSrv.url.split('/')[2];
  //   console.log(this.type);
  //   this.apiSrv.setType(this.type);
  // }

  // ngOnDestroy() {
  //   super.ngOnDestroy();
  //   this.apiSrv.setType(null);
  // }
}
