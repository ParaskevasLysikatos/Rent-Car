import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreatePaymentComponent } from 'src/app/payment/create-payment/create-payment.component';
import { EditPaymentComponent } from 'src/app/payment/edit-payment/edit-payment.component';
import { PaymentSelectorService } from 'src/app/payment/payment-selector.service';
import { IPayment } from 'src/app/payment/payment.interface';
import { PaymentService } from 'src/app/payment/payment.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-payment-selector',
  templateUrl: './payment-selector.component.html',
  styleUrls: ['./payment-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => PaymentSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: PaymentSelectorComponent
    },
    { provide: ApiService, useClass: PaymentSelectorService}
  ]
})
export class PaymentSelectorComponent extends AbstractSelectorComponent<IPayment> {
  readonly EditComponent = EditPaymentComponent;
  readonly CreateComponent = CreatePaymentComponent;
  label = 'Πληρωμές';






  NavigateLink(p_type: string,id: string){
   // this.selector.router.navigate(['/payments/' +p_type, id])
    this.selector.router.navigate([]).
    then(result => { window.open(`/payments/`+p_type+'/'+id, '_blank'); });
  }


}
