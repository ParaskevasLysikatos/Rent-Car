import { TestBed } from '@angular/core/testing';

import { IconUploadService } from './icon-upload.service';

describe('IconUploadService', () => {
  let service: IconUploadService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(IconUploadService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
