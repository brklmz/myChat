var path = require("path");
var express = require("express");
var router = express.Router();
var cFriend = require(path.join(__dirname, "../Controller/FriendWSController"));

router.use(function(req, res, next) {

    next();
});

router.get("/addFriend/:user_id/:other_id", cFriend.addFriend);
router.get("/getFriends/:user_id", cFriend.getFriends);


module.exports = router;