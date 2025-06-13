import { Controller, Post, Body, Req, Query, UseGuards, Patch, Param } from '@nestjs/common';
import { AuthService } from './auth.service';
import { LoginDto } from './dto/login.dto';
import { RegisterDto } from './dto/register.dto';
import { ForgotPasswordDto } from './dto/forgot-password.dto';
import { JwtAuthGuard } from './jwt-auth.guard';
import { Request } from 'express';

@Controller('auth')
export class AuthController {
    constructor(private readonly authService: AuthService) { }

    @Post('login')
    async login(@Body() dto: LoginDto, @Req() req) {
        return this.authService.login(dto, req.ip);
    }

    @Post('register')
    async register(@Body() dto: RegisterDto) {
        return this.authService.register(dto);
    }

    @Post('forgot-password')
    async forgotPassword(@Body() dto: ForgotPasswordDto) {
        return this.authService.forgotPassword(dto);
    }

    @Post('confirm-email')
    async confirmEmail(@Query('token') token: string) {
        return this.authService.confirmEmail(token);
    }

    @Post('logout')
    @UseGuards(JwtAuthGuard)
    logout(@Req() req: Request) {
        return this.authService.logout(req.user);
    }

    @Patch('reset-password/:token')
    async resetPassword(@Param('token') token: string, @Body() body: { password: string }) {
        return this.authService.resetPassword(token, body.password);
    }

}