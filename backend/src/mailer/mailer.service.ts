import { Injectable } from '@nestjs/common';
import { MailerService as BaseMailerService } from '@nestjs-modules/mailer';

@Injectable()
export class MailerService {
  constructor(private readonly mailer: BaseMailerService) { }

  async sendResetPassword(email: string, token: string, name?: string) {
    const resetUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/reset-password/${token}`;

    const html = `
      <div style="font-family: sans-serif; padding: 20px;">
        <h2>Passwort zurücksetzen</h2>
        <p>Hallo ${name ?? 'Sportsfreund'},</p>
        <p>Du kannst dein Passwort mit folgendem Link zurücksetzen:</p>
        <p><a href="${resetUrl}">Passwort ändern</a></p>
        <p>Viele Grüße,<br/>Dein MXR Team</p>
      </div>
    `;

    await this.mailer.sendMail({
      to: email,
      subject: 'Passwort zurücksetzen',
      html,
    });
  }

  async sendEmailConfirmation(email: string, token: string, name?: string) {
    const confirmUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/confirm-email/${token}`;

    const html = `
      <div style="font-family: sans-serif; padding: 20px;">
        <h2>E-Mail bestätigen</h2>
        <p>Hallo ${name ?? 'Sportsfreund'},</p>
        <p>Bitte bestätige deine E-Mail-Adresse mit folgendem Link:</p>
        <p><a href="${confirmUrl}">E-Mail bestätigen</a></p>
        <p>Viele Grüße,<br/>Dein MXR Team</p>
      </div>
    `;

    await this.mailer.sendMail({
      to: email,
      subject: 'E-Mail bestätigen',
      html,
    });
  }

  async sendVacationNotification(userId: string, startDate: string, endDate: string, totalDays: number, type: string) {
  //   const admins = await db.user.findMany({
  //     where: { roles: { some: { name: 'admin' } } },
  //   });

  //   const user = await db.user.findUnique({ where: { id: userId } });

  //   const html = `
  //   <div style="font-family: sans-serif; padding: 20px;">
  //     <h2>${type}</h2>
  //     <p>User: ${user.name}</p>
  //     <p>Von: ${startDate}</p>
  //     <p>Bis: ${endDate}</p>
  //     <p>Arbeitstage: ${totalDays}</p>
  //   </div>
  // `;

  //   for (const admin of admins) {
  //     await this.mailer.sendMail({
  //       to: admin.email,
  //       subject: type,
  //       html,
  //     });
  //   }
  }

}
