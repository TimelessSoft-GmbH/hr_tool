import { Injectable } from '@nestjs/common';
import { MailerService as BaseMailerService } from '@nestjs-modules/mailer';
import { User } from 'src/users/user.schema';

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

  async sendRequestNotification(admins: User[], requester: User, startDate: string, endDate: string, totalDays: number, type: 'Urlaub' | 'Krankheit') {
    const requestsUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/requests`;

    const html = `
      <div style="font-family: sans-serif; padding: 20px;">
        <h2>Neue ${type}-Anfrage</h2>
        <p>Mitarbeiter: ${requester.name}</p>
        <p>Von: ${startDate}</p>
        <p>Bis: ${endDate}</p>
        <p>Arbeitstage: ${totalDays}</p>
        <p>Bitte überprüfen Sie die Anfrage:</p>
        <p><a href="${requestsUrl}">Zu den Anfragen</a></p>
        <p>Viele Grüße,<br/>Ihr MXR Team</p>
      </div>
    `;

    for (const admin of admins) {
      await this.mailer.sendMail({
        to: admin.email,
        subject: `Neue ${type}-Anfrage von ${requester.name}`,
        html,
      });
    }
  }

  async sendApprovalNotification(email: string, name: string, type: 'Urlaub' | 'Krankheit') {
    const dashboardUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/dashboard`;

    const html = `
      <div style="font-family: sans-serif; padding: 20px;">
        <h2>Ihre ${type}-Anfrage wurde genehmigt</h2>
        <p>Hallo ${name},</p>
        <p>Ihre ${type}-Anfrage wurde genehmigt.</p>
        <p>Sie können die Details in Ihrem Dashboard einsehen:</p>
        <p><a href="${dashboardUrl}">Zum Dashboard</a></p>
        <p>Viele Grüße,<br/>Ihr MXR Team</p>
      </div>
    `;

    await this.mailer.sendMail({
      to: email,
      subject: `${type}-Genehmigung`,
      html,
    });
  }

  async sendAccountCreated(email: string, name: string, password: string, token: string) {
    const confirmUrl = `${process.env.WEB_APP_BASE_URL ?? 'http://localhost:3000'}/confirm-email/${token}`;
    const html = `
    <div style="font-family: sans-serif; padding: 20px;">
      <h2>Willkommen bei MXR</h2>
      <p>Hallo ${name},</p>
      <p>Ein Benutzerkonto wurde für dich erstellt.</p>
      <p><strong>Passwort:</strong> ${password}</p>
      <p>Bitte bestätige deine E-Mail-Adresse:</p>
      <p><a href="${confirmUrl}">E-Mail bestätigen</a></p>
      <p>Viele Grüße,<br/>Dein MXR Team</p>
    </div>
  `;

    await this.mailer.sendMail({
      to: email,
      subject: 'MXR Benutzerkonto erstellt',
      html,
    });
  }
}
