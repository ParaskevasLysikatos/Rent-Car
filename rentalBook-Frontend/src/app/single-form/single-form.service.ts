import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SingleFormService {
  delete: Subject<void> = new Subject();
  save: Subject<string> = new Subject();
  saved$ = this.save.asObservable();
  delete$ = this.delete.asObservable();

  waitSaveToComplete: BehaviorSubject<boolean> = new BehaviorSubject(false);

  constructor() { }

  triggerDelete(): void {
    this.delete.next();
  }

  onDelete(): Observable<void> {
    return this.delete$;
  }

  saved(id: string): void {
    return this.save.next(id);
  }

  onSave() {
    return this.saved$;
  }
}
