app = angular.module('starter.controllers', [])

.controller('AppCtrl', ['$scope', '$rootScope', '$ionicModal', '$timeout', 'webMethod', '$ionicPopup', function($scope, $rootScope, $ionicModal, $timeout, webMethod, $ionicPopup) {

    $scope.addFriend = function() {
        $scope.data = {};

        // An elaborate, custom popup
        var myPopup = $ionicPopup.show({
            template: '<input type="text" placeholder="kullanıcı adı..." ng-model="data.friend_name">',
            title: 'Ara',
            scope: $scope,
            buttons: [
                { text: 'Vazgeç' },
                {
                    text: '<b>Ekle</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        if (!$scope.data.friend_name) {
                            //don't allow the user to close unless he enters first_name
                            e.preventDefault();
                        } else {

                            if (window.localStorage.getItem("username") == $scope.data.friend_name) {
                                var myPopup = $ionicPopup.show({
                                    title: 'Kendi kullanıcı adınızı girdiniz',
                                    scope: $scope,
                                    buttons: [
                                        { text: 'Tamam' }
                                    ]
                                });
                            } else {
                                webMethod.getUser($scope.data.friend_name).then(function(response) {
                                    if (response.data.status == true) {
                                        console.log(response.data);

                                        webMethod.addFriend(window.localStorage.getItem("user_id"), response.data.user.uye_id).then(function(res) {
                                            if (res.data.status == true) {
                                                var myPopup = $ionicPopup.show({
                                                    title: res.data.res,
                                                    scope: $scope,
                                                    buttons: [
                                                        { text: 'Tamam' }
                                                    ]
                                                });
                                                $rootScope.allUsers.push(response.data.user);



                                            }
                                        });

                                    } else {
                                        var myPopup = $ionicPopup.show({
                                            title: 'Kullanıcı bulunamadı',
                                            scope: $scope,
                                            buttons: [
                                                { text: 'Tamam' }
                                            ]
                                        });
                                    }
                                });
                            }






                        }
                    }
                }
            ]
        });
    };

    // Form data for the login modal
    $scope.loginData = {};

    // Create the login modal that we will use later
    $ionicModal.fromTemplateUrl('templates/login.html', {
        scope: $scope
    }).then(function(modal) {
        $scope.modal = modal;
    });

    // Triggered in the login modal to close it
    $scope.closeLogin = function() {
        $scope.modal.hide();
    };

    // Open the login modal
    $scope.login = function() {
        $scope.modal.show();
    };

    // Perform the login action when the user submits the login form
    $scope.doLogin = function() {
        console.log('Doing login', $scope.loginData);

        // Simulate a login delay. Remove this and replace with your login
        // code if using a login system
        $timeout(function() {
            $scope.closeLogin();
        }, 1000);
    };

    if (window.localStorage.getItem("username")) {
        $rootScope.name = window.localStorage.getItem("username");
        $rootScope.first_name = window.localStorage.getItem("first_name");
        $rootScope.last_name = window.localStorage.getItem("last_name");
        $rootScope.picture = window.localStorage.getItem("picture");
    }

    if (!window.localStorage.getItem("picture")) {
        $rootScope.picture = "img/profile.png";
    }

}])


.controller('loginCtrl', ['$scope', '$rootScope', '$cordovaOauth', '$location', '$http', '$state', 'webMethod', '$ionicPopup', '$ionicLoading', '$ionicPlatform', '$http', '$q', function($scope, $rootScope, $cordovaOauth, $location, $http, $state, webMethod, $ionicPopup, $ionicLoading, $ionicPlatform, $http, $q) {

    if (window.localStorage.getItem("isLogin")) {
        $state.go('app.chat-users');
    }

    $scope.userInfo = {
        "username": "",
        "password": ""
    };


    $scope.login = function() {

        if ($scope.userInfo.username && $scope.userInfo.password) {

            $ionicLoading.show({
                templateUrl: "templates/loading.html"
            });

            webMethod.checkUsers($scope.userInfo.username, $scope.userInfo.password).then(function(response) {
                $ionicLoading.hide();
                console.log(response);
                if (response.data.status == true) {
                    //gul.nurullah@gmail.com
                    console.log(response.data);

                    $rootScope.doLogin = true;
                    window.localStorage.setItem("isLogin", true);
                    window.localStorage.setItem("username", response.data.user.username);
                    window.localStorage.setItem("first_name", response.data.user.ad);
                    window.localStorage.setItem("last_name", response.data.user.soyad);
                    window.localStorage.setItem("picture", response.data.user.img_url);
                    window.localStorage.setItem("user_id", response.data.user.uye_id);
                    $rootScope.picture = window.localStorage.getItem('picture');
                    $state.go('app.chat-users');

                } else {

                    var alertPopup = $ionicPopup.alert({
                        title: 'Hata !',
                        template: 'Hatalı Giriş'
                    });

                }

            });

        } else {

            var alertPopup = $ionicPopup.alert({
                title: 'Uyarı',
                template: 'Alanları eksiksiz doldurunuz.'
            });


        }

    }




}])


