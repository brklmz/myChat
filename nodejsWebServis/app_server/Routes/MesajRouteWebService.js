var path = require("path");
var express = require("express");
var router = express.Router();
var cMessage = require(path.join(__dirname, "../Controller/MessageWSController"));

router.use(function(req, res, next) {

    next();
});

router.get("/getAllMessages/:user_id/:other_id", cMessage.getAllMessages);


module.exports = router;