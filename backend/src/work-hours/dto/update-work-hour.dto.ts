import { IsInt, IsNumber, IsString, Min, Max } from 'class-validator';

export class UpdateWorkHourDto {
  @IsString()
  user_id: string;

  @IsInt()
  @Min(2021)
  year: number;

  @IsInt()
  @Min(1)
  @Max(12)
  month: number;

  @IsNumber()
  hours: number;
}
