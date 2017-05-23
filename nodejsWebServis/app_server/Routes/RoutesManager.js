var path = require("path");

var routeMessage = require(path.join(__dirname, "./MesajRouteWebService"));
var routeFriend = require(path.join(__dirname, "./FriendRouteWebService"));


module.exports = function(app) {
	app.use("/message", routeMessage);
	app.use("/friend", routeFriend);
}