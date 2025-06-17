import { ConflictException, Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { Model, Types } from 'mongoose';
import { Blob, BlobWithoutData } from './schema/blob.schema';

@Injectable()
export class BlobStorageService {
  constructor(@InjectModel(Blob.name) private blobModel: Model<Blob>) {}

  async store(blob: Omit<Blob, '_id' | 'createdAt' | 'updatedAt'>): Promise<BlobWithoutData> {
    if (blob.multiple) {
      return this.blobModel.create(blob);
    } else {
      const dbBlob = await this.blobModel
        .findOneAndUpdate(
          {
            ref: blob.ref,
            refCollection: blob.refCollection,
            name: blob.name,
          },
          { ...blob, $set: { data: blob.data } },
          { upsert: true, new: true },
        )
        .select({ data: false })
        .exec();
      return dbBlob.toObject();
    }
  }

  async retrieveOne({ ref, refCollection, name }: Pick<Blob, 'ref' | 'refCollection' | 'name'>, withData: boolean): Promise<Blob | null>;
  async retrieveOne({ ref, refCollection, name }: Pick<Blob, 'ref' | 'refCollection' | 'name'>, withData?: false): Promise<BlobWithoutData | null>;
  async retrieveOne({ ref, refCollection, name }: Pick<Blob, 'ref' | 'refCollection' | 'name'>, withData?: boolean): Promise<Blob | BlobWithoutData | null> {
    const query = this.blobModel.findOne({ ref, refCollection, name });
    if (!withData) {
      query.select({ data: false });
    }
    return query.exec();
  }

  async retrieveOneById(blobId: string | Types.ObjectId, withData: true): Promise<Blob>;
  async retrieveOneById(blobId: string | Types.ObjectId, withData?: false): Promise<BlobWithoutData>;
  async retrieveOneById(blobId: string | Types.ObjectId, withData?: boolean): Promise<Blob | BlobWithoutData> {
    const query = this.blobModel.findById(blobId);
    if (!withData) {
      query.select({ data: false });
    }

    const blob = await query.exec();
    if (!blob) throw new ConflictException(`Blob with id ${String(blobId)} does not exist anymore.`);
    return blob;
  }

  async retrieveMultiple({ ref, refCollection, name }: Pick<Blob, 'ref' | 'refCollection' | 'name'>): Promise<BlobWithoutData[]> {
    return this.blobModel.find({ ref, refCollection, name }).select({ data: false }).lean().exec();
  }
}
