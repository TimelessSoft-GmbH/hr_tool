import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import mongoose, { Types } from 'mongoose';
import { BaseSchema } from 'src/utils/base.schema';

@Schema({ timestamps: true })
export class Blob extends BaseSchema {
  @Prop({ type: mongoose.Schema.Types.ObjectId, ref: 'User' })
  createdBy?: Types.ObjectId;

  @Prop({ type: mongoose.Schema.Types.ObjectId, refPath: 'refCollection' })
  ref: Types.ObjectId;

  @Prop({ type: String })
  refCollection: string;

  @Prop({ type: String })
  name: string;

  @Prop({ type: Buffer })
  data: Buffer;

  @Prop({ type: String })
  mimeType: string;

  @Prop({ type: String })
  multiple: boolean;

  @Prop({ type: String })
  filename?: string;
}

export type BlobWithoutData = Omit<Blob, 'data'>;

export const BlobSchema = SchemaFactory.createForClass(Blob);
