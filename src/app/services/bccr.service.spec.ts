import { TestBed } from '@angular/core/testing';

import { BccrService } from './bccr.service';

describe('BccrService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: BccrService = TestBed.get(BccrService);
    expect(service).toBeTruthy();
  });
});