app.controller('LogoutCtrl', ['$scope', '$state', function($scope, $state) {


    window.localStorage.removeItem("isLogin");
    window.localStorage.removeItem("first_name");
    window.localStorage.removeItem("last_name");
    window.localStorage.removeItem("picture");
    $state.go('login');
}])



.controller('profileCtrl', ['$rootScope', '$scope', '$ionicPopup', 'webMethod', '$ionicModal', '$cordovaCamera', function($rootScope, $scope, $ionicPopup, webMethod, $ionicModal, $cordovaCamera) {
    $scope.namePopup = function() {
        $scope.data = {};

        // An elaborate, custom popup
        var myPopup = $ionicPopup.show({
            template: '<input type="text" ng-model="data.first_name">',
            title: 'Adınız',
            scope: $scope,
            buttons: [
                { text: 'Vazgeç' },
                {
                    text: '<b>Kaydet</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        if (!$scope.data.first_name) {
                            //don't allow the user to close unless he enters first_name
                            e.preventDefault();
                        } else {

                            webMethod.nameUpdate(window.localStorage.getItem("user_id"), $scope.data.first_name).then(function(response) {
                                if (response.data.status == true) {
                                    window.localStorage.setItem("first_name", $scope.data.first_name);
                                    $rootScope.first_name = $scope.data.first_name;
                                }
                            });


                        }
                    }
                }
            ]
        });


    };

    $scope.lastnamePopup = function() {
        $scope.data = {};

        // An elaborate, custom popup
        var myPopup = $ionicPopup.show({
            template: '<input type="text" ng-model="data.last_name">',
            title: 'Soyadınız',
            scope: $scope,
            buttons: [
                { text: 'Vazgeç' },
                {
                    text: '<b>Kaydet</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        if (!$scope.data.last_name) {
                            //don't allow the user to close unless he enters last_name
                            e.preventDefault();
                        } else {

                            webMethod.lastnameUpdate(window.localStorage.getItem("user_id"), $scope.data.last_name).then(function(response) {
                                if (response.data.status == true) {
                                    window.localStorage.setItem("last_name", $scope.data.last_name);
                                    $rootScope.last_name = $scope.data.last_name;
                                }
                            });


                        }
                    }
                }
            ]
        });


    };

    $scope.passPopup = function() {
        $scope.data = {};

        // An elaborate, custom popup
        var myPopup = $ionicPopup.show({
            templateUrl: "templates/password_change.html",
            title: 'Şifre değiştir',
            scope: $scope,
            buttons: [
                { text: 'Vazgeç' },
                {
                    text: '<b>Kaydet</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        if ($scope.data.pass && $scope.data.new_pass && $scope.data.confirm_pass) {
                            if ($scope.data.new_pass == $scope.data.confirm_pass) {


                                webMethod.passUpdate(window.localStorage.getItem("user_id"), $scope.data.pass, $scope.data.new_pass).then(function(response) {
                                    if (response.data.status == true) {

                                        var confirmPopup = $ionicPopup.confirm({
                                            title: "Tamamlandı",
                                            template: 'Şifreniz değiştirildi.',
                                            buttons: [
                                                { text: 'Tamam', type: 'button-positive' }
                                            ]
                                        });
                                    } else {
                                        var confirmPopup = $ionicPopup.confirm({
                                            title: "Hata!",
                                            template: 'Hata oluştu.',
                                            buttons: [
                                                { text: 'Tamam', type: 'button-positive' }
                                            ]
                                        });
                                    }
                                });
                            } else {
                                var confirmPopup = $ionicPopup.confirm({
                                    title: "Hata!",
                                    template: 'Girdiğiniz şifreler uyuşmuyor.',
                                    buttons: [
                                        { text: 'Tamam', type: 'button-positive' }
                                    ]
                                });
                            }
                        } else {
                            e.preventDefault();



                        }
                    }
                }
            ]
        });


    };

    $scope.chooseOption = function() {
        var myPopup = $ionicPopup.show({
            title: 'Fotoğraf yükleme seçenekleri',
            //subTitle: 'Please use normal things',
            //scope: $scope,
            buttons: [{
                    text: '<b class="myclose">Kamera</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        var options = {
                            quality: 75,
                            destinationType: Camera.DestinationType.DATA_URL,
                            sourceType: Camera.PictureSourceType.CAMERA,
                            allowEdit: true,
                            encodingType: Camera.EncodingType.JPEG,
                            targetWidth: 300,
                            targetHeight: 300,
                            popoverOptions: CameraPopoverOptions,
                            saveToPhotoAlbum: false
                        };

                        $cordovaCamera.getPicture(options).then(function(imageData) {
                            $scope.imgURI = "data:image/jpeg;base64," + imageData;
                            $scope.img_url = "data:image/jpeg;base64," + imageData;

                            webMethod.uploadImg(window.localStorage.getItem("user_id"), $scope.img_url).then(function(response) {



                                if (response.data.status == true) {
                                    var alertPopup = $ionicPopup.alert({
                                        title: 'Yüklendi',
                                        template: response.data.message
                                    });
                                    $rootScope.picture = $scope.img_url;


                                } else {

                                    var alertPopup = $ionicPopup.alert({
                                        title: 'Hata',
                                        template: response.data.message
                                    });

                                }


                            })

                        }, function(err) {
                            // An error occured. Show a message to the user
                        });
                    }
                },
                {
                    text: '<b class="myclose">Galeri</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        var options = {
                            quality: 75,
                            destinationType: Camera.DestinationType.DATA_URL,
                            sourceType: Camera.PictureSourceType.PHOTOLIBRARY,
                            allowEdit: true,
                            encodingType: Camera.EncodingType.JPEG,
                            targetWidth: 300,
                            targetHeight: 300,
                            popoverOptions: CameraPopoverOptions,
                            saveToPhotoAlbum: false
                        };

                        $cordovaCamera.getPicture(options).then(function(imageData) {
                            $scope.imgURI = "data:image/jpeg;base64," + imageData;
                            $scope.img_url = "data:image/jpeg;base64," + imageData;

                            webMethod.uploadImg(window.localStorage.getItem("user_id"), $scope.img_url).then(function(response) {


                                if (response.data.status == true) {
                                    var alertPopup = $ionicPopup.alert({
                                        title: 'Yüklendi',
                                        template: response.data.message
                                    });

                                    $rootScope.picture = $scope.img_url;

                                } else {

                                    var alertPopup = $ionicPopup.alert({
                                        title: 'Hata',
                                        template: response.data.message
                                    });

                                }


                            })

                        }, function(err) {
                            // An error occured. Show a message to the user
                        });
                    }
                },
                {
                    text: '<i class="icon ion-close-round myclose"></i>'
                }
            ]
        });

    }

    $scope.showModal = function() {
        console.log("tiklandi");
        $ionicModal.fromTemplateUrl('templates/image_popover.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
            $scope.modal.show();
        });
    }

    // Close the modal
    $scope.closeModal = function() {
        $scope.modal.hide();
        $scope.modal.remove()
    };


}])


