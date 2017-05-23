var mongoose = require('mongoose');

mongoose.Promise = require('bluebird');

var mongoUrl = 'mongodb://127.0.0.1/Chat';

mongoose.connect(mongoUrl, function(err, res) {
    if (err) {
        console.log('Mongo db bağlantı hatası: ' + err);
    } else {
        console.log('Mongo db bağlandı: ' + mongoUrl);
    }
});