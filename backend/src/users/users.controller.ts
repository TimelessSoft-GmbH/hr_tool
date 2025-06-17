import { BadRequestException, Body, Controller, Delete, ForbiddenException, Get, HttpCode, Param, Patch, Post, Put, RawBodyRequest, Req, Res, UploadedFile, UseGuards, UseInterceptors } from '@nestjs/common';
import { UsersService } from './users.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';
import { UpdateUserDto } from './dto/update-user.dto';
import { UpdatePasswordDto } from './dto/update-password.dto';
import { FileInterceptor } from '@nestjs/platform-express';
import { Express } from 'express';
import { User } from './user.schema';
import { ApiBearerAuth, ApiBody, ApiConsumes } from '@nestjs/swagger';
import { Request, Response } from 'express';

@Controller('users')
export class UsersController {
  constructor(private usersService: UsersService) { }

  @Get('me')
  @UseGuards(JwtAuthGuard)
  async getMe(@Req() req: Request) {
    const user = req.user as any;
    return this.usersService.findById(user._id);
  }
  @Get()
  async getAllUsers(@Req() req: Request) {
    return this.usersService.findAll();
  }

  @Delete(':id')
  @UseGuards(JwtAuthGuard)
  async deleteUser(@Param('id') id: string, @Req() req: Request) {
    const user = req.user as any;

    if (!user?.roles?.includes('admin')) {
      throw new ForbiddenException('Only admins can delete users');
    }

    return this.usersService.deleteById(id);
  }

  @Patch()
  @UseGuards(JwtAuthGuard)
  async updateProfile(@Req() req: Request, @Body() dto: UpdateUserDto) {
    return this.usersService.updateProfile(req.user, dto);
  }

  @Patch(':id')
  @UseGuards(JwtAuthGuard)
  async updateUserById(
    @Param('id') id: string,
    @Req() req: Request,
    @Body() dto: UpdateUserDto,
  ) {
    const requestingUser = req.user as any;
    if (!requestingUser?.roles?.includes('admin')) {
      throw new ForbiddenException('Only admins can update other users');
    }
    return this.usersService.updateProfile(id, dto);
  }


  @Put('password')
  @UseGuards(JwtAuthGuard)
  async updatePassword(@Req() req: Request, @Body() dto: UpdatePasswordDto) {
    return this.usersService.changePassword(req.user, dto);
  }

  @Get(':id')
  @UseGuards(JwtAuthGuard)
  async getUserById(@Param('id') id: string, @Req() req: Request) {
    const requestingUser = req.user as any;
    if (!requestingUser?.roles?.includes('admin')) {
      throw new ForbiddenException('Only admins can fetch other users');
    }

    return this.usersService.findById(id);
  }

  @Post()
  @UseGuards(JwtAuthGuard)
  async createUser(@Req() req: Request, @Body() dto: Partial<User>) {
    const requestingUser = req.user as any;
    if (!requestingUser?.roles?.includes('admin')) {
      throw new ForbiddenException('Only admins can create users');
    }

    return this.usersService.createUser(dto);
  }

  @UseGuards(JwtAuthGuard)
  @Get('me/image')
  @ApiBearerAuth()
  async getMeImage(@Req() req: Request, @Res() res: Response): Promise<Response> {
    const image = await this.usersService.getImage(req.user!._id);
    if (!image) {
      return res.status(204).send();
    }
    return res.status(200).contentType(image.mimeType).send(image.data);
  }

  @UseGuards(JwtAuthGuard)
  @Put('me/image')
  @HttpCode(204)
  @ApiBearerAuth()
  @ApiConsumes('image/jpeg')
  @ApiConsumes('image/png')
  @ApiBody({ type: Buffer })
  async setMeImage(@Req() req: RawBodyRequest<Request>): Promise<void> {
    const contentType = req.headers['content-type'];
    if (!contentType?.startsWith('image/')) {
      throw new BadRequestException('Only Content-Type: image/* accepted.');
    }
    if (!req.rawBody?.length) {
      throw new BadRequestException('No content sent.');
    }

    await this.usersService.setImage(req.user!._id, req.rawBody, contentType);
  }

  @Get(':id/image')
  async getImage(@Param('id') id: string, @Res() res: Response): Promise<Response> {
    const image = await this.usersService.getImage(id);
    if (!image) {
      return res.status(204).send();
    }
    return res.status(200).contentType(image.mimeType).send(image.data);
  }

  @Delete()
  @UseGuards(JwtAuthGuard)
  async deleteAccount(@Req() req: Request, @Body('password') password: string) {
    return this.usersService.deleteAccount(req.user, password);
  }

  @Post(':id/contract')
  @UseGuards(JwtAuthGuard)
  @UseInterceptors(FileInterceptor('file'))
  async uploadContractPdf(
    @Param('id') id: string,
    @UploadedFile() file: Express.Multer.File,
  ) {
    if (!file || !file.buffer) {
      throw new BadRequestException('No file received.');
    }

    return this.usersService.setContractPdf(id, file.buffer, file.mimetype);
  }

  @Get(':id/contract')
  @UseGuards(JwtAuthGuard)
  async downloadContractPdf(@Param('id') id: string, @Res() res: Response) {
    const blob = await this.usersService.getContractPdf(id);
    if (!blob) return res.status(204).send();
    return res.status(200).contentType(blob.mimeType).send(blob.data);
  }
}
