import { Body, Controller, Delete, ForbiddenException, Get, Param, Patch, Post, Put, Req, UploadedFile, UseGuards, UseInterceptors } from '@nestjs/common';
import { Request } from 'express';
import { UsersService } from './users.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';
import { UpdateUserDto } from './dto/update-user.dto';
import { UpdatePasswordDto } from './dto/update-password.dto';
import { FileInterceptor } from '@nestjs/platform-express';
import { Express } from 'express';
import { User } from './user.schema';

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

  @Post(':id/upload')
  @UseGuards(JwtAuthGuard)
  @UseInterceptors(FileInterceptor('file'))
  async uploadUserFile(
    @Param('id') id: string,
    @UploadedFile() file: Express.Multer.File,
  ) {
    return { success: true, filename: file.filename };
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

  // @Post('image')
  // @UseInterceptors(
  //   FileInterceptor('image', {
  //     storage: diskS({
  //       destination: './public/images',
  //       filename: (req, file, cb) => {
  //         const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1e9);
  //         cb(null, uniqueSuffix + path.extname(file.originalname));
  //       },
  //     }),
  //   }),
  // )
  // async uploadImage(@Req() req, @UploadedFile() file: Express.Multer.File) {
  //   return this.usersService.updateProfileImage(req.user.sub, file.filename);
  // }

  @Delete()
  @UseGuards(JwtAuthGuard)
  async deleteAccount(@Req() req: Request, @Body('password') password: string) {
    return this.usersService.deleteAccount(req.user, password);
  }
}
