import { IsNotEmpty, IsNumber, IsString } from 'class-validator';

export class WorkHoursDto {
  @IsString()
  @IsNotEmpty()
  user_id: string;

  @IsNumber()
  @IsNotEmpty()
  year: number;

  @IsNumber()
  @IsNotEmpty()
  month: number;

  @IsNumber()
  @IsNotEmpty()
  hours: number;
}
