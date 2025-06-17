import { Module } from '@nestjs/common';
import { MongooseModule } from '@nestjs/mongoose';
import { BlobStorageService } from './blob-storage.service';
import { BlobSchema } from './schema/blob.schema';

@Module({
  imports: [MongooseModule.forFeature([{ name: Blob.name, schema: BlobSchema }])],
  providers: [BlobStorageService],
  exports: [BlobStorageService],
})
export class BlobStorageModule {}
