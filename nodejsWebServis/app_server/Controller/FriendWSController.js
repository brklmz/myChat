var path = require("path");
var Friend = require(path.join(__dirname, "../Models/friend"));



module.exports.addFriend = function(req, res) {
    Friend.find({user_id:req.params.user_id},function(err,result){
		if (err) {
			res.json(false);
			
        } else {
			if(result.length==0){
				var newFriend = new Friend({
					user_id:req.params.user_id,
					list:[{
						friend_id:req.params.other_id,
						status:'1'
					}]
				});
				newFriend.save(function(err) {
					if (err) {
						res.json(false);
					} else {
						res.json('ilk arkadaş eklendi');
					}
				});
			}else{
				Friend.find({user_id:req.params.user_id,list:{$elemMatch : {friend_id:req.params.other_id}}},function(err2,result2){
					if(result2.length==0){
						Friend.update(
							{user_id: req.params.user_id},
							{
								$push: {
								'list': {friend_id: req.params.other_id, status: '1'}}
							},function(error,response){
								if(error){
									res.json(false);
								}else{
									res.json('arkadaş eklendi');
								}
							}
						);
					}else{
						res.json('arkadaş zaten ekli');
					}
				});
				
			}

        }
	});
}

module.exports.getFriends = function(req,res){
	Friend.find({user_id:req.params.user_id},function(err,result){
		if (err) {
			res.json(false);
			
        } else {
			res.json(result);
		}
	});
}











