import date from 'helper-date';
import config from '../utils/config';

export const hbsHelpers = {
  date,
  webAppBaseUrl: () => config.webAppBaseUrl,
  setVar: (a, b, options) => (options.data.root[a] = b),
  and: (...args) => args.every((i) => !!i),
  add: (a, b) => a + b,
  sub: (a, b) => a - b,
  ex: (a, b) => (a ? a : b),
  nx: (a) => !a,
  ix: (a) => !!a,
  eq: (a, b) => a == b,
  ne: (a, b) => a != b,
  gt: (a, b) => Number(a) > Number(b),
  lt: (a, b) => Number(a) < Number(b),
  ge: (a, b) => Number(a) >= Number(b),
  le: (a, b) => Number(a) <= Number(b),
  now: () => new Date(),
  year: () => new Date().getFullYear(), 
  FloatToStr: (a, b = 'de-DE', c = 'EUR') => Number(a).toLocaleString(b, { style: 'currency', currency: c }),
  co: (a) => Number(a) + 1,
  formatSeconds: (s) => {
    if (s == null) {
      return '-';
    }
    const seconds = Math.floor(s) % 60;
    s = Math.floor(s / 60);
    const minutes = s % 60;
    s = Math.floor(s / 60);
    const hours = s;
    const pad = (i) => ('0' + i).substr(-2);
    return (hours > 0 ? hours + ':' : '') + pad(minutes) + ':' + pad(seconds);
  },
};

