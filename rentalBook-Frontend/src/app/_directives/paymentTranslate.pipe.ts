import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'paymentTranslate'
})
export class PaymentTranslatePipe implements PipeTransform {

  transform(value: string): string {
    return this.handleType(value);
  }



handleType(type:string):string {
    if (type == 'payment') {
      return type = 'Είσπραξη';
    } else if (type == 'refund') {
      return type = 'Επιστροφή Χρημάτων';
    } else if (type == 'pre-auth') {
      return type = 'Εγγύηση';
    } else if (type == 'refund-pre-auth') {
      return type = 'Επιστροφή Χρημάτων Εγγύησης';
    } else {
     return type = '';
    }
  }

}
