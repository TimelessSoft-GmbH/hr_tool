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

@Controller('dashboard')
export class DashboardController {
    constructor(private readonly dashboardService: DashboardService) { }

    @Get()
    @UseGuards(JwtAuthGuard)
    async getDashboard(@Req() req: Request) {
        const user = req.user as any;
        return this.dashboardService.getDashboardData(user);
    }

    @Post('vacation')
    async storeVacation(@Body() dto: CreateVacationDto) {
        return this.dashboardService.storeVacation(dto.user_id, dto);
    }

    @Post('sickness')
    async storeSickness(@Body() dto: CreateSicknessDto) {
        return this.dashboardService.storeSickness(dto.user_id, dto);
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
        return this.dashboardService.approveVacation(id);
    }

    @Patch('sickness/:id/approve')
    async approveSickness(@Param('id') id: string) {
        return this.dashboardService.approveSickness(id);
    }
}
