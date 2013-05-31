var videos = [],
    PeerConnection = window.PeerConnection || window.webkitPeerConnection00 || window.webkitRTCPeerConnection || window.mozRTCPeerConnection || window.RTCPeerConnection,
    menuWidth = 250;

function UIresize(){
  var videosWidth = window.innerWidth - menuWidth;
  document.getElementById('videos').style.width = videosWidth;
}

function subdivideVideos() {
  var numVideos = videos.length,
      videosHeight = window.innerHeight,
      videosWidth = videosHeight / 0.75;

  if(videosWidth > (window.innerWidth - menuWidth)){
    videosWidth = window.innerWidth - menuWidth;
  }

  document.getElementById('videos').style.width = videosWidth;
  document.getElementById('videos').style.height = videosHeight;

  // Local streaming
  if(!numVideos){
    document.getElementById('you').style.width = videosWidth;
    document.getElementById('you').style.height = videosHeight;

  // Hasta 4 clientes
  }else if(numVideos > 0 && numVideos < 4){
    document.getElementById('you').style.width = videosWidth/2;
    document.getElementById('you').style.height = videosHeight/2;

    for(i in videos){
      var video = videos[i];
      video.style.width = videosWidth/2;
      video.style.height = videosHeight/2;
    }

  // Hasta 9 clientes
  }else{
    document.getElementById('you').style.width = videosWidth/3;
    document.getElementById('you').style.height = videosHeight/3;

    for(i in videos){
      var video = videos[i];
      video.style.width = videosWidth/3;
      video.style.height = videosHeight/3;
    }
  }
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

function addToChat(msg, color) {
  var messages = document.getElementById('messages');
  msg = sanitize(msg);
  if(color) {
    msg = '<span style="color: ' + color + '; padding-left: 15px">' + msg + '</span>';
  } else {
    msg = '<strong style="padding-left: 15px">' + msg + '</strong>';
  }
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

var dataChannelChat = {
  send: function(message) {
    for(var connection in rtc.dataChannels) {
      var channel = rtc.dataChannels[connection];
      console.log(channel);
      channel.send(message);
    }
  },
  recv: function(channel, message) {
    return JSON.parse(message).data;
  },
  event: 'data stream data'
};

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
  //var toggleHideShow = document.getElementById("hideShowMessages");
  var room = window.location.hash.slice(1);
  var color = "#" + ((1 << 24) * Math.random() | 0).toString(16);

  // toggleHideShow.addEventListener('click', function() {
  //   var element = document.getElementById("messages");

  //   if(element.style.display === "block") {
  //     element.style.display = "none";
  //   }
  //   else {
  //     element.style.display = "block";
  //   }

  // });

  input.addEventListener('keydown', function(event) {
    var key = event.which || event.keyCode;
    if(key === 13) {
      chat.send(JSON.stringify({
        "eventName": "chat_msg",
        "data": {
          "messages": input.value,
          "room": room,
          "color": color
        }
      }));
      addToChat(input.value);
      input.value = "";
    }
  }, false);
  rtc.on(chat.event, function() {
    var data = chat.recv.apply(this, arguments);
    console.log(data.color);
    addToChat(data.messages, data.color.toString(16));
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
  var user_id = Math.floor((Math.random()*10)+1);

  // Conexión por webSocket
  rtc.connect("ws:192.168.12.190:8080",room);
  //rtc.connect("ws:" + window.location.href.substring(window.location.protocol.length).split('#')[0], room);

  // Al conectarnos con el websocket, enviamos información del usuario
  rtc.on('connect', function(data){
    webSocket.send(JSON.stringify({
        "eventName": "user_connect",
        "data": {
          "user_id": user_id
        }
      }));
  });

  // Listener que recibe actualización de estado de usuarios
  rtc.on('receive_status', function(data){
    var text = "";
    $.each(data.users, function(i, val){
      if(val != user_id)
        text += "<li>Usuario "+val+"</li>";
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
        "socket_id": data
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