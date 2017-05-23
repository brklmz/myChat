// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
var db;
angular.module('starter', ['ionic', 'starter.controllers', 'ngCordova', 'ngCordovaOauth', 'ngMap', 'ngSanitize', 'btford.socket-io'])

.run(function($ionicPlatform, $rootScope, $timeout, $ionicModal, $ionicPopup, $state, $cordovaGeolocation, $cordovaSQLite, socket) {

    $rootScope.streets = [{ id: "1", name: "Albemarle Street", time: "12 minutes", distance: "8 KM" }, { id: "2", name: "Bob Marley Way", time: "16 minutes", distance: "13 KM" }, { id: "3", name: "Ailsa Road", time: "19 minutes", distance: "15 KM" }]
    $rootScope.myroads = [{ id: "1", name: "Albemarle Street", time: "12 minutes", distance: "8 KM" }, { id: "2", name: "Bob Marley Way", time: "16 minutes", distance: "13 KM" }, { id: "3", name: "Ailsa Road", time: "19 minutes", distance: "15 KM" }, { id: "4", name: "Albemarle Street", time: "12 minutes", distance: "8 KM" }, { id: "5", name: "Bob Marley Way", time: "16 minutes", distance: "13 KM" }, { id: "6", name: "Ailsa Road", time: "19 minutes", distance: "15 KM" }, { id: "7", name: "Ailsa Road", time: "19 minutes", distance: "15 KM" }]
    $rootScope.camera = [{ id: "1", img: "img/001.png" }, { id: "2", img: "img/002.png" }, { id: "3", img: "img/003.png" }, { id: "4", img: "img/004.png" }, { id: "5", img: "img/005.png" }, { id: "6", img: "img/003.png" }, { id: "7", img: "img/001.png" }, { id: "8", img: "img/002.png" }]



    $rootScope.closeCamera = function() {
        $rootScope.close_this = true
    }
    $rootScope.activeItem = function(index) {
        $rootScope.active_itemcolor = index
    }
    $rootScope.showStreet = function() {
        $rootScope.available_street = true;
        $timeout(function() {
            var offsetHeight = document.getElementById('ava_street').offsetHeight;
            $rootScope.height = 'calc(100% - ' + offsetHeight + 'px)';
        }, 100)

    }
    $rootScope.goHome = function() {
        $rootScope.available_street = false;
        $rootScope.height = '100%'
    }
    $rootScope.active_detail = 1;

    $rootScope.activeTab = function(index) {
        $rootScope.active_detail = index;
        $rootScope.close_this = false
    }




    /*********************menu_modal**********************/
    $ionicModal.fromTemplateUrl('templates/menu_modal.html', function(modal) {
        $rootScope.menu_modal = modal;
    }, {
        scope: $rootScope,
        animation: 'slide-in-up'
    });

    $rootScope.openmenu_modal = function() {
        $rootScope.menu_modal.show();
    };

    /* */
    $rootScope.closemenu_modal = function() {
        $rootScope.menu_modal.hide();
    };
    $rootScope.$on('$destroy', function() {
        $rootScope.menu_modal.remove();
    });
    $rootScope.$on('modal.hidden', function() {
        // Execute action
    });

    /********************* task create *********************/
    $rootScope.create_task = function() {
        $state.go("app.create_task");
    };





    /********************* ionic   **********************/
    $ionicPlatform.ready(function() {
        // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
        // for form inputs)
        if (window.cordova && window.cordova.plugins.Keyboard) {
            cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
            cordova.plugins.Keyboard.disableScroll(true);

        }
        if (window.StatusBar) {
            // org.apache.cordova.statusbar required
            StatusBar.styleDefault();
        }



        var posOptions = {
            enableHighAccuracy: true,
            timeout: 2000,
            maximumAge: 0
        };

        $cordovaGeolocation.getCurrentPosition(posOptions).then(function(position) {
            $rootScope.lat = position.coords.latitude;
            $rootScope.longi = position.coords.longitude;


            console.log($rootScope.lat);

        }, function(err) {
            console.log(err);
        });



        if (window.cordova) {
            db = $cordovaSQLite.openDB({ name: "my.db", location: 'default' }); //device
            console.log("Android");
        } else {
            db = window.openDatabase("my.db", '1', 'my', 1024 * 1024 * 100); // browser
            console.log("browser");

        }





        //Hierarchy TEXT
        $cordovaSQLite.execute(db, 'CREATE TABLE IF NOT EXISTS Messages (id INTEGER PRIMARY KEY AUTOINCREMENT,message TEXT,added_date TEXT)');



    });
})

.config(['$stateProvider', '$urlRouterProvider', '$ionicConfigProvider', '$httpProvider', function($stateProvider, $urlRouterProvider, $ionicConfigProvider, $httpProvider) {
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    $ionicConfigProvider.tabs.position("bottom"); //Places them at the bottom for all OS
    $ionicConfigProvider.tabs.style("standard"); //Makes them all look the same across all OS

    $ionicConfigProvider.backButton.text('').previousTitleText('');
    $ionicConfigProvider.navBar.alignTitle('center');
    $stateProvider

        .state('app', {
        url: '/app',
        abstract: true,
        templateUrl: 'templates/menu.html',
        controller: 'AppCtrl'
    })

    .state('login', {
            url: '/login',
            templateUrl: 'templates/login.html',
            controller: 'loginCtrl'
        })
        .state('logout', {
            url: '/logout',
            templateUrl: 'templates/login.html',
            controller: 'LogoutCtrl'
        })
        .state('register', {
            url: '/register',
            templateUrl: 'templates/register.html',
            controller: 'SignUpCtrl'
        })
        .state('app.profile', {
            url: '/profile',
            views: {
                'menuContent': {
                    templateUrl: 'templates/profile.html',
                    controller: 'profileCtrl'
                }
            }
        })

    .state('app.chat-support', {
            url: '/chat-support/:id',
            views: {
                'menuContent': {
                    templateUrl: 'templates/chat_support.html',
                    controller: 'chatSupportCtrl'
                }
            }
        })
        .state('app.chat-users', {
            url: '/chat-users',
            cache: false,
            views: {
                'menuContent': {
                    templateUrl: 'templates/chat_users.html',
                    controller: 'chatUsersCtrl'
                }
            }
        })
        .state('upload_img', {
            url: '/upload_img',
            templateUrl: 'templates/upload_img.html',
            controller: 'uploadImgCtrl'
        })
        .state('app.list', {
            url: '/list',
            views: {
                'menuContent': {
                    templateUrl: 'templates/list.html'
                }
            }
        });
    // if none of the above states are matched, use this as the fallback
    $urlRouterProvider.otherwise('/login');
}])