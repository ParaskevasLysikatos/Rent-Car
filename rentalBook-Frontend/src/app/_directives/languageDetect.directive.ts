import { Directive, HostListener } from '@angular/core';
import { NotificationService } from '../_services/notification.service';

@Directive({
  selector: 'input[language]'
})
export class LanguageDirective {

  constructor(public notSrv: NotificationService) { }

  @HostListener('input', ['$event'])
  onInput(event) {
    event.target.value = this.lngtype(event.target.value)
  }



  lngtype(text: string): string {
    // var text = text.replace(/\s/g); //read input value, and remove "space" by replace \s
    //Dictionary for Unicode range of the languages
    var langdic = {
      // "arabic": /[\u0600-\u06FF]/,
      // "persian": /[\u0750-\u077F]/,
      // "Hebrew": /[\u0590-\u05FF]/,
      // "Syriac": /[\u0700-\u074F]/,
      // "Bengali": /[\u0980-\u09FF]/,
      // "Ethiopic": /[\u1200-\u137F]/,
      "Greek and Coptic": /[\u0370-\u03FF]/,
      // "Georgian": /[\u10A0-\u10FF]/,
      // "Thai": /[\u0E00-\u0E7F]/,
      "english": /^[a-zA-Z]+$/
      //add other languages her
    }
    let arrayLang = [];
    let uniqueArray = [];
    //const keys = Object.keys(langdic); //read  keys
    //const keys = Object.values(langdic); //read  values
    const keys = Object.entries(langdic); // read  keys and values from the dictionary
    for (let char of text) {
      Object.entries(langdic).forEach(([key, value]) => {  // loop to read all the dictionary items if not true
        if (value.test(char) == true) {   //Check Unicode to see which one is true
          arrayLang.push(key);
          uniqueArray = [...new Set(arrayLang)]
          //return //document.getElementById("lang_her").innerHTML = key; //print language name if unicode true
          if (uniqueArray.length > 1) {
            this.notSrv.showErrorNotification('careful 2 Languages in same input');
          }
         // console.log(uniqueArray);
        }
      });
    }
    return text;
  }

}

