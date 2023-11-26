import { TestBed } from '@angular/core/testing';

import { PreviewResolver } from './preview.resolver';

describe('PreviewResolver', () => {
  let resolver: PreviewResolver;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    resolver = TestBed.inject(PreviewResolver);
  });

  it('should be created', () => {
    expect(resolver).toBeTruthy();
  });
});
