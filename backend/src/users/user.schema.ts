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

  @Prop({ type: String, default: null })
  address: string;

  @Prop({ type: String, default: null })
  phoneNumber: string;

  @Prop({ type: String, default: null })
  initials: string;

  @Prop({ type: String, default: null })
  image: string;

  @Prop({ type: Number, default: null })
  salary: number;

  @Prop({ type: Date })
  email_verified_at: Date;

  @Prop({ type: Number, default: 25 })
  vacationDays: number;

  @Prop({ type: Number, default: null })
  vacationDays_left: number;

  @Prop({ type: Number, default: null })
  sicknessLeave: number;

  @Prop({ type: Date })
  start_of_work: Date;

  @Prop()
  remember_token: string;

  @Prop()
  contract: string;

  @Prop({ type: Number, default: null })
  hours_per_week: number;
}

export type UserDocument = User;
export const UserSchema = SchemaFactory.createForClass(User);
