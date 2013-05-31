var app = require('express')();
var server = require('http').createServer(app);
var webRTC = require('webrtc.io').listen(server);

// Puerto de comunicación
var port = process.env.PORT || 8080;
server.listen(port);

// Array de clientes conectados
var clients = [];


// app.get('/', function(req, res) {
//   res.sendfile(__dirname + '/index.html');
// });

// app.get('/style.css', function(req, res) {
//   res.sendfile(__dirname + '/style.css');
// });

// app.get('/fullscrean.png', function(req, res) {
//   res.sendfile(__dirname + '/fullscrean.png');
// });

// app.get('/script.js', function(req, res) {
//   res.sendfile(__dirname + '/script.js');
// });

// app.get('/webrtc.io.js', function(req, res) {
//   res.sendfile(__dirname + '/webrtc.io.js');
// });

webRTC.rtc.on('chat_msg', function(data, socket) {
  var roomList = webRTC.rtc.rooms[data.room] || [];

  for (var i = 0; i < roomList.length; i++) {
    var socketId = roomList[i];

    if (socketId !== socket.id) {
      var soc = webRTC.rtc.getSocket(socketId);

      if (soc) {
        soc.send(JSON.stringify({
          "eventName": "receive_chat_msg",
          "data": {
            "messages": data.messages,
            "color": data.color
          }
        }), function(error) {
          if (error) {
            console.log(error);
          }
        });
      }
    }
  }
});

webRTC.rtc.on('user_connect', function(data, socket){
  // Eliminamos sockets zombies
  deleteUnusedSockets();

  // Añadimos nuevo cliente al array
  var o = {
    "socket_id": socket.id,
    "user_id": data.user_id
  };
  clients.push(o);
  
  // Obtenemos array de usuarios a enviar
  var users = getUsers();

  // Enviamos a cada cliente información de los usuarios conectados
  for(var i in clients){
    //var soc = clients[i].socket_id;
    var soc = webRTC.rtc.getSocket(clients[i].socket_id);

    if(soc){
       soc.send(JSON.stringify({
        "eventName": "receive_status",
        "data": {
          "users": users
        }
      }), function(error) {
        if (error) {
          console.log("ERROR: "+error);
        }
      });
    }
  }

});

webRTC.rtc.on('user_disconnect', function(data, socket){
  // Eliminamos el socket desconectado del array (Si no ha sido previamente eliminado por algun cliente)
  for(var i in clients){
    if(clients[i].socket_id == data.socket_id)
      clients.splice(i,1);
  }

  // Obtenemos array de usuarios a enviar
  var users = getUsers();

  // Enviamos información al cliente que ha lanzado el evento
  var soc = webRTC.rtc.getSocket(socket.id);
  if(soc){
    soc.send(JSON.stringify({
      "eventName": "receive_status",
      "data": {
        "users": users
      }
    }), function(error) {
      if (error) {
        console.log("ERROR: "+error);
      }
    });
  }

});

function getUsers(){
  var users = new Array();

  for(var i in clients){
    if(users.indexOf(clients[i].user_id) == -1)
      users.push(clients[i].user_id);
  }
  
  return users;
}

function deleteUnusedSockets(){
  for(var i in clients){
    
    var soc = webRTC.rtc.getSocket(clients[i].socket_id);

    if(!soc){
      delete clients[i];   
    }
  }
}
