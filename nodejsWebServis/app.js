var path = require("path");
var express = require("express");
var ejsLayouts = require('express-ejs-layouts');
var bodyParser = require('body-parser');
var app = express();
var server = app.listen("8000");
var socket = require('socket.io').listen(server);
var db = require(path.join(__dirname, "app_server/Models/db"));




app.use(ejsLayouts);
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

app.use("/public", express.static(path.join(__dirname, "public")));
var Message = require(path.join(__dirname, "app_server/Models/message"));


socket.on('connection', function (io) {
    console.log('bağlandı');
	io.on('get_message', function (data) {
		console.log(data);
		var yeniMesaj = new Message({
			gonderen_id: data.gonderen_id,
			alici_id: data.alici_id,
			mesaj: data.mesaj,
			tarih: data.tarih
		});
		yeniMesaj.save(function(err) {
			if (err) {
				console.log(err);
			} else {
				console.log('mesaj kaydedildi');
			}
		});
		socket.emit('send_message_'+data.alici_id, data);
	});
});



socket.on('disconnect', function () {
    console.log('çıktı');
});


require(path.join(__dirname, "app_server/Routes/RoutesManager"))(app);
