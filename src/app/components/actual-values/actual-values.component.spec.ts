import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActualValuesComponent } from './actual-values.component';

describe('ActualValuesComponent', () => {
  let component: ActualValuesComponent;
  let fixture: ComponentFixture<ActualValuesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActualValuesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActualValuesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
