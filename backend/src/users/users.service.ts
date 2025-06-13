import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { User, UserDocument } from './user.schema';
import { FilterQuery, Model } from 'mongoose';
import { UpdateUserDto } from './dto/update-user.dto';
import { UpdatePasswordDto } from './dto/update-password.dto';
import * as bcrypt from 'bcrypt';

@Injectable()
export class UsersService {
  constructor(@InjectModel(User.name) private userModel: Model<UserDocument>) { }

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
    return this.userModel.findByIdAndUpdate(userId, dto, { new: true }).select('-password');
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
}
