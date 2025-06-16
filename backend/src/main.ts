
import 'dotenv/config';
import { ValidationPipe } from '@nestjs/common';
import { NestFactory } from '@nestjs/core';
import { NestExpressApplication } from '@nestjs/platform-express';
import { DocumentBuilder, SwaggerModule } from '@nestjs/swagger';
import { AppModule } from './app.module';
import config from './utils/config';

async function bootstrap() {
  const app = await NestFactory.create<NestExpressApplication>(AppModule, {
    rawBody: true,
  });

  app.useBodyParser('raw', { limit: '5mb', type: 'image/*' });

  app.enableCors({
    origin: true,
    credentials: true,
  });

  app.useGlobalPipes(
    new ValidationPipe({
      transform: true,
      whitelist: true,
      forbidNonWhitelisted: false,
    }),
  );

  if (config.nodeEnv === 'development') {
    const swaggerConfig = new DocumentBuilder()
      .setTitle('TLSoft HR Platform API')
      .setDescription('API for the TLSoft HR Platform-App backend')
      .setVersion('1.0')
      .addBearerAuth() 
      .build();

    const document = SwaggerModule.createDocument(app, swaggerConfig);
    SwaggerModule.setup('swagger', app, document);
  }

  await app.listen(config.applicationPort);
  console.log(`🚀 Listening on http://localhost:${config.applicationPort}`);
  if (config.nodeEnv === 'development') {
    console.log(`📚 Swagger docs at http://localhost:${config.applicationPort}/swagger`);
  }
}

void bootstrap();
