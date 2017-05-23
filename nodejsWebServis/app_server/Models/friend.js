var mongoose = require('mongoose');

var Schema = mongoose.Schema;

var friendSchema = new Schema({
		user_id:String,
		list:[
			{
				friend_id:String,
				status:String
			}
		]
	},
	{ collection: 'friend' }
);

var Friend = mongoose.model('Friend', friendSchema);

module.exports = Friend;