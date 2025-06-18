import * as fs from 'fs';
import * as hbs from 'handlebars';
import { createTransport } from 'nodemailer';
import mailhbs from 'nodemailer-express-handlebars';
import config from 'src/utils/config';
import { hbsHelpers } from 'src/views/helpers';

const hbsRootDir = __dirname + '/../views';
const partialsDir = hbsRootDir + '/partials';

const filenames = fs.readdirSync(partialsDir);
filenames.forEach(function (filename) {
  const matches = /^([^.]+).hbs$/.exec(filename);
  if (!matches) {
    return;
  }
  const name = matches[1];
  const template = fs.readFileSync(partialsDir + '/' + filename, 'utf8');
  hbs.registerPartial(name, template);
});

export const SMTPTransport = createTransport({
  host: config.smtpHost,
  port: config.smtpPort,
  secure: config.smtpSecure,
  ignoreTLS: config.smtpIgnoreTLS,
  ...(config.smtpUsername && config.smtpPassword
    ? {
        auth: {
          user: config.smtpUsername,
          pass: config.smtpPassword,
        },
      }
    : {}),
});

SMTPTransport.use(
  'compile',
  mailhbs({
    viewEngine: {
      helpers: hbsHelpers,
      layoutsDir: hbsRootDir + '/email',
      partialsDir: partialsDir,
      defaultLayout: '',
    },
    viewPath: hbsRootDir + '/email',
    extName: '.hbs',
  }),
);
