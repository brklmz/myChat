var path = require("path");
var Mesaj = require(path.join(__dirname, "../Models/message"));



module.exports.getAllMessages = function(req, res) {
    Mesaj.find({ $or: [ { gonderen_id:req.params.user_id, alici_id:req.params.other_id } , { alici_id:req.params.user_id, gonderen_id:req.params.other_id } ] }, function(err, result) {
        if (err) {

        } else {
			res.json(result);

        }
    });
}