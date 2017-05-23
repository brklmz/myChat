app.factory('carServ',function($rootScope, $http, $state, $ionicLoading,UIService,SQLite_SERVICE,$stateParams,UtilsService) {

	var server = "http://api.carhub.com/PublicApi.ashx";
	var req = {
	 method: 'POST',
	 url: server,
	 headers: {
	   'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
	 },
	 data: ""
	};
	return{
		signUp:function(param){
			UIService.loadingMessage("Waiting service");
			req.data= UtilsService.carServDataFormat(param,'Quick');

		    var $promise = $http(req);
		    $promise.then(function (msg) {
		    	if(msg.data.Data === null){
					$ionicLoading.hide();
		    		UIService.popAlert("SERVICE ERROR",msg.data.ErrorMessage);
		    		return;
		    	}
				$ionicLoading.hide();
				$state.go("signupcheckactive");				   
			}, function(reason) {
				alert("hatali")
				$ionicLoading.hide();
			});
		},
		quickSignUp:function(param){
			UIService.loadingMessage("Waiting service");
			req.data=UtilsService.carServDataFormat(param,'QuickSignUp');

		    var $promise = $http(req);
		    $promise.then(function (msg) {
		    	if(msg.data.Data === null){
					$ionicLoading.hide();
		    		UIService.popAlert("SERVICE ERROR",msg.data.ErrorMessage);
		    		return;
		    	}
				$ionicLoading.hide();
				$state.go("signupcheckactive");				   
			}, function(reason) {
				alert("hatali")
				$ionicLoading.hide();
			});
		},
		getMemberInfo:function(param){
			UIService.loadingMessage("Waiting service");
			req.data=UtilsService.carServDataFormat(param,'GetMemberInfo')

		    var $promise = $http(req);
		    return $promise;
		   /* $promise.then(function (msg) {
		    	if(msg.data.Data === null){
					$ionicLoading.hide();
		    		UIService.popAlert("SERVICE ERROR",msg.data.ErrorMessage);
		    		return;
		    	}
				//Do somethings	
				$ionicLoading.hide();			   
			}, function(reason) {
		    	alert(JSON.stringify(reason));
				$ionicLoading.hide();
			});*/
		},
		getProfileInfo:function(param){
			UIService.loadingMessage("Waiting service");
			req.data=UtilsService.carServDataFormat(param,'GetProfileInfo')
		    var $promise = $http(req);
		    return $promise;
		    /*$promise.then(function (msg) {
		    	if(msg.data.Data === null){
					$ionicLoading.hide();
		    		UIService.popAlert("SERVICE ERROR",msg.data.ErrorMessage);
		    		return;
		    	}
				//Do somethings	
				$ionicLoading.hide();			   
			}, function(reason) {
		    	alert(JSON.stringify(reason));
				$ionicLoading.hide();
			});*/
		},
		changeMemberPassword:function(param){
			UIService.loadingMessage("Waiting service");
			req.data=UtilsService.carServDataFormat(param,'ChangeMemberPassword');
		    var $promise = $http(req);
		    return $promise;
		},
		changeMemberEmail:function(param){
			UIService.loadingMessage("Waiting service");
			req.data=UtilsService.carServDataFormat(param,'ChangeMemberEmail');
		    var $promise = $http(req);
		    return $promise;
		},
		getMakes: function (param) {
			UIService.loadingMessage("Waiting service");
			req.data= UtilsService.carServDataFormat(param,'GetMakes');
		    var $promise = $http(req);
		    return $promise;
		},
		getModels: function (param) {
			UIService.loadingMessage("Waiting service");
			req.data= UtilsService.carServDataFormat(param,'GetModels');
		    var $promise = $http(req);
		    return $promise;
		},
		islogged:function(){
		    var $checkSessionServer = $http.post(server+'Serv.ashx?cmd=logincheck');
			return $checkSessionServer
		},
		imageUpload:function(param){
			var req = {
			 method: 'POST',
			 url: server,
			 headers: {
			   'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			 },
			 data: param
			};
		},
		checkService:function(){

			$http.get('http://demo.carhub.com/ListMyCar.aspx&SessionId=' + msg.data.Data.SessionId).then(function(resp) {
			    console.log('Success', resp);
			    // For JSON responses, resp.data contains the result
			  }, function(err) {
			    console.error('ERR', err);
			    // err.status will contain the status code
			 })
		}
	}

});