/* tslint:disable:no-unused-variable */

import { TestBed, async, inject } from '@angular/core/testing';
import { TotalBookingService } from './total-booking.service';

describe('Service: TotalBooking', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [TotalBookingService]
    });
  });

  it('should ...', inject([TotalBookingService], (service: TotalBookingService) => {
    expect(service).toBeTruthy();
  }));
});
