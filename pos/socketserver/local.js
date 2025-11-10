const express = require("express");
const app = express();
const http = require("https");
const fs = require("fs");
const cors = require("cors");
var key = fs.readFileSync(__dirname + "/cert/selfsigned.key");
var cert = fs.readFileSync(__dirname + "/cert/selfsigned.crt");
var options = {
  key: key,
  cert: cert,
};
const server = http.createServer(options, app);
const io = require("socket.io")(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
  },
});

const moment = require("moment");
const axios = require("axios").default;
const { send } = require("process");
const winston = require("winston");

//=========== Updateable =============
const port = 3000;

app.use(cors());

app.get("/", function (reg, res) {
  return res.send({ success: true, message: "Web Socket Connected" });
});

// Global Var
let cUser = {};

async function getAPI(param) {
  var data = await axios({
    method: "get",
    url: param.url,
    responseType: "json",
  }).then(function (res) {
    return res.data;
  });

  return data;
}

function sendAPI(param) {
  axios({
    method: "post",
    url: param.url,
    data: param.data,
  }).then(function (res) {
    console.log(res.data);
  });
}

function refreshUserOnline(socket) {
  socket.broadcast.emit("refreshUserOnline", true);
}

// Create Logger
const logger = winston.createLogger({
  transports: [
    new winston.transports.Console(),
    new winston.transports.File({ filename: "socket_local.log" }),
  ],
});

io.on("connection", (socket) => {
  socket.on("user_data", (param) => {
    cUser = param;

    // Data who logins
    console.log("connected socket id = " + socket.id);

    logger.log({
      level: "info",
      message: "connected socket id =" + socket.id,
      timestamp: moment().format("LLL"),
    });

    cUser.socket_id = socket.id;
    // do Online
    sendAPI({
      url: "https://pos-ptpis.sekawanmedia.co.id/dev/pos-v2/index.php/history_login/online",
      data: cUser,
    });
    refreshUserOnline(socket);
  });

  socket.on("disconnect", (param) => {
    // do Offlines
    sendAPI({
      url: "https://pos-ptpis.sekawanmedia.co.id/dev/pos-v2/index.php/history_login/offline",
      data: { socket_id: socket.id },
    });
    socket.broadcast.emit("checkOnlineUserWhenDC", socket.id);
    refreshUserOnline(socket);

    console.log("disconnect socket id = " + socket.id);

    logger.log({
      level: "info",
      message: "disconnect socket id =" + socket.id,
      timestamp: moment().format("LLL"),
    });
  });

  socket.emit("hello", function (socket) {
    console.log("connected to websocket from local server");
  });

  socket.on("test", (param) => {
    console.log(param);
  });
});

server.listen(port, () => {
  console.log("listening on *:" + port);
  logger.log({
    level: "info",
    message: "Server running on port :" + port,
    timestamp: moment().format("LLL"),
  });
});
