app.service('geoService', function ($q) {
  
  this.getCurrentPosition = function(){
    var deferred = $q.defer();
    var options = {
      enableHighAccuracy: false,
      timeout: 3000,
      maximumAge: 0
    };
    try{
        navigator.geolocation.getCurrentPosition(function (pos) {
        deferred.resolve = pos;

      }, function (error) {
        alert('Unable to get location: ' + error.message);
        deferred.reject(error);
      },options);
      /* var posOptions = {timeout: 10000, enableHighAccuracy: false};
        $cordovaGeolocation.getCurrentPosition(posOptions)
          .then(function (position) {
            deferred.resolve = position;
          }, function(err) {
            deferred.reject(error);
          });*/
    }catch(error){
      deferred.reject(error);
    }
    return deferred.promise;
  };

  this.watchPosition = function(){
    var deferred = $q.defer();
    try{
      var watchOptions = {
        timeout : 3000,
        enableHighAccuracy: false // may cause errors if true
      };

      var watch = $cordovaGeolocation.watchPosition(watchOptions);
      deferred.resolve = watch;
    }catch(error){
      deferred.reject(error);
    }
    return deferred.promise;
  };

  this.clearWatch = function(watch){
    var deferred = $q.defer();
    $cordovaGeolocation.clearWatch(watch)
    .then(function(result) {
        deferred.resolve = result;
      }, function (error) {
        deferred.reject(error);
    });
    return deferred.promise;
  }

  this.createMarker = function(coords){
    var markers ={};
    for (var i = 0; i < coords.length; i++) {
      markers.push({
        id:i+1,
        coords: coords[i]
      })
    };
    return markers;
  }
});
