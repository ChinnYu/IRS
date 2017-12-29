var socket  = require( 'socket.io' );
var express = require('express');
var app     = express();
var fs = require('fs');
var options = {
    key: fs.readFileSync('/home/johnr2/public_html/www/wildcard_ccu_edu_tw.key'),
    cert: fs.readFileSync('/home/johnr2/public_html/www/wildcard_ccu_edu_tw.crt'),
	ca: fs.readFileSync('/home/johnr2/public_html/www/wildcard_ccu_edu_tw.ca-bundle'),
    requestCert: false,
	rejectUnauthorized: false
};
var server  = require('https').createServer(options, app);
var io      = socket.listen( server );
var port    = process.env.PORT || 3000;
var guestNumber = 1;
var nickNames = {};
var namesUsed = [];
var currentRoom = {};

server.listen(port, function () {
  console.log('Server listening at port %d', port);
});


io.on('connection', function (socket) {
	/* io.use(function(socket) {
	  var handshakeData = socket.request;
	  console.log("middleware:", handshakeData._query['foo']);
	  var userName = socket.request._query['foo'];
	
	  //var classpin = socket.request._query['foo2'];
	
	  //next();
	}); */
	guestNumber = assignGuestName(socket, guestNumber, nickNames, namesUsed);
	joinRoom(socket, 'Lobby');
	handleMessageBroadcasting(socket, nickNames);
    handleNameChangeAttempts(socket, nickNames, namesUsed);
    handleRoomJoining(socket);
    socket.on('rooms', function(pin) {
		//console.log(pin);
		var clients = io.sockets.adapter.rooms[pin];
		var numClients = (typeof clients !== 'undefined') ? Object.keys(clients).length : 0;
        socket.emit('rooms', numClients);
		if (numClients >= 1) {
		var usersInRoomSummary='';
		for (var clientId in clients ) {

		 //this is the socket of each client in the room.
		 var clientSocket = io.sockets.connected[clientId];

		 //you can do whatever you need with this
		 usersInRoomSummary += '  ';
		 usersInRoomSummary += nickNames[clientId];
		}
		socket.emit('clientlist', usersInRoomSummary);
	  }
    });
	socket.on('quizstart', function(room){
		console.log('hi');
		socket.broadcast.to(room).emit('quizstart');
	});
    handleClientDisconnection(socket, nickNames, namesUsed);
	
  /* socket.on( 'realname', function( data ) {
	  console.log(data['realname']);
	  var feed = data['realname'];
    io.sockets.emit( 'realnamefeedback', { 
    	realname: feed

    });
  }); */

  
});

function assignGuestName(socket, guestNumber, nickNames, namesUsed) {
  var name = 'Guest' + guestNumber;
  nickNames[socket.id] = name;
  socket.emit('nameResult', {
    success: true,
    name: name
  });
  namesUsed.push(name);
  return guestNumber + 1;
}

function joinRoom(socket, room) {
  socket.join(room);
  currentRoom[socket.id] = room;
  socket.emit('joinResult', {room: room});
  console.log(room);
  /* socket.broadcast.to(room).emit('message', {
    text: room 
  }); */
  var usersInRoom = io.sockets.adapter.rooms[room];
  var numClients = (typeof usersInRoom !== 'undefined') ? Object.keys(usersInRoom).length : 0;
  if (numClients > 1) {
    var usersInRoomSummary = 'Users currently in ' + room + ': ';
	for (var clientId in usersInRoom ) {

     //this is the socket of each client in the room.
     var clientSocket = io.sockets.connected[clientId];

     //you can do whatever you need with this
     usersInRoomSummary += ', ';
	 usersInRoomSummary += nickNames[clientId];
	}
    /* for (var index in usersInRoom) {
      var userSocketId = usersInRoom[index].id;
      if (userSocketId != socket.id) {
        if (index > 0) {
          usersInRoomSummary += ', ';
        }
        usersInRoomSummary += nickNames[userSocketId];
      }
    } */
    usersInRoomSummary += '.';
    socket.emit('message', {text: usersInRoomSummary});
  }
}

function handleNameChangeAttempts(socket, nickNames, namesUsed) {
  socket.on('nameAttempt', function(name) {
    if (name.indexOf('Guest') == 0) {
      socket.emit('nameResult', {
        success: false,
        message: 'Names cannot begin with "Guest".'
      });
    } else {
      if (namesUsed.indexOf(name) == -1) {
        var previousName = nickNames[socket.id];
        var previousNameIndex = namesUsed.indexOf(previousName);
		console.log(name);
        namesUsed.push(name);
        nickNames[socket.id] = name;
        delete namesUsed[previousNameIndex];
        socket.emit('nameResult', {
          success: true,
          name: name
        });
        /* socket.broadcast.to(currentRoom[socket.id]).emit('message', {
          text: previousName + ' is now known as ' + name + '.'
        }); */
      } else {
        socket.emit('nameResult', {
          success: false,
          message: 'That name is already in use.'
        });
      }
    }
  });
}

function handleMessageBroadcasting(socket) {
  socket.on('message', function (message) {
    socket.broadcast.to(message.room).emit('message', {
      text: nickNames[socket.id] + ': ' + message.text
    });
  });
}

function handleRoomJoining(socket) {
  socket.on('join', function(room) {
    socket.leave(currentRoom[socket.id]);
    joinRoom(socket, room.newRoom);
  });
}

function handleClientDisconnection(socket) {
  socket.on('disconnect', function() {
    var nameIndex = namesUsed.indexOf(nickNames[socket.id]);
    delete namesUsed[nameIndex];
    delete nickNames[socket.id];
  });
}

function getClientsInRoom(room) {
    // get array of socket ids in this room
      var usersInRoom = io.sockets.adapter.rooms[room];
	  var numClients = (typeof usersInRoom !== 'undefined') ? Object.keys(usersInRoom).length : 0;
	  if (numClients >= 1) {
		var usersInRoomSummary='';
		for (var clientId in usersInRoom ) {

		 //this is the socket of each client in the room.
		 var clientSocket = io.sockets.connected[clientId];

		 //you can do whatever you need with this
		 usersInRoomSummary += ', ';
		 usersInRoomSummary += nickNames[clientId];
		}
		socket.emit('clientlist', usersInRoomSummary);
	  }
}