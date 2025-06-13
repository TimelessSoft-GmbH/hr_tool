import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document } from 'mongoose';

@Schema({ timestamps: true })
export class User extends Document {
  @Prop({ required: true })
  name: string;

  @Prop({ required: true, unique: true, lowercase: true })
  email: string;

  @Prop({ required: true })
  password: string;

  @Prop({ default: false })
  isEmailConfirmed: boolean;

  @Prop({ type: String, default: null })
  resetToken: string | null;

  @Prop({ type: Date, default: null })
  resetTokenExpires: Date | null;

  @Prop({ type: [String], default: ['user'] })
  roles: string[];

  @Prop({ type: [String], default: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] })
  workdays: string[];

  @Prop()
  address: string;

  @Prop()
  phoneNumber: string;

  @Prop()
  initials: string;

  @Prop()
  image: string;

  @Prop()
  salary: number;

  @Prop({ type: Date })
  email_verified_at: Date;

  @Prop()
  vacationDays: number;

  @Prop()
  vacationDays_left: number;

  @Prop()
  sicknessLeave: number;

  @Prop({ type: Date })
  start_of_work: Date;

  @Prop()
  remember_token: string;

  @Prop()
  contract: string;

  @Prop()
  hours_per_week: number;
}

export type UserDocument = User;
export const UserSchema = SchemaFactory.createForClass(User);
