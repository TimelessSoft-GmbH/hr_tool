import { IsNotEmpty, IsDateString, IsString, IsEnum, IsOptional } from 'class-validator';

export enum RequestStatus {
  Pending = 'pending',
  Approved = 'approved',
}

export class CreateVacationDto {
  @IsString()
  @IsNotEmpty()
  user_id: string;

  @IsDateString()
  @IsNotEmpty()
  start_date: string;

  @IsDateString()
  @IsNotEmpty()
  end_date: string;

  @IsEnum(RequestStatus)
  @IsOptional()
  status?: RequestStatus = RequestStatus.Pending;
}
