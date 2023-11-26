import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-toolbar',
  templateUrl: './toolbar.component.html',
  styleUrls: ['./toolbar.component.scss']
})
export class ToolbarComponent implements OnInit {
  @Input() onSubmit!: () => boolean|Observable<any>;

  constructor(private router: Router) { }

  ngOnInit(): void {
  }

  save() {
    const submit = this.onSubmit();
    if (submit instanceof Observable) {
      submit.subscribe();
    }
    return submit;
  }

  saveAndNew() {
    if (this.save() !== false) {
      
    }
  }

  saveAndBack() {
    if (this.save() !== false) {
      this.router.navigate(['/home']);
    }
  }
}
