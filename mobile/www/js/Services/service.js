app.config(function($ionicConfigProvider, $httpProvider) {
    $httpProvider.defaults.useXDomain = true;
    $ionicConfigProvider.backButton.text('').icon('ion-ios-arrow-back').previousTitleText(false);
})

app.factory('webMethod', function($http) {
    var server = "http://95.85.26.49/api/index.php/";

    return {
        newUsers: function(name, surname, username, password) { // Yeni kullanıcı oluşturmak için kullanılır

            return $http.get(server + "user/users_add?name=" + encodeURI(name) + "&surname=" + encodeURI(surname) + "&username=" + encodeURI(username) + "&password=" + encodeURI(password));
        },
        userInfo: function(user_id) {
            return $http.get(server + "user/user_info?user_id=" + encodeURI(user_id));

        },
        uploadImg: function(user_id, img_url) {
            return $http.post(server + "user/upload_img", { "user_id": user_id, "img_url": img_url });
        },
        nameUpdate: function(user_id, first_name) {
            return $http.post(server + "user/name_update", { "user_id": user_id, "first_name": first_name });
        },
        lastnameUpdate: function(user_id, last_name) {
            return $http.get(server + "user/last_name_update?user_id=" + encodeURI(user_id) + "&last_name=" + encodeURI(last_name));
        },
        passUpdate: function(user_id, pass, new_pass) {
            return $http.get(server + "user/pass_update?user_id=" + encodeURI(user_id) + "&pass=" + encodeURI(pass) + "&new_pass=" + encodeURI(new_pass));
        },
        checkUsers: function(username, password) { // User kontrolü yapmak için kullanılır

            return $http.get(server + "user/check_user?username=" + encodeURI(username) + "&password=" + encodeURI(password));
        },
        getAllMessages: function(user_id, other_id) {
            return $http.get(server + "Messages/messages/" + user_id + "/" + other_id);
        },
        addFriend: function(user_id, other_id) {
            return $http.get(server + "Friends/add_friend/" + user_id + "/" + other_id);
        },
        getUsers: function(user_id) { // User kontrolü yapmak için kullanılır

            return $http.get(server + "friends/friends?user_id=" + encodeURI(user_id));
        },
        getUser: function(username) { // User kontrolü yapmak için kullanılır

            return $http.get(server + "user/user?username=" + username);
        }

    }
});