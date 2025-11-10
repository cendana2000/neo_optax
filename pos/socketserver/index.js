const express = require("express");
const app = express();
const http = require("http");
const fs = require("fs");
const cors = require("cors");
const moment = require("moment");
const axios = require("axios");
const { logger, yyyymmddhhiiss } = require('./service/log.service');

require("dotenv").config();

var key = fs.readFileSync(__dirname + "/cert/selfsigned.key");
var cert = fs.readFileSync(__dirname + "/cert/selfsigned.crt");
var options = {
  // key: key,
  // cert: cert,
};
const server = http.createServer(options, app);
const io = require("socket.io")(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
  },
});
//=========== Updateable =============

const port = 3000;

app.use(cors());

app.get("/", function (reg, res) {
  return res.send({ success: true, message: "Web Socket Connected" });
});

app.get("/sc", function (req, res) {
  return res.send({ success: true, message: "Web Socket Connected Via SC" });
})

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

  logger.log({
    level: "info",
    message: "param =" + JSON.stringify(param),
    timestamp: moment().format("LLL"),
  });

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
      url: `${process.env.POS_URL}index.php/history_login/online`,
      data: cUser,
    });
    refreshUserOnline(socket);
  });

  socket.on("disconnect", (param) => {
    // do Offlines
    sendAPI({
      url: `${process.env.POS_URL}index.php/history_login/offline`,
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
