import { BadRequestException, forwardRef, Inject, Injectable, NotFoundException } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { User, UserDocument } from './user.schema';
import { FilterQuery, Model, Types } from 'mongoose';
import { UpdateUserDto } from './dto/update-user.dto';
import { UpdatePasswordDto } from './dto/update-password.dto';
import * as bcrypt from 'bcrypt';
import axios from 'axios';
import { MailerService } from 'src/mailer/mailer.service';
import { AuthService } from 'src/auth/auth.service';
import { Blob, BlobWithoutData } from 'src/blob-storage/schema/blob.schema';
import { oid } from 'src/utils/mongoose';
import { BlobStorageService } from 'src/blob-storage/blob-storage.service';

const USER_IMAGE_BLOB_NAME = 'user-image-blob-name';
const USER_CONTRACT_BLOB_NAME = 'user-contract-pdf';

function generateRandomPassword(length = 12): string {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%&*';
  return Array.from({ length }, () => chars.charAt(Math.floor(Math.random() * chars.length))).join('');
}

@Injectable()
export class UsersService {
  constructor(@InjectModel(User.name) private userModel: Model<UserDocument>, private readonly mailerService: MailerService, @Inject(forwardRef(() => AuthService)) private readonly authService: AuthService, private readonly blobStorageService: BlobStorageService) { }

  async findOne(filter: FilterQuery<User>): Promise<User | null> {
    return this.userModel.findOne(filter).exec();
  }

  async findById(id: string): Promise<User | null> {
    return this.userModel.findById(id).select('-password');
  }

  async findByEmail(email: string): Promise<User | null> {
    return this.userModel.findOne({ email }).exec();
  }

  async updatePassword(userId: string, hashedPassword: string): Promise<User | null> {
    return this.userModel.findByIdAndUpdate(
      userId,
      { password: hashedPassword },
      { new: true },
    );
  }

  async findAll() {
    return this.userModel.find().lean();
  }

  async deleteById(id: string) {
    return this.userModel.findByIdAndDelete(id);
  }

  async updateProfile(userId: string, dto: UpdateUserDto) {
    return this.userModel
      .findByIdAndUpdate(userId, { $set: dto }, { new: true })
      .select('-password');
  }

  async changePassword(userId: string, dto: UpdatePasswordDto) {
    const user = await this.userModel.findById(userId);
    if (!user) {
      throw new NotFoundException('User not found');
    }

    const match = await bcrypt.compare(dto.current_password, user.password);
    if (!match) throw new BadRequestException({ current_password: ['Password is incorrect'] });

    if (dto.password !== dto.password_confirmation) {
      throw new BadRequestException({ password_confirmation: ['Passwords do not match'] });
    }

    user.password = await bcrypt.hash(dto.password, 10);
    await user.save();
    return { message: 'Password updated' };
  }

  async updateProfileImage(userId: string, filename: string) {
    return this.userModel.findByIdAndUpdate(userId, { image: filename }, { new: true }).select('-password');
  }

  async deleteAccount(userId: string, password: string) {
    const user = await this.userModel.findById(userId);
    if (!user) {
      throw new NotFoundException('User not found');
    }

    const match = await bcrypt.compare(password, user.password);
    if (!match) throw new BadRequestException({ password: ['Password is incorrect'] });

    await this.userModel.findByIdAndDelete(userId);
    return { message: 'Account deleted' };
  }

  async getWorkingDays(user: User, month: number) {
    const year = new Date().getFullYear();
    const response = await axios.get(`https://date.nager.at/api/v3/PublicHolidays/${year}/AT`);
    const holidays = response.data.map(h => h.date);
    let workingDays = 0;
    const totalDays = new Date(year, month, 0).getDate();
    for (let day = 1; day <= totalDays; day++) {
      const date = new Date(year, month - 1, day);
      const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
      const dateStr = date.toISOString().split('T')[0];
      if (user.workdays.includes(dayName) && !holidays.includes(dateStr)) {
        workingDays++;
      }
    }
    return workingDays;
  }

  async getWorkingHours(user: User, month: number) {
    const workingDays = await this.getWorkingDays(user, month);
    const averagePerDay = user.hours_per_week / user.workdays.length;
    return workingDays * averagePerDay;
  }

  async applyApprovedVacationDay(userId: string, daysUsed: number = 1) {
    const user = await this.userModel.findById(userId);
    if (!user) {
      throw new NotFoundException('User not found');
    }
    user.vacationDays_left = (user.vacationDays_left ?? user.vacationDays ?? 0) - daysUsed;
    await user.save();
    return user;
  }

  async createUser(dto: Partial<User>): Promise<Record<string, any>> {
    const existing = await this.userModel.findOne({ email: dto.email });
    if (existing) {
      throw new BadRequestException('User with this email already exists');
    }

    const randomPassword = generateRandomPassword();
    const hashedPassword = await bcrypt.hash(randomPassword, 10);

    const createdUser = new this.userModel({
      ...dto,
      password: hashedPassword,
    });

    await createdUser.save();
    const token = await this.authService.createEmailToken(createdUser._id as any);
    await this.mailerService.sendAccountCreated(
      createdUser.email,
      createdUser.name,
      randomPassword,
      token,
    );
    const userObj = createdUser.toObject() as any;
    delete userObj.password;
    return userObj;
  }
  async setImage(userId: string | Types.ObjectId, imageData: Buffer, mimeType: string): Promise<BlobWithoutData> {
    return this.blobStorageService.store({
      ref: oid(userId),
      refCollection: User.name,
      name: USER_IMAGE_BLOB_NAME,
      data: imageData,
      mimeType,
      multiple: false,
    });
  }

  async getImage(userId: string | Types.ObjectId): Promise<Blob | null> {
    return this.blobStorageService.retrieveOne(
      {
        ref: oid(userId),
        refCollection: User.name,
        name: USER_IMAGE_BLOB_NAME,
      },
      true,
    );
  }

  async setContractPdf(
    userId: string | Types.ObjectId,
    pdfData: Buffer,
    mimeType: string,
  ): Promise<BlobWithoutData> {
    if (!mimeType.startsWith('application/pdf')) {
      throw new BadRequestException('Only PDF files are allowed');
    }

    if (pdfData.length > 10 * 1024 * 1024) {
      throw new BadRequestException('PDF too large. Maximum size is 10MB.');
    }


    return this.blobStorageService.store({
      ref: oid(userId),
      refCollection: User.name,
      name: USER_CONTRACT_BLOB_NAME,
      data: pdfData,
      mimeType,
      multiple: false,
    });
  }

  async getContractPdf(userId: string | Types.ObjectId): Promise<Blob | null> {
    return this.blobStorageService.retrieveOne(
      {
        ref: oid(userId),
        refCollection: User.name,
        name: USER_CONTRACT_BLOB_NAME,
      },
      true,
    );
  }

}
