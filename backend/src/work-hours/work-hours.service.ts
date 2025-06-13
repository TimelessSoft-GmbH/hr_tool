import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { Model } from 'mongoose';
import { UpdateWorkHourDto } from './dto/update-work-hour.dto';
import { UsersService } from '../users/users.service';
import { WorkHour } from 'src/models/work-hour.schema';

@Injectable()
export class WorkHoursService {
  constructor(
    @InjectModel(WorkHour.name) private workHourModel: Model<WorkHour>,
    private usersService: UsersService,
  ) {}

  async getAllForYear(year: number) {
    const users = await this.usersService.findAll();
    const workHours = await this.workHourModel.find({ year }).lean();
    return { users, workHours };
  }

  async updateOrCreate(dto: UpdateWorkHourDto) {
    return this.workHourModel.findOneAndUpdate(
      { user_id: dto.user_id, year: dto.year, month: dto.month },
      { $set: { hours: dto.hours } },
      { upsert: true, new: true }
    );
  }
}
