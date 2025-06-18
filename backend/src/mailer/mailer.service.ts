import { Injectable, Logger, OnApplicationBootstrap } from '@nestjs/common';
import { MailerService as BaseMailerService } from '@nestjs-modules/mailer';
import { User } from 'src/users/user.schema';
import config from 'src/utils/config';
import { SMTPTransport } from './smtp-transport';

const defaultOptions = {
  from: config.emailDefaultFrom,
};


@Injectable()
export class MailerService implements OnApplicationBootstrap {
  private readonly logger = new Logger(MailerService.name);

  constructor(private readonly mailer: BaseMailerService) { }

  async onApplicationBootstrap(): Promise<void> {
    await this.checkConnection();
  }

  private async checkConnection(): Promise<void> {
    this.logger.log('Verifying connection to SMTP server...');
    return new Promise<void>((resolve, reject) => {
      SMTPTransport.verify((error) => {
        if (error) {
          this.logger.error('Connection to SMTP server failed', error.stack);
          reject(error);
        } else {
          this.logger.log('Connection to SMTP server successfully verified');
          resolve();
        }
      });
    });
  }

  async sendMail(options): Promise<void> {
    return new Promise<void>((resolve, reject) => {
      const mergedOptions = Object.assign({}, defaultOptions, options);

      if (!mergedOptions.to) {
        throw new Error('Missing sendMail argument: options.to');
      }
      if (!mergedOptions.subject) {
        throw new Error('Missing sendMail argument: options.subject');
      }
      if (!mergedOptions.template && !mergedOptions.text) {
        throw new Error('Missing sendMail argument: options.template|options.text');
      }

      if (!mergedOptions.template && mergedOptions.text) {
        mergedOptions.template = 'blank';
        mergedOptions.context = { text: mergedOptions.text };
      }

      const onError = (error: Error) => {
        this.logger.error('ERROR sending mail ', mergedOptions.subject, ' to:', mergedOptions.to);
        reject(error);
      };

      try {
        SMTPTransport.verify((error) => {
          if (error) {
            onError(error);
          } else {
            SMTPTransport.sendMail(mergedOptions, (error, info /* SentMessageInfo */) => {
              if (error) {
                onError(error);
              } else {
                this.logger.log('Mail sent ', mergedOptions.subject, ' to:', mergedOptions.to, ' SentMessageInfo: ', info);
                resolve();
              }
            });
          }
        });
      } catch (error) {
        onError(error);
      }
    });
  }
  async sendResetPassword(email: string, token: string, name: string = 'Sportsfreund'): Promise<void> {
    const resetUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/reset-password/${token}`;
    await this.sendMail({
      to: email,
      subject: 'Passwort zurucksetzen',
      template: 'resetPassword',
      context: { name, resetUrl },
    });
  }

  async sendEmailConfirmation(email: string, token: string, name: string = 'Sportsfreund'): Promise<void> {
    const confirmUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/confirm-email/${token}`;
    await this.sendMail({
      to: email,
      subject: 'E-Mail bestätigen',
      template: 'email-confirmation',
      context: { name, confirmUrl },
    });
  }

  async sendRequestNotification(admins: User[], requester: User, startDate: string, endDate: string, totalDays: number, type: 'Urlaub' | 'Krankheit'): Promise<void> {
    const requestsUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/requests`;
    for (const admin of admins) {
      await this.sendMail({
        to: admin.email,
        subject: `Neue ${type}-Anfrage von ${requester.name}`,
        template: 'request-notification',
        context: { type, requesterName: requester.name, startDate, endDate, totalDays, requestsUrl },
      });
    }
  }

  async sendApprovalNotification(email: string, name: string, type: 'Urlaub' | 'Krankheit'): Promise<void> {
    const dashboardUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/dashboard`;
    await this.sendMail({
      to: email,
      subject: `${type}-Genehmigung`,
      template: 'approval-notification',
      context: { name, type, dashboardUrl },
    });
  }

  async sendAccountCreated(email: string, name: string, password: string, token: string): Promise<void> {
    const confirmUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/confirm-email/${token}`;
    await this.sendMail({
      to: email,
      subject: 'TLSoft HR Platform Benutzerkonto erstellt',
      template: 'account-created',
      context: { name, password, confirmUrl },
    });
  }

  async sendCustomEmail(to: string, subject: string, text?: string, html?: string): Promise<void> {
    await this.sendMail({
      to,
      subject,
      text,
      html,
    });
  }
}
