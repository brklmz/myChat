app.directive('map', function(geoService,UIService) {
  var  someObject ={ 
    origin: {lat: 40.6939973, lng: 30.4357631},
    destination: {lat: 41.0480556, lng: 29.0236111}
  };
  return {
    restrict: 'E',
    scope: {
      onCreate: '&',
      data:'@data'
    },
    link: function ($scope, $element, $attr) {
      function initialize() {
        UIService.loadingMessage('Loading');
        var x = JSON.parse($scope.data);
        if(x.type === 'location'){

          try{
            var mapOptions = {
              center: new google.maps.LatLng(x.myPos),
              zoom: 16,
              disableDefaultUI: true
            };
            var map = new google.maps.Map($element[0], mapOptions);
            var marker = new google.maps.Marker({
              position: x.myPos, 
              map: map,
              icon: 'img/findMyCarPinIcon.png'
            });
            var m = new google.maps.Marker({
              position: x.center, 
              map: map,
              icon: 'img/currentPos.png'
            });
            $scope.onCreate({map: map});

            // Stop the side bar from dragging when mousedown/tapdown on the map
            google.maps.event.addDomListener($element[0], 'mousedown', function (e) {
              e.preventDefault();
              return false;
            });

          }
          catch(error){
            alert(error)
          }
        }
        else if(x.type === 'trip'){

          try{
            var mapOptions = {
              center: new google.maps.LatLng(x.origin),
              zoom: 16,
              disableDefaultUI: true,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map($element[0], mapOptions);
           // map.marker = geoService.marker(coords);
            var directionsDisplay = new google.maps.DirectionsRenderer({
              map: map
            });

            // Set destination, origin and travel mode.
            var request = {
              destination: x.destination,
              origin: x.origin,
              travelMode: google.maps.TravelMode.DRIVING
            };

            // Pass the directions request to the directions service.
            var directionsService = new google.maps.DirectionsService();

            directionsService.route(request, function(response, status) {
              if (status == google.maps.DirectionsStatus.OK) {
                // Display the route on the map.
                directionsDisplay.setDirections(response);
              }
            });
            $scope.onCreate({map: map});

            // Stop the side bar from dragging when mousedown/tapdown on the map
            google.maps.event.addDomListener($element[0], 'mousedown', function (e) {
              e.preventDefault();
              return false;
            });

          }
          catch(error){
            alert(error)
          }
        }
        else if(x.type === 'place'){
          try{
            var map;
            var infowindow;
            var placeList =[];

            map = new google.maps.Map($element[0], {
              center: x.center,
              disableDefaultUI: true,
              zoom: 14
            });

            infowindow = new google.maps.InfoWindow(); 
            var service = new google.maps.places.PlacesService(map);
            service.nearbySearch({
              location: x.center,
              radius: 1000,
              types: ['car_repair']
            }, callback);
            function callback(results, status) {
              if (status === google.maps.places.PlacesServiceStatus.OK) {
                for (var i = 0; i < results.length; i++) {
                  createMarker(results[i]);
                  var request = {
                    placeId: results[i].place_id
                  };

                  service.getDetails(request, function (place, sts) {
                    if (sts == google.maps.places.PlacesServiceStatus.OK) {
                    var y = place.geometry.location;
                    var distance = mesafe_hesapla(x.center.lat
                      ,x.center.lng
                      ,y.lat()
                      ,y.lng(),'K');
                    var placeDetail = {
                      type:'placeDetail',
                      place: place,
                      distance: distance
                    };
                    placeList.push(placeDetail);
                  }
                  //Kendi konumunu bul.
                  var marker = new google.maps.Marker({
                    map: map,
                    position: x.center,
                    icon: 'img/currentPos.png'
                  });

                  $scope.onCreate({map: placeList});
                });
              }
            }
          }
            function mesafe_hesapla(lat1, lon1, lat2, lon2, unit) {
              var radlat1 = Math.PI * lat1/180
              var radlat2 = Math.PI * lat2/180
              var radlon1 = Math.PI * lon1/180
              var radlon2 = Math.PI * lon2/180
              var theta = lon1-lon2
              var radtheta = Math.PI * theta/180
              var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
              dist = Math.acos(dist)
              dist = dist * 180/Math.PI
              dist = dist * 60 * 1.1515
              if (unit=="K") { dist = dist * 1.609344 }
              if (unit=="N") { dist = dist * 0.8684 }
              return (Math.round(dist*1000)/1000);
            }
            var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
            function createMarker(place) {
              var marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location,
                icon: 'img/placeMarker.png'
              });
              google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(place.name);
                infowindow.open(map, this);
              });

            }
          }
          catch(error){
            alert(error)
          }
        }
        else if (x.type ==='placeDetail'){
          try{

              var map = new google.maps.Map($element[0], {
                center: x.center,
                zoom: 14,
                disableDefaultUI: true
              });
              var infowindow = new google.maps.InfoWindow();
              var service = new google.maps.places.PlacesService(map);
              var request = {
                placeId: x.placeId
              };
              var m = new google.maps.Marker({
                map: map,
                position: x.center,
                icon: 'img/currentPos.png'
              });
              service.getDetails(request, function (place, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                  // If the request succeeds, draw the place location on the map
                  // as a marker, and register an event to handle a click on the marker.
                  var marker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location,
                    icon: 'img/placeMarker.png'
                  });

                  google.maps.event.addListener(marker, 'click', function() {
                    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' +
                      'Place ID: ' + place.place_id + '<br>' +
                      place.formatted_address + '</div>');
                    infowindow.open(map, this);
                  });

                  map.panTo(place.geometry.location);
                  $scope.onCreate({map: place});
                }
              });

            // Run the initialize function when the window has finished loading.
            google.maps.event.addDomListener(window, 'load', initialize);
          }
          catch(error){
            alert(error)
          }

        }
        
      }

      if (document.readyState === "complete") {
        initialize();
      } else {
        google.maps.event.addDomListener(window, 'load', initialize);
      }
    }
  }
});