.controller('SignUpCtrl', ['$state', '$scope', '$ionicModal', '$ionicLoading', 'webMethod', '$ionicPopup', function($state, $scope, $ionicModal, $ionicLoading, webMethod, $ionicPopup) {

    $scope.isCompany = false;

    $scope.data = {
        name: "",
        surname: "",
        username: "",
        password: "",
        password_retry: ""

    };

    $scope.vm = {
        sozlesme: false
    };

    $scope.toogleCompany = function() {

        if ($scope.isCompany)
            $scope.isCompany = false;
        else
            $scope.isCompany = true;

        console.log($scope.isCompany);

    }

    $scope.status = 0;

    $scope.doRegister = function() {

        $scope.result = 0;

        $ionicLoading.show({
            templateUrl: "templates/loading.html"
        });



        if ($scope.data.name && $scope.data.surname && $scope.data.username && $scope.data.password && $scope.data.password_retry) {





            if ($scope.data.password.length >= 6) {
                if ($scope.data.name.length >= 3) {
                    if ($scope.data.password == $scope.data.password_retry) {


                        webMethod.newUsers($scope.data.name, $scope.data.surname, $scope.data.username, $scope.data.password).then(function(response) {

                            $ionicLoading.hide();

                            if (response.data.status == true) {
                                window.localStorage.setItem("user_id", response.data.id);
                                $state.go('upload_img');

                            } else {

                                var alertPopup = $ionicPopup.alert({
                                    title: 'Hata',
                                    template: response.data.message
                                });

                            }


                        })

                    } else {

                        $ionicLoading.hide();

                        var alertPopup = $ionicPopup.alert({
                            title: 'Hata',
                            template: 'Şifre alanları uyuşmamaktadır.'
                        });


                    }

                } else {

                    var alertPopup = $ionicPopup.alert({
                        title: 'Hata',
                        template: 'Kullanıcı Adınız en az 3 karakterli olmalıdır.'
                    });

                }
            } else {

                var alertPopup = $ionicPopup.alert({
                    title: 'Hata',
                    template: 'Şifre Alanı en az 6 karakterli olmalıdır.'
                });
            }


        } else {

            $ionicLoading.hide();
            var alertPopup = $ionicPopup.alert({
                title: 'Hata',
                template: 'Alanları eksiksiz doldurunuz.'
            });
        }

        $ionicLoading.hide();

    }


    $ionicModal.fromTemplateUrl('my-modal.html', {
        scope: $scope,
        animation: 'splat'
    }).then(function(modal) {
        $scope.modal = modal;
    });

    $ionicModal.fromTemplateUrl('my-modal-sozlesme.html', {
        scope: $scope,
        animation: 'splat'
    }).then(function(modal) {
        $scope.modalSozlesme = modal;
    });

    $scope.openModal = function() {
        $scope.modal.show();
    };

    $scope.closeModal = function() {
        $scope.modal.hide();
    };

    // Cleanup the modal when we're done with it!
    $scope.$on('$destroy', function() {
        $scope.modal.remove();
    });
    // Execute action on hide modal
    $scope.$on('modal.hidden', function() {
        // Execute action
    });
    // Execute action on remove modal
    $scope.$on('modal.removed', function() {
        // Execute action
    });


}])

