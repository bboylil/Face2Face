var videos = [],
    PeerConnection = window.PeerConnection || window.webkitPeerConnection00 || window.webkitRTCPeerConnection || window.mozRTCPeerConnection || window.RTCPeerConnection,
    menuWidth = 280,
    headerHeight = 75,
    chatInputHeight = 60,
    user_id,
    username,
    color;

function UIresize(){
  // Ancho
  // var videosWidth = window.innerWidth - menuWidth;
  // document.getElementById('videos').style.width = videosWidth;
  // document.getElementById('main').style.width = videosWidth;

  // // Altura
  // var menuHeight = window.innerHeight - headerHeight - chatInputHeight;
  // document.getElementById('menu').style.height = menuHeight;  
  // document.getElementById('main').style.height = window.innerHeight - headerHeight;
}

function subdivideVideos() {
  // var numVideos = videos.length,
  //     videosHeight = window.innerHeight - headerHeight - chatInputHeight,
  //     videosWidth = videosHeight / 0.75;

  // if(videosWidth > (window.innerWidth - menuWidth)){
  //   videosWidth = window.innerWidth - menuWidth;
  // }

  // document.getElementById('videos').style.width = videosWidth;
  // document.getElementById('videos').style.height = videosHeight;

  // // Local streaming
  // if(!numVideos){
  //   document.getElementById('you').style.width = videosWidth;
  //   document.getElementById('you').style.height = videosHeight;

  // // Hasta 4 clientes
  // }else if(numVideos > 0 && numVideos < 4){
  //   document.getElementById('you').style.width = videosWidth/2;
  //   document.getElementById('you').style.height = videosHeight/2;

  //   for(i in videos){
  //     var video = videos[i];
  //     video.style.width = videosWidth/2;
  //     video.style.height = videosHeight/2;
  //   }

  // // Hasta 9 clientes
  // }else{
  //   document.getElementById('you').style.width = videosWidth/3;
  //   document.getElementById('you').style.height = videosHeight/3;

  //   for(i in videos){
  //     var video = videos[i];
  //     video.style.width = videosWidth/3;
  //     video.style.height = videosHeight/3;
  //   }
  // }
}

function cloneVideo(domId, socketId) {
  var video = document.getElementById(domId);
  var clone = video.cloneNode(false);
  clone.id = "remote" + socketId;
  document.getElementById('videos').appendChild(clone);
  videos.push(clone);
  return clone;
}

function removeVideo(socketId) {
  var video = document.getElementById('remote' + socketId);
  if(video) {
    videos.splice(videos.indexOf(video), 1);
    video.parentNode.removeChild(video);
  }
  subdivideVideos();
}

function addToChat(msg, color, user_name) {
  var messages = document.getElementById('messages');

  weigth = (color == "black") ? "bold" : "none";

  msg = sanitize(msg);
  msg = '<span style="color: '+ color +'; font-weight:'+ weigth +'">'+ user_name +'</span><div>' + msg + '</div>';
  
  messages.innerHTML = messages.innerHTML + msg + '<br>';
  messages.scrollTop = 10000;
}

function sanitize(msg) {
  return msg.replace(/</g, '&lt;');
}

function initFullScreen() {
  var button = document.getElementById("fullscreen");
  button.addEventListener('click', function(event) {
    var elem = document.getElementById("videos");
    //show full screen
    elem.webkitRequestFullScreen();
  });
}

function initNewRoom() {
  var button = document.getElementById("newRoom");

  button.addEventListener('click', function(event) {

    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    var string_length = 8;
    var randomstring = '';
    for(var i = 0; i < string_length; i++) {
      var rnum = Math.floor(Math.random() * chars.length);
      randomstring += chars.substring(rnum, rnum + 1);
    }

    window.location.hash = randomstring;
    location.reload();
  })
}

var webSocket = {
  send: function(message) {
    rtc._socket.send(message);
  },
  recv: function(message) {
    return message;
  }
};

var websocketChat = {
  send: function(message) {
    rtc._socket.send(message);
  },
  recv: function(message) {
    return message;
  },
  event: 'receive_chat_msg'
};

