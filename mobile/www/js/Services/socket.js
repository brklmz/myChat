app.factory('socket', function(socketFactory) {
    //Create socket and connect to http://chat.socket.io 
    var myIoSocket = io.connect('http://95.85.26.49:8000');

    mySocket = socketFactory({
        ioSocket: myIoSocket
    });

    return mySocket;
})