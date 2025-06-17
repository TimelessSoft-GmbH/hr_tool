import { MongooseModule } from '@nestjs/mongoose';
import { Test, TestingModule } from '@nestjs/testing';
import { MongoMemoryServer } from 'mongodb-memory-server';
import { BlobStorageService } from './blob-storage.service';
import { Blob, BlobSchema } from './schema/blob.schema';

describe('BlobStorageService', () => {
  let service: BlobStorageService;

  beforeAll(async () => {
    const mongoServer = await MongoMemoryServer.create();

    const module: TestingModule = await Test.createTestingModule({
      // prettier-ignore
      imports: [
        MongooseModule.forRoot(mongoServer.getUri()),
        MongooseModule.forFeature([{ name: Blob.name, schema: BlobSchema }]),
      ],
      providers: [BlobStorageService],
    }).compile();

    service = module.get<BlobStorageService>(BlobStorageService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
