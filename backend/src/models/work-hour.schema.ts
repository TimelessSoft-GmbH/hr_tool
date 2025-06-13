import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document } from 'mongoose';
import * as mongoose from 'mongoose';

export type WorkHourDocument = WorkHour & Document;

@Schema({ timestamps: true })
export class WorkHour {
  @Prop({ type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true })
  user_id: string;

  @Prop({ required: true })
  year: number;

  @Prop({ required: true })
  month: number;

  @Prop({ required: true })
  hours: number;
}

export const WorkHourSchema = SchemaFactory.createForClass(WorkHour);
