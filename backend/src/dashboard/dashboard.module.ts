import { Module, forwardRef } from '@nestjs/common';
import { MongooseModule } from '@nestjs/mongoose';

import { DashboardController } from './dashboard.controller';
import { DashboardService } from './dashboard.service';

import { VacationRequest, VacationRequestSchema } from '../models/vacation-request.schema';
import { SicknessRequest, SicknessRequestSchema } from '../models/sickness-request.schema';
import { WorkHour, WorkHourSchema } from '../models/work-hour.schema';
import { User, UserSchema } from 'src/users/user.schema';
import { UsersService } from 'src/users/users.service';
import { MailerModule } from 'src/mailer/mailer.module';
import { AuthModule } from 'src/auth/auth.module'; 
import { BlobStorageModule } from 'src/blob-storage/blob-storage.module';

@Module({
  imports: [
    MailerModule,
    BlobStorageModule,
    forwardRef(() => AuthModule), 
    MongooseModule.forFeature([
      { name: VacationRequest.name, schema: VacationRequestSchema },
      { name: SicknessRequest.name, schema: SicknessRequestSchema },
      { name: WorkHour.name, schema: WorkHourSchema },
      { name: User.name, schema: UserSchema }
    ]),
  ],
  controllers: [DashboardController],
  providers: [DashboardService, UsersService],
  exports: [DashboardService],
})
export class DashboardModule {}