.controller('uploadImgCtrl', ['$scope', '$ionicLoading', '$state', '$ionicHistory', 'webMethod', '$ionicPopup', '$cordovaCamera', function($scope, $ionicLoading, $state, $ionicHistory, webMethod, $ionicPopup, $cordovaCamera) {

    $scope.chooseOption = function() {
        var myPopup = $ionicPopup.show({
            title: 'Fotoğraf yükleme seçenekleri',
            //subTitle: 'Please use normal things',
            //scope: $scope,
            buttons: [{
                    text: '<b class="myclose">Kamera</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        var options = {
                            quality: 75,
                            destinationType: Camera.DestinationType.DATA_URL,
                            sourceType: Camera.PictureSourceType.CAMERA,
                            allowEdit: true,
                            encodingType: Camera.EncodingType.JPEG,
                            targetWidth: 300,
                            targetHeight: 300,
                            popoverOptions: CameraPopoverOptions,
                            saveToPhotoAlbum: false
                        };

                        $cordovaCamera.getPicture(options).then(function(imageData) {
                            $scope.imgURI = "data:image/jpeg;base64," + imageData;
                            $scope.img_url = "data:image/jpeg;base64," + imageData;

                        }, function(err) {
                            // An error occured. Show a message to the user
                        });
                    }
                },
                {
                    text: '<b class="myclose">Galeri</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        var options = {
                            quality: 75,
                            destinationType: Camera.DestinationType.DATA_URL,
                            sourceType: Camera.PictureSourceType.PHOTOLIBRARY,
                            allowEdit: true,
                            encodingType: Camera.EncodingType.JPEG,
                            targetWidth: 300,
                            targetHeight: 300,
                            popoverOptions: CameraPopoverOptions,
                            saveToPhotoAlbum: false
                        };

                        $cordovaCamera.getPicture(options).then(function(imageData) {
                            $scope.imgURI = "data:image/jpeg;base64," + imageData;
                            $scope.img_url = "data:image/jpeg;base64," + imageData;

                        }, function(err) {
                            // An error occured. Show a message to the user
                        });
                    }
                },
                {
                    text: '<i class="icon ion-edit myclose"></i>'
                }
            ]
        });

    }

    $scope.submitUpload = function() {
        webMethod.uploadImg(window.localStorage.getItem("user_id"), $scope.img_url).then(function(response) {

            $ionicLoading.hide();

            if (response.data.status == true) {
                var alertPopup = $ionicPopup.alert({
                    title: 'Yüklendi',
                    template: response.data.message
                });

                $state.go("login");

            } else {

                var alertPopup = $ionicPopup.alert({
                    title: 'Hata',
                    template: response.data.message
                });

            }


        })
    }

}])


