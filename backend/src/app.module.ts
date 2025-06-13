import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import config from './utils/config';
import { MongooseModule } from '@nestjs/mongoose';
import { MailerModule } from '@nestjs-modules/mailer';
import { AuthModule } from './auth/auth.module';
import { PassportModule } from '@nestjs/passport';
import { DashboardModule } from './dashboard/dashboard.module';
import { WorkHoursModule } from './work-hours/work-hours.module';

const smtpConfig = {
  host: config.smtpHost,
  port: config.smtpPort,
  secure: config.smtpSecure,
  auth: {
    user: config.smtpUsername,
    pass: config.smtpPassword,
  },
  tls: {
    rejectUnauthorized: false,
  },
};
console.log(smtpConfig);

@Module({
  imports: [
    PassportModule,
    MongooseModule.forRoot(config.dbConnectionString),
    MailerModule.forRoot({
      transport: {
        host: config.smtpHost,
        port: config.smtpPort,
        secure: config.smtpSecure,
        ...(config.smtpUsername
          ? {
            auth: {
              user: config.smtpUsername,
              pass: config.smtpPassword,
            },
          }
          : {}),
        tls: { rejectUnauthorized: false },
      },
      defaults: {
        from: config.emailDefaultFrom,
      },
    }),
    AuthModule,
    DashboardModule,
    WorkHoursModule
  ],
  controllers: [AppController],
  providers: [AppService],
})
export class AppModule { }