// var dataChannelChat = {
//   send: function(message) {
//     for(var connection in rtc.dataChannels) {
//       var channel = rtc.dataChannels[connection];
//       console.log(channel);
//       channel.send(message);
//     }
//   },
//   recv: function(channel, message) {
//     return JSON.parse(message).data;
//   },
//   event: 'data stream data'
// };

function initChat() {
  var chat;

  // if(rtc.dataChannelSupport) {
  //   console.log('initializing data channel chat');
  //   chat = dataChannelChat;
  // } else {
    console.log('initializing websocket chat');
    chat = websocketChat;
  // }

  var input = document.getElementById("chatinput");
  var room = window.location.hash.slice(1);

  input.addEventListener('keydown', function(event) {
    var key = event.which || event.keyCode;
    if(key === 13 && input.value != "") {
      chat.send(JSON.stringify({
        "eventName": "chat_msg",
        "data": {
          "messages": input.value,
          "room": room,
          "color": color,
          "user_id": user_id,
          "username": username
        }
      }));
      addToChat(input.value,"black",username);
      input.value = "";
    }
  }, false);
  rtc.on(chat.event, function() {
    var data = chat.recv.apply(this, arguments);
    (data.user_id == user_id) ? addToChat(data.messages,"black", username) : addToChat(data.messages, data.color.toString(16), data.username);
  });
}


function init() {
  UIresize();

  if(PeerConnection) {
    rtc.createStream({
      "video": {"mandatory": {}, "optional": []},
      "audio": false
    }, function(stream) {
      document.getElementById('you').src = URL.createObjectURL(stream);
      document.getElementById('you').play();
      //videos.push(document.getElementById('you'));
      //rtc.attachStream(stream, 'you');
      //subdivideVideos();
    });
  } else {
    alert('Your browser is not supported or you have to turn on flags. In chrome you go to chrome://flags and turn on Enable PeerConnection remember to restart chrome');
  }


  var room = window.location.hash.slice(1);

  // Obtenemos el ID y nombre de usuario
  $.ajax({
    type: "post",
    dataType: "json",
    url: "../connect/functions.php",
    success: function(datos){
      if(datos.code != 0){
        
        user_id = datos.data.iduser;
        username = datos.data.nick;

        // Conexión por webSocket
        rtc.connect("ws:192.168.0.198:8080",room);
        //rtc.connect("ws:" + window.location.href.substring(window.location.protocol.length).split('#')[0], room);
        
      }else{ window.location = "/"; }
    }
  });

  // var user_id = Math.floor((Math.random()*10)+1);

  

  // Al conectarnos con el websocket, enviamos información del usuario
  rtc.on('connect', function(data){
    webSocket.send(JSON.stringify({
        "eventName": "user_connect",
        "data": {
          "user_id": user_id,
          "username": username,
          "room": room
        }
      }));
  });

  // Listener que recibe actualización de estado de usuarios
  rtc.on('receive_status', function(data){
    var text = "";
    $.each(data.users, function(i, val){
      if((val.user_id != user_id) && val.room == room)
        text += "<li>"+val.username+"</li>";
      // Soy yo
      else if(val.user_id == user_id){
        // Asignamos color al cliente
        color = val.color;
      }
    });
    
    // Insertamos usuarios en DOM
    $('.users_list').html(text);

    // Añadimos los listeners al clicar en el usuario
    var lis = $('.users_list li');
    $.each(lis, function(i, val){
      $(val).click(function(){
        
      });
    });

  });

  rtc.on('add remote stream', function(stream, socketId) {
    console.log("ADDING REMOTE STREAM...");
    var clone = cloneVideo('you', socketId);
    document.getElementById(clone.id).setAttribute("class", "");
    rtc.attachStream(stream, clone.id);
    subdivideVideos();
  });

  // Listener: Desconexión del socket
  rtc.on('disconnect stream', function(data) {
    console.log('remove ' + data);
    removeVideo(data);

    // Enviamos información de estado al server
    webSocket.send(JSON.stringify({
      "eventName": "user_disconnect",
      "data": {
        "socket_id": data,
        "room": room
      }
    }));

  });
  //initFullScreen();
  initNewRoom();
  initChat();
}

window.onresize = function(event) {
  subdivideVideos();
};