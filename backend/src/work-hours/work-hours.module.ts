import { Module } from '@nestjs/common';
import { MongooseModule } from '@nestjs/mongoose';
import { WorkHoursService } from './work-hours.service';
import { WorkHoursController } from './work-hours.controller';
import { UsersModule } from '../users/users.module';
import { WorkHour, WorkHourSchema } from 'src/models/work-hour.schema';

@Module({
  imports: [
    MongooseModule.forFeature([{ name: WorkHour.name, schema: WorkHourSchema }]),
    UsersModule,
  ],
  controllers: [WorkHoursController],
  providers: [WorkHoursService],
})
export class WorkHoursModule {}
