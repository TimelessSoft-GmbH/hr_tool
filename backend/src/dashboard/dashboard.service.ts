import { Injectable } from '@nestjs/common';
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
    const currentYear = new Date().getFullYear();
    const nextYear = currentYear + 1;

    const users = await this.usersService.findAll();

    const filter = {
      startDate: { $gte: `${currentYear}-01-01`, $lte: `${nextYear}-12-31` },
    };

    const isAdmin = user?.roles?.includes('admin');

    const vacationRequests = await this.vacModel
      .find(isAdmin ? filter : { ...filter, userId: user._id })
      .lean();

    const sicknessRequests = await this.sickModel
      .find(isAdmin ? filter : { ...filter, userId: user._id })
      .lean();

    return { users, vacationRequests, sicknessRequests };
  }


  async storeVacation(userId: string, body: any) {
    const attributes = await this.getAttributes(userId, body);
    attributes.status = body.status ?? 'pending';
    await this.vacModel.create(attributes);
    if (attributes.status === 'pending') {
      await this.sendNotification(attributes, 'Urlaubsantrag');
    }
  }

  async storeSickness(userId: string, body: any) {
    const attributes = await this.getAttributes(userId, body);
    attributes.status = body.status ?? 'pending';
    await this.sickModel.create(attributes);
    if (attributes.status === 'pending') {
      await this.sendNotification(attributes, 'Krankheitsurlaub');
    }
  }
  async approveVacation(id: string) {
    return this.vacModel.findByIdAndUpdate(id, { status: 'approved' }, { new: true });
  }

  async approveSickness(id: string) {
    return this.sickModel.findByIdAndUpdate(id, { status: 'approved' }, { new: true });
  }

  async getAttributes(userId: string, body: any) {
    const { start_date, end_date } = body;
    const start = new Date(start_date);
    const end = new Date(end_date);

    const year = new Date().getFullYear();
    const holidaysResp = await axios.get(`https://date.nager.at/api/v3/PublicHolidays/${year}/AT`);
    const holidayDates = holidaysResp.data.map((h) => h.date);

    const user = await this.userModel.findById(userId);
    const workdays = user?.workdays ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    let totalDays = 0;
    for (
      let d = new Date(start);
      d <= end;
      d.setDate(d.getDate() + 1)
    ) {
      const dayName = d.toLocaleDateString('en-US', { weekday: 'long' });
      const isoDate = d.toISOString().slice(0, 10);
      if (workdays.includes(dayName) && !holidayDates.includes(isoDate)) {
        totalDays++;
      }
    }

    return {
      userId,
      startDate: start,
      endDate: end,
      totalDays,
      status: 'pending',
    };
  }

  async sendNotification(attributes: any, type: string) {
    const admins = await this.userModel.find({ roles: { $in: ['admin'] } });
    const emails = admins.map((a) => a.email);
    // Use a mailer service here
    // await this.mailerService.sendMail(...)
  }

  async storeHours(dto: any) {
    const { user_id, year, month, hours } = dto;
    const existing = await this.hourModel.findOne({ userId: user_id, year, month });
    if (existing) {
      existing.hours = hours;
      await existing.save();
    } else {
      await this.hourModel.create({ userId: user_id, year, month, hours });
    }
  }

  async deleteVacation(id: string) {
    return this.vacModel.findByIdAndDelete(id);
  }

  async deleteSickness(id: string) {
    return this.sickModel.findByIdAndDelete(id);
  }

  async getVacations(user: any) {
    const isAdmin = user.roles?.includes('admin');

    return this.vacModel
      .find(isAdmin ? {} : { userId: user._id })
      .populate('userId', 'name email')
      .lean();
  }

  async getSicknesses(user: any) {
    const isAdmin = user.roles?.includes('admin');

    return this.sickModel
      .find(isAdmin ? {} : { userId: user._id })
      .populate('userId', 'name email')
      .lean();
  }

  async updateVacation(id: string, dto: { startDate: string; endDate: string }) {
    return this.vacModel.findByIdAndUpdate(
      id,
      {
        startDate: dto.startDate,
        endDate: dto.endDate,
      },
      { new: true }
    );
  }

  async updateSickness(id: string, dto: { startDate: string; endDate: string }) {
    return this.sickModel.findByIdAndUpdate(
      id,
      {
        startDate: dto.startDate,
        endDate: dto.endDate,
      },
      { new: true }
    );
  }


  async getRequestNotificationData(userId: string, startDate: string, endDate: string) {
    const admins = await this.userModel.find({ roles: 'admin' }).exec();
    const requester = await this.usersService.findById(userId);
    const start = new Date(startDate);
    const end = new Date(endDate);
    const totalDays = Math.ceil((end.getTime() - start.getTime()) / (1000 * 3600 * 24)) + 1;

    return { admins, requester, totalDays };
  }

  async getApprovalNotificationData(requestId: string, type: 'vacation' | 'sickness') {
    const request =
      type === 'vacation'
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

}
