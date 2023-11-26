import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup } from '@angular/forms';

@Component({
  selector: 'app-calulate-fpa',
  templateUrl: './calculate-fpa.component.html',
})
export class CalulateFpaComponent implements OnInit {


myForm=new FormGroup({
  percentageFPA : new FormControl(24),
  amountFPA : new FormControl(''),
  priceWithoutFPA : new FormControl(''),
  priceWithFPA : new FormControl('')
    });

  constructor() { }



  ngOnInit(): void {
  }


  calculateWithout(){
    let pWithout=this.myForm.controls.priceWithoutFPA.value;
    let fpa=this.myForm.controls.percentageFPA.value;

    let pWith = (pWithout * (1+(fpa / 100))).toFixed(2);
    let aFpa= (pWithout * (fpa / 100)).toFixed(2);

    this.myForm.controls.amountFPA.setValue(aFpa);
    this.myForm.controls.priceWithFPA.setValue(pWith);
  }


  calculateWith(){
    let pWith=this.myForm.controls.priceWithFPA.value;
    let fpa=this.myForm.controls.percentageFPA.value;

    let pWithout:any = (pWith / (1+(fpa / 100))).toFixed(2);
    let aFpa = (pWith - pWithout).toFixed(2) ;

    this.myForm.controls.amountFPA.setValue(aFpa);
    this.myForm.controls.priceWithoutFPA.setValue(pWithout);
  }

  clear(){
    this.myForm.controls.amountFPA.setValue('');
     this.myForm.controls.priceWithoutFPA.setValue('');
      this.myForm.controls.priceWithFPA.setValue('');
  }

}