.controller('chatUsersCtrl', ['$rootScope', '$scope', '$ionicLoading', '$state', 'socket', '$cordovaSQLite', 'webMethod', '$filter', function($rootScope, $scope, $ionicLoading, $state, socket, $cordovaSQLite, webMethod, $filter) {
        $scope.user_img = '';
        $ionicLoading.show({
            templateUrl: "templates/loading.html"
        });
        $ionicLoading.hide();
        $rootScope.allUsers = [];

        webMethod.getUsers(window.localStorage.getItem("user_id")).then(function(response) {
            $ionicLoading.hide();

            angular.forEach(response.data.users, function(val, key) {


                $rootScope.allUsers.push(val);
            });
        });

        console.log(window.localStorage.getItem("username"));


    }])
    .controller('chatSupportCtrl', function($scope, $ionicLoading, $state, $stateParams, socket, $cordovaSQLite, webMethod, $filter, $ionicScrollDelegate, $timeout) {
        $scope.sender_user_id = $stateParams.id;
        $scope.user_id = window.localStorage.getItem("user_id");
        $ionicLoading.show({
            templateUrl: "templates/loading.html"
        });

        $scope.user_info = {
            "ad": "",
            "password": ""
        };

        webMethod.userInfo($scope.sender_user_id).then(function(response) {
            $ionicLoading.hide();

            $scope.user_info.ad = response.data.user;
        });

        console.log($scope.user_info.ad);

        $ionicLoading.hide();
        $scope.allSessions = [];
        $scope.message = [];

        socket.on('send_message_' + window.localStorage.getItem("user_id"), function(data) {

            $scope.allSessions.push({
                alici_id: data.alici_id,
                gonderen_id: data.gonderen_id,
                mesaj: data.mesaj,
                tarih: data.tarih
            });
        });

        webMethod.getAllMessages(window.localStorage.getItem("user_id"), $stateParams.id).then(function(response) {

            $ionicLoading.hide();

            angular.forEach(response.data.msg, function(val, key) {
                $scope.allSessions.push(val);
            });

            $timeout(function() {
                $ionicScrollDelegate.scrollBottom();
            });

        });




        $scope.sendPush = function(mesaj) {
            var dates = $filter('date')(new Date(), 'dd-MM-yyyy HH:mm:ss');
            var msg = new Object();
            msg.gonderen_id = window.localStorage.getItem("user_id");
            msg.alici_id = $scope.sender_user_id;
            msg.mesaj = mesaj;
            msg.tarih = dates;
            socket.emit('get_message', msg);
            $scope.userMessage = null;

            $scope.allSessions.push({
                alici_id: $scope.sender_user_id,
                gonderen_id: window.localStorage.getItem("user_id"),
                mesaj: mesaj,
                tarih: dates
            });



        }





    })


/*Fotoğrafı modal'da gösterir. Kullanımı:
<img ng-controller="MediaCtrl" ng-src="{{url}}" ng-click="showModal(url)">*/
.controller('MediaCtrl', ['$scope', '$ionicModal', function($scope, $ionicModal) {


    $scope.showModal = function() {
        console.log("tiklandi");
        $ionicModal.fromTemplateUrl('templates/image_popover.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
            $scope.modal.show();
        });
    }

    // Close the modal
    $scope.closeModal = function() {
        $scope.modal.hide();
        $scope.modal.remove()
    };
}])