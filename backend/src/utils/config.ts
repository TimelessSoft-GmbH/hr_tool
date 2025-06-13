const config = {
  nodeEnv: process.env.NODE_ENV || 'development',

  dbDebug: process.env.DB_DEBUG === 'true',
  applicationPort: parseInt(process.env.APPLICATION_PORT ?? '') || 5005,
  dbConnectionString:
    process.env.MONGODB_URI || 'mongodb://localhost:27017/tlsoft_hr',
  emailDefaultFrom: process.env.EMAIL_DEFAULT_FROM!,
  emailBCC: process.env.EMAIL_BCC!,
  smtpHost: process.env.SMTP_SERVICE_HOST!,
  smtpPort: parseInt(process.env.SMTP_SERVICE_PORT!),
  smtpSecure: process.env.SMTP_SERVICE_SECURE === 'true', 
  smtpUsername: process.env.SMTP_USER_NAME!,
  smtpPassword: process.env.SMTP_USER_PASSWORD!,
};


export default config;
