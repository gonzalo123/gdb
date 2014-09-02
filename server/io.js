var
    express = require('express'),
    expressApp = express(),
    server = require('http').Server(expressApp),
    io = require('socket.io')(server, {origins: 'localhost:*'})
    ;

expressApp.get('/emit', function (req, res) {
    io.sockets.emit(req.query._chanel, req.query);
    res.json('OK');
});

expressApp.listen(3001, 'localhost', function () {
    console.log('Express started');
}, '0.0.0.0');

server.listen(3000, function () {
    console.log('IO started');
}, '0.0.0.0');