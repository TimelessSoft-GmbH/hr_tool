import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { VacationRequest, VacationRequestDocument } from '../models/vacation-request.schema';
import { SicknessRequest } from '../models/sickness-request.schema';
import { WorkHour } from '../models/work-hour.schema';
import { Model } from 'mongoose';
import axios from 'axios';
import { User } from 'src/users/user.schema';
import { UsersService } from 'src/users/users.service';

@Injectable()
export class DashboardService {
  constructor(
    @InjectModel(VacationRequest.name) private vacModel: Model<VacationRequestDocument>,
    @InjectModel(SicknessRequest.name) private sickModel: Model<SicknessRequest>,
    @InjectModel(WorkHour.name) private hourModel: Model<WorkHour>,
    @InjectModel(User.name) private userModel: Model<User>,
    private usersService: UsersService,
  ) { }

  async getDashboardData(user: any) {
    const yearRange = {
      startDate: { $gte: `${new Date().getFullYear()}-01-01`, $lte: `${new Date().getFullYear() + 1}-12-31` },
    };
    const isAdmin = user?.roles?.includes('admin');
    const filter = isAdmin ? yearRange : { ...yearRange, userId: user._id };

    const [users, vacationRequests, sicknessRequests] = await Promise.all([
      this.usersService.findAll(),
      this.vacModel.find(filter).lean(),
      this.sickModel.find(filter).lean(),
    ]);

    return { users, vacationRequests, sicknessRequests };
  }

  async storeVacation(userId: string, body: any) {
    const attributes = await this.buildRequestAttributes(userId, body);
    const result = await this.vacModel.create(attributes);
    if (attributes.status === 'pending') {
      await this.sendNotification(attributes, 'Urlaubsantrag');
    }
    return result;
  }

  async storeSickness(userId: string, body: any) {
    const attributes = await this.buildRequestAttributes(userId, body);
    const result = await this.sickModel.create(attributes);
    if (attributes.status === 'pending') {
      await this.sendNotification(attributes, 'Krankheitsurlaub');
    }
    return result;
  }

  async approveVacation(id: string) {
    const request = await this.vacModel.findById(id);
    if (!request) throw new NotFoundException('Vacation request not found');
    if (request.status === 'approved') return request;

    const user = await this.usersService.findById(request.userId);
    const holidays = await this.getPublicHolidayDates(request.startDate);
    const workdaysUsed = this.calculateWorkingDays(request.startDate, request.endDate, user!.workdays, holidays);

    request.status = 'approved';
    await request.save();
    await this.usersService.applyApprovedVacationDay(user!._id!.toString(), workdaysUsed);

    return request;
  }

  async approveSickness(id: string) {
    const result = await this.sickModel.findByIdAndUpdate(id, { status: 'approved' }, { new: true });
    if (!result) throw new NotFoundException('Sickness request not found');
    return result;
  }

  async storeHours(dto: any) {
    const { user_id, year, month, hours } = dto;
    const existing = await this.hourModel.findOne({ userId: user_id, year, month });
    if (existing) {
      existing.hours = hours;
      return existing.save();
    } else {
      return this.hourModel.create({ userId: user_id, year, month, hours });
    }
  }

  async deleteVacation(id: string) {
    return this.vacModel.findByIdAndDelete(id);
  }

  async deleteSickness(id: string) {
    return this.sickModel.findByIdAndDelete(id);
  }

  async getVacations(user: any) {
    const filter = user.roles?.includes('admin') ? {} : { userId: user._id };
    return this.vacModel.find(filter).populate('userId', 'name email').lean();
  }

  async getSicknesses(user: any) {
    const filter = user.roles?.includes('admin') ? {} : { userId: user._id };
    return this.sickModel.find(filter).populate('userId', 'name email').lean();
  }

  async updateVacation(id: string, dto: { startDate: string; endDate: string }) {
    return this.vacModel.findByIdAndUpdate(id, dto, { new: true });
  }

  async updateSickness(id: string, dto: { startDate: string; endDate: string }) {
    return this.sickModel.findByIdAndUpdate(id, dto, { new: true });
  }

  async getRequestNotificationData(userId: string, startDate: string, endDate: string) {
    const [admins, requester] = await Promise.all([
      this.userModel.find({ roles: 'admin' }).exec(),
      this.usersService.findById(userId),
    ]);

    const totalDays = this.calculateCalendarDays(new Date(startDate), new Date(endDate));
    return { admins, requester, totalDays };
  }

  async getApprovalNotificationData(requestId: string, type: 'vacation' | 'sickness') {
    const request = type === 'vacation'
      ? await this.vacModel.findById(requestId)
      : await this.sickModel.findById(requestId);

    if (!request) throw new Error(`${type} request not found`);

    const user = await this.usersService.findById(request.userId);
    if (!user) throw new Error('User not found');

    return {
      userEmail: user.email,
      userName: user.name,
    };
  }

  private async buildRequestAttributes(userId: string, body: any) {
    const { start_date, end_date, status } = body;
    const start = new Date(start_date);
    const end = new Date(end_date);

    const holidays = await this.getPublicHolidayDates(start);
    const user = await this.userModel.findById(userId);
    const workdays = user?.workdays ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    const totalDays = this.calculateWorkingDays(start, end, workdays, holidays);

    return {
      userId,
      startDate: start,
      endDate: end,
      totalDays,
      status: status ?? 'pending',
    };
  }

  async getHolidays(region: string = "AT", year: number = new Date().getFullYear()) {
    try {
      const response = await axios.get(`https://date.nager.at/api/v3/PublicHolidays/${year}/${region}`);
      return response.data.map((holiday: any) => holiday.date);
    } catch (error) {
      console.error('Error fetching holidays:', error);
      return [];
    }
  }

  private async getPublicHolidayDates(date: Date) {
    const year = date.getFullYear();
    const response = await axios.get(`https://date.nager.at/api/v3/PublicHolidays/${year}/AT`);
    return response.data.map((h: any) => h.date);
  }

  private calculateWorkingDays(start: Date, end: Date, workdays: string[], holidays: string[]): number {
    let count = 0;
    for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
      const day = d.toLocaleDateString('en-US', { weekday: 'long' });
      const iso = d.toISOString().split('T')[0];
      if (workdays.includes(day) && !holidays.includes(iso)) {
        count++;
      }
    }
    return count;
  }

  private calculateCalendarDays(start: Date, end: Date) {
    return Math.ceil((end.getTime() - start.getTime()) / (1000 * 60 * 60 * 24)) + 1;
  }

  private async sendNotification(attributes: any, type: string) {
    const admins = await this.userModel.find({ roles: { $in: ['admin'] } });
    const emails = admins.map((a) => a.email);
    // this.mailerService.sendMail(...)
  }
}
