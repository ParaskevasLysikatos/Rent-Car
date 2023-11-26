import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class LanguageService {
  lang: BehaviorSubject<string> = new BehaviorSubject('el');
  lang$ = this.lang.asObservable();

  constructor() { }

  onLangChange(): Observable<string> {
    return this.lang$;
  }

  setLang(lang: string): void {
    this.lang.next(lang);
  }

  getCurrentLang(): string {
    return 'el';
  }

  getLangs(): Array<string> {
    return [
      'el', 'en'
    ];
  }
}
