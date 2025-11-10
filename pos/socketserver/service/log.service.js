const dateFormat = require('dateformat');
const path = require('path');
const winston = require('winston');
const winstonDailyRotateFile = require('winston-daily-rotate-file');

// START LOGS
// START CONSTRUCTION

console.log(dateFormat(new Date(), 'yyyy-mm-dd # HH:MM:ss'));
const yyyy = dateFormat(new Date(), 'yyyy');
const mm = dateFormat(new Date(), 'mm');
const dd = dateFormat(new Date(), 'dd');
const hh = dateFormat(new Date(), 'HH');
const ii = dateFormat(new Date(), 'MM');
const ss = dateFormat(new Date(), 'ss');
const yyyymmdd = `${yyyy}-${mm}-${dd}`;
const hhiiss = `${hh}:${ii}:${ss}`;
const yyyymmddhhiiss = `${yyyymmdd} # ${hhiiss}`;
console.log(yyyymmddhhiiss);
const logFilename = path.join(__dirname, '..', 'logs', 'server', yyyy, mm, 'log');
const logFilenameClient = path.join(__dirname, '..', 'logs', 'client', yyyy, mm, 'log');

/*
const levels = { error: 0, warn: 1, info: 2, http: 3, verbose: 4, debug: 5, silly: 6 };
*/

const transportLog = new winstonDailyRotateFile({
  name: 'logfile',
  filename: `${logFilename}_%DATE%.log`,
  datePattern: 'YYYYMMDD',
  prepend: true,
  level: 'info'
});

// const transportLogClient = new winstonDailyRotateFile({
//   name: 'logfile',
//   filename: `${logFilenameClient}_%DATE%.log`,
//   datePattern: 'YYYYMMDD',
//   prepend: true,
//   level: 'info'
// });

const logger = new winston.createLogger({
  transports: [transportLog]
});

// const loggerClient = new winston.createLogger({
//   transports: [transportLogClient]
// });

/*
// Bellow just for test, you can comment or remove it
// Our log pattern should "yyyy-mm-dd # hh:ii:ss # log description"
// This applies to all services, both client and server
logger.log('error', `${yyyymmddhhiiss} # error level will show`);
logger.log('warn', `${yyyymmddhhiiss} # warn level will show`);
logger.log('info', `${yyyymmddhhiiss} # info level will show`);
logger.log('http', `${yyyymmddhhiiss} # http level will show`);
logger.log('verbose', `${yyyymmddhhiiss} # verbose level will show`);
logger.log('debug', `${yyyymmddhhiiss} # debug level will show`);
logger.log('silly', `${yyyymmddhhiiss} # silly level will show`);
logger.info(`${yyyymmddhhiiss} # info level will show (direct create method)`, 'info');
logger.warn(`${yyyymmddhhiiss} # warn level will show (direct create method)`, 'warn');
logger.error(`${yyyymmddhhiiss} # error level will show (direct create method)`, 'error');
*/

// END CONSTRUCTION
// END LOGS

module.exports = {
  // loggerClient,
  logger,
  yyyymmddhhiiss
};