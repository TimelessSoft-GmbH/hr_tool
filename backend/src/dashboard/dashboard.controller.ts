import {
    Controller, Get, Post, Body, Req, Delete, Param,
    Patch,
    UseGuards,
} from '@nestjs/common';
import { DashboardService } from './dashboard.service';
import { Request } from 'express';
import { WorkHoursDto } from './dto/work-hours.dto';
import { CreateVacationDto } from './dto/create-vacation.dto';
import { CreateSicknessDto } from './dto/create-sickness.dto';
import { JwtAuthGuard } from 'src/auth/jwt-auth.guard';
import { MailerService } from 'src/mailer/mailer.service';

@Controller('dashboard')
export class DashboardController {
    constructor(
        private readonly dashboardService: DashboardService,
        private readonly mailerService: MailerService,
    ) { }

    @Get()
    @UseGuards(JwtAuthGuard)
    async getDashboard(@Req() req: Request) {
        const user = req.user as any;
        return this.dashboardService.getDashboardData(user);
    }

    @Post('vacation')
    @UseGuards(JwtAuthGuard)
    async storeVacation(@Req() req: any, @Body() dto: CreateVacationDto) {
        const result = await this.dashboardService.storeVacation(req.user.id, dto);
        const { admins, requester, totalDays } = await this.dashboardService.getRequestNotificationData(req.user.id, dto.start_date, dto.end_date);
        if (admins && requester) {
            await this.mailerService.sendRequestNotification(
                admins,
                requester,
                dto.start_date,
                dto.end_date,
                totalDays,
                'Urlaub'
            );
        }

        return result;
    }

    @Post('sickness')
    @UseGuards(JwtAuthGuard)
    async storeSickness(@Req() req: any, @Body() dto: CreateSicknessDto) {
        const result = await this.dashboardService.storeSickness(req.user.id, dto);
        const { admins, requester, totalDays } = await this.dashboardService.getRequestNotificationData(req.user.id, dto.start_date, dto.end_date);

        if (admins && requester) {
            await this.mailerService.sendRequestNotification(
                admins,
                requester,
                dto.start_date,
                dto.end_date,
                totalDays,
                'Krankheit'
            );
        }

        return result;
    }

    @Post('hours')
    async storeHours(@Body() dto: WorkHoursDto) {
        return this.dashboardService.storeHours(dto);
    }

    @Delete('vacation/:id')
    async deleteVacation(@Param('id') id: string) {
        return this.dashboardService.deleteVacation(id);
    }

    @Delete('sickness/:id')
    async deleteSickness(@Param('id') id: string) {
        return this.dashboardService.deleteSickness(id);
    }

    @UseGuards(JwtAuthGuard)
    @Get('vacations')
    getVacations(@Req() req: any) {
        return this.dashboardService.getVacations(req.user);
    }

    @UseGuards(JwtAuthGuard)
    @Get('sicknesses')
    getSicknesses(@Req() req: any) {
        return this.dashboardService.getSicknesses(req.user);
    }

    @Patch('vacation/:id/approve')
    async approveVacation(@Param('id') id: string) {
        const result = await this.dashboardService.approveVacation(id);
        const { userEmail, userName } = await this.dashboardService.getApprovalNotificationData(id, 'vacation');
        if (userEmail && userName) {
            await this.mailerService.sendApprovalNotification(
                userEmail,
                userName,
                'Urlaub'
            );
        }
        return result;
    }

    @Patch('sickness/:id/approve')
    async approveSickness(@Param('id') id: string) {
        const result = await this.dashboardService.approveSickness(id);
        const { userEmail, userName } = await this.dashboardService.getApprovalNotificationData(id, 'sickness');
        if (userEmail && userName) {
            await this.mailerService.sendApprovalNotification(
                userEmail,
                userName,
                'Krankheit'
            );
        }
        return result;
    }

    @Patch('vacation/:id')
    async updateVacation(
        @Param('id') id: string,
        @Body() dto: { startDate: string; endDate: string }
    ) {
        return this.dashboardService.updateVacation(id, dto);
    }

    @Patch('sickness/:id')
    async updateSickness(
        @Param('id') id: string,
        @Body() dto: { startDate: string; endDate: string }
    ) {
        return this.dashboardService.updateSickness(id, dto);
    }

    @Get('holidays/:region/:year')
    async getHolidays(
        @Param('region') region: string,
        @Param('year') year: string
    ) {
        return this.dashboardService.getHolidays(region, parseInt(year));
    }
}