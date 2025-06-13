// work-hours.controller.ts
import { Body, Controller, Get, Post, Query, UseGuards } from '@nestjs/common';
import { WorkHoursService } from './work-hours.service';
import { UpdateWorkHourDto } from './dto/update-work-hour.dto';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';

@Controller('work-hours')
@UseGuards(JwtAuthGuard)
export class WorkHoursController {
  constructor(private readonly workHoursService: WorkHoursService) {}

  @Get()
  async getAll(@Query('year') year: string) {
    return this.workHoursService.getAllForYear(parseInt(year));
  }

  @Post('update')
  async update(@Body() dto: UpdateWorkHourDto) {
    return this.workHoursService.updateOrCreate(dto);
  }
}
