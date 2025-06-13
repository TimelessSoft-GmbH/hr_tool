import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document } from 'mongoose';
import * as mongoose from 'mongoose';

export type SicknessRequestDocument = SicknessRequest & Document;

@Schema({ timestamps: true })
export class SicknessRequest {
  @Prop({ type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true })
  userId: string;

  @Prop({ required: true })
  startDate: Date;

  @Prop({ required: true })
  endDate: Date;

  @Prop({ required: true })
  totalDays: number;

  @Prop({ default: 'pending', enum: ['pending', 'approved'] })
  status: string;
}

export const SicknessRequestSchema = SchemaFactory.createForClass(SicknessRequest);
