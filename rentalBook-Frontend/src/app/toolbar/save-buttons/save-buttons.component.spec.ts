import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SaveButtonsComponent } from './save-buttons.component';

describe('SaveButtonsComponent', () => {
  let component: SaveButtonsComponent;
  let fixture: ComponentFixture<SaveButtonsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SaveButtonsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SaveButtonsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
