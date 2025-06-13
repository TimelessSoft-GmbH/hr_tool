import {
    Controller, Get, Post, Body, Req, Delete, Param,
} from '@nestjs/common';
import { DashboardService } from './dashboard.service';
import { Request } from 'express';
import { WorkHoursDto } from './dto/work-hours.dto';
import { CreateVacationDto } from './dto/create-vacation.dto';
import { CreateSicknessDto } from './dto/create-sickness.dto';

@Controller('dashboard')
export class DashboardController {
    constructor(private readonly dashboardService: DashboardService) { }

    @Get()
    async getDashboard(@Req() req: Request) {
        return this.dashboardService.getDashboardData(req.user['id']);
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

    @Get('vacations')
    async getVacations(@Req() req: Request) {
        return this.dashboardService.getVacations(req.user);
    }

    @Get('sicknesses')
    async getSicknesses(@Req() req: Request) {
        return this.dashboardService.getSicknesses(req.user);
    }

}
