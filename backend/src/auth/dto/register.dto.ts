import { IsEmail, IsString, MinLength, ValidateIf } from 'class-validator';

export class RegisterDto {
  @IsEmail()
  email: string;

  @IsString()
  @MinLength(6)
  password: string;

  @IsString()
  name: string;

  @ValidateIf(o => o.password_confirmation === o.password)
  password_confirmation: string;
}
