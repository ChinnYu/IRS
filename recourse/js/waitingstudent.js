var socket = io.connect( 'https://'+window.location.hostname+':3000',{ secure: true, reconnect: true, rejectUnauthorized : false });
var Chat = function(socket) {
  this.socket = socket;
};

Chat.prototype.sendMessage = function(room, text) {
  var message = {
    room: room,
    text: text
  };
  this.socket.emit('message', message);
};

Chat.prototype.changeRoom = function(room) {
  this.socket.emit('join', {
    newRoom: room
  });
};

Chat.prototype.changenick = function(name) {
  this.socket.emit('nameAttempt', name);
};

Chat.prototype.processCommand = function(command) {
  var words = command.split(' ');
  var command = words[0]
                .substring(1, words[0].length)
                .toLowerCase();
  var message = false;

  switch(command) {
    case 'join':
      words.shift();
      var room = words.join(' ');
      this.changeRoom(room);
      break;
    case 'nick':
      words.shift();
      var name = words.join(' ');
      this.socket.emit('nameAttempt', name);
      break;
    default:
      message = 'Unrecognized command.';
      break;
  };

  return message;
};

function divEscapedContentElement(message) {
  return $('<div></div>').text(message);
}

function divSystemContentElement(message) {
  return $('<div></div>').html('<i>' + message + '</i>');
}

function processUserInput(chatApp, socket) {
  var message = $('#send-message').val();
  var systemMessage;

  if (message.charAt(0) == '/') {
    systemMessage = chatApp.processCommand(message);
    if (systemMessage) {
      $('#messages').append(divSystemContentElement(systemMessage));
    }
  } else {
    chatApp.sendMessage($('#room').text(), message);
    $('#messages').append(divEscapedContentElement(message));
    $('#messages').scrollTop($('#messages').prop('scrollHeight'));
  }

  $('#send-message').val('');
}

			
$(document).ready(function() {



  var chatApp = new Chat(socket);
 
  socket.on('rooms', function(rooms) {
    $('#room-list').empty();
  });
  
  socket.on('quizstart', function() {
	sleep(2000);
	window.location.href = './quizpage';
  });

});

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

$.ajax({
				type: "POST",
				url: "classconnect/loadnamestudent",
				dataType: "json",
				success: function(su){				
				
				var socket = io.connect( 'https://'+window.location.hostname+':3000',{ secure: true, reconnect: true, rejectUnauthorized : false });
				var chatApp = new Chat(socket);
				chatApp.changenick(su['user_Name']);
				chatApp.changeRoom(su['pin']);
				} ,error: function(xhr, status, error) {
				  alert('hi');
				},

			});	