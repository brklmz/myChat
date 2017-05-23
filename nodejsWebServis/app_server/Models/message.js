var mongoose = require('mongoose');

var Schema = mongoose.Schema;

var messageSchema = new Schema({
		gonderen_id: String,
		alici_id: String,
		mesaj: String,
		tarih: String}, 
		{ collection: 'messages' });

var Message = mongoose.model('Message', messageSchema);

module.exports = Message;