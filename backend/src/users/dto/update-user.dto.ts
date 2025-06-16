import {
  IsOptional,
  IsEmail,
  IsString,
  IsNumber,
  IsArray,
  IsDateString,
} from 'class-validator';

export class UpdateUserDto {
  @IsOptional()
  @IsString()
  name?: string;

  @IsOptional()
  @IsEmail()
  email?: string;

  @IsOptional()
  @IsString()
  phoneNumber?: string;

  @IsOptional()
  @IsString()
  address?: string;

  @IsOptional()
  @IsNumber()
  salary?: number;

  @IsOptional()
  @IsNumber()
  vacationDays?: number;

  @IsOptional()
  @IsString()
  contract?: string;

  @IsOptional()
  @IsNumber()
  hours_per_week?: number;

  @IsOptional()
  @IsString()
  image?: string;

  @IsOptional()
  @IsDateString()
  start_of_work?: string;

  @IsOptional()
  @IsArray()
  @IsString({ each: true })
  workdays?: string[];

  @IsOptional()
  @IsArray()
  roles: string[];
}
