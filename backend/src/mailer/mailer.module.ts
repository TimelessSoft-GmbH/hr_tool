import { MailerModule as BaseMailerModule } from '@nestjs-modules/mailer';
import { Module } from '@nestjs/common';
import { MailerService } from './mailer.service';

@Module({
  imports: [
    BaseMailerModule.forRoot({
      transport: {
        host: 'mailhog',
        port: 1025,
      },
      defaults: {
        from: '"No Reply" <noreply@example.com>',
      },
    }),
  ],
  providers: [MailerService],
  exports: [MailerService],
})
export class MailerModule {}
