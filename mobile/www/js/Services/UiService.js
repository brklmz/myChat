app.factory('UIService', ['$ionicLoading', '$ionicPopup','$q','$ionicModal', '$state','$rootScope','$ionicHistory',function($ionicLoading, $ionicPopup,$q,$ionicModal,$state,$rootScope,$ionicHistory) {
        
        var popAlert= function (title, message) {
            $ionicPopup.alert({ title: title, template: message });
            //alert(message);
        };
        return {
            checkLogin: function($scope){
                var deferred = $q.defer();
                //return;
                $scope.$on('$ionicView.enter', function(){
                    if($rootScope.login === false){
                        deferred.reject(false);
                        $ionicLoading.hide();
                        popAlert("ERROR","Plase log in");
                        $state.go('login');
                    }
                    else{                        
                         deferred.resolve(true);
                    }
                });                
                return deferred.promise;
            },
            loadingMessage: function (msg) {
                $ionicLoading.show({ template: msg + '... <ion-spinner></ion-spinner>'});
            },
            loadingMessageTemp: function (url) {
                $ionicLoading.show({ templateUrl: url });
            },
            closeLoading: function() {
                $ionicLoading.hide();
            },
            popAlert:popAlert,
            popConfirm : function(title,message){
                var deferred = $q.defer();
                var confirmPopup = $ionicPopup.confirm({
                      title: title,
                      template: message
                    });
                    confirmPopup.then(function(res) {
                      if(res) {
                         deferred.resolve(res);
                      } else {
                         deferred.reject(res);
                      }
                    });
                return deferred.promise;
            },
            openModal : function(url,$scope){
                $ionicModal.fromTemplateUrl(url, {
                    scope: $scope,
                    animation: 'slide-in-up'
                }).then(function(modal) {
                    $scope.modal = modal;
                    $scope.modal.show();
                });
            },
            closeModal : function($scope) {
                $scope.modal.hide();
                $scope.$on('$destroy', function() { $scope.modal.remove(); });
            }        
        };
    }]);