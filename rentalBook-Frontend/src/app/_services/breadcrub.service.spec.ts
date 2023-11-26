import { TestBed } from '@angular/core/testing';

import { BreadcrubService } from './breadcrub.service';

describe('BreadcrubService', () => {
  let service: BreadcrubService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(BreadcrubService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
