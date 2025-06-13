import { BadRequestException, Injectable, UnauthorizedException } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import * as bcrypt from 'bcrypt';
import { InjectModel } from '@nestjs/mongoose';
import { Model } from 'mongoose';
import { User, UserDocument } from '../users/user.schema';
import { LoginDto } from './dto/login.dto';
import { RegisterDto } from './dto/register.dto';
import { ForgotPasswordDto } from './dto/forgot-password.dto';
import * as crypto from 'crypto';
import { MailerService } from 'src/mailer/mailer.service';
import { UsersService } from 'src/users/users.service';

const rateLimit = new Map<string, { count: number, lastTry: number }>();

@Injectable()
export class AuthService {
    constructor(
        private jwtService: JwtService,
        @InjectModel(User.name) private userModel: Model<UserDocument>,
        private readonly mailer: MailerService,
        private usersService: UsersService,
    ) { }

    private async isRateLimited(email: string, ip: string) {
        const key = `${email.toLowerCase()}|${ip}`;
        const entry = rateLimit.get(key);

        if (entry && entry.count >= 5 && Date.now() - entry.lastTry < 60_000) {
            throw new Error(`Too many login attempts. Try again later.`);
        }

        if (entry) {
            entry.count++;
            entry.lastTry = Date.now();
        } else {
            rateLimit.set(key, { count: 1, lastTry: Date.now() });
        }
    }


    async login(dto: LoginDto, ip: string) {
        await this.isRateLimited(dto.email, ip);

        const user = await this.userModel.findOne({ email: dto.email.toLowerCase() });
        const passwordMatches = user ? await bcrypt.compare(dto.password, user.password) : false;

        if (!user || !passwordMatches) {
            throw new UnauthorizedException('Invalid credentials');
        }

        rateLimit.delete(`${dto.email.toLowerCase()}|${ip}`);

        const token = this.jwtService.sign({ sub: user._id, email: user.email });
        return { access_token: token, user: { id: user._id, email: user.email } };
    }

    async register(dto: RegisterDto) {
        if (dto.password !== dto.password_confirmation) {
            throw new Error('Passwords do not match');
        }

        const existing = await this.userModel.findOne({ email: dto.email });
        if (existing) throw new Error('Email already in use');

        const hash = await bcrypt.hash(dto.password, 10);
        const user = await this.userModel.create({
            name: dto.name,
            email: dto.email,
            password: hash,
            isEmailConfirmed: false,
        });

        const token = this.jwtService.sign({ sub: user._id });
        await this.mailer.sendEmailConfirmation(user.email, token);

        return { message: 'Registered. Please confirm your email.' };
    }


    async forgotPassword(dto: ForgotPasswordDto) {
        const user = await this.userModel.findOne({ email: dto.email });
        if (!user) throw new Error('User not found');

        const token = crypto.randomBytes(32).toString('hex');
        user.resetToken = token;
        user.resetTokenExpires = new Date(Date.now() + 1000 * 60 * 60); 
        await user.save();

        await this.mailer.sendEmailConfirmation(user.email, token);
        console.log(`Password reset token: ${token}`);

        return { message: 'Reset link sent to your email if it exists.' };
    }

    async confirmEmail(token: string) {
        const payload = this.jwtService.verify(token);
        const user = await this.userModel.findById(payload.sub);
        if (!user) throw new Error('Invalid token');

        user.isEmailConfirmed = true;
        await user.save();
        return { message: 'Email confirmed.' };
    }

    async logout(user: any) {
        return { message: 'Logged out successfully' };
    }

    async resetPassword(token: string, newPassword: string) {

        const user = await this.usersService.findOne({
            resetToken: token,
            resetTokenExpires: { $gt: Date.now() }
        });

        if (!user) {
            throw new BadRequestException('Invalid or expired reset token');
        }

        const hashedPassword = await bcrypt.hash(newPassword, 10);

        await this.usersService.updatePassword(user._id as string, hashedPassword);
        user.resetToken = null;
        user.resetTokenExpires = null;

        await user.save();

        return { message: 'Password reset successful' };
    }

}
