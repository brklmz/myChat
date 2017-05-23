

 app.filter('to_trusted', ['$sce',function($sce) {
  return function(value, type) {
    return $sce.trustAs(type || 'html', value);
  }
}]);

 app.filter('millSecondsToTimeString', function() {
//Returns duration from milliseconds in hh:mm:ss format.
  return function(millseconds) {
    var oneSecond = 1000;
    var oneMinute = oneSecond * 60;
    var oneHour = oneMinute * 60;
    var oneDay = oneHour * 24;

    var seconds = Math.floor((millseconds % oneMinute) / oneSecond);
    var minutes = Math.floor((millseconds % oneHour) / oneMinute);
    var hours = Math.floor((millseconds % oneDay) / oneHour);
    var days = Math.floor(millseconds / oneDay);

    var timeString = '';
    if (days !== 0) {
        timeString += (days !== 1) ? (days + ' gün ') : (days + ' gün ');
    }
    if (hours !== 0) {
        timeString += (hours !== 1) ? (hours + ' saat ') : (hours + ' saat ');
    }
    if (minutes !== 0) {
        timeString += (minutes !== 1) ? (minutes + ' dakika ') : (minutes + ' dakika ');
    }
    if (seconds !== 0 || millseconds < 1000) {
        timeString += (seconds !== 1) ? (seconds + ' saniye ') : (seconds + ' saniye ');
    }

    return timeString;
};
});

  app.filter('millSecondsToTimeStringHome', function() {
//Returns duration from milliseconds in hh:mm:ss format.
  return function(millseconds) {
    var oneSecond = 1000;
    var oneMinute = oneSecond * 60;
    var oneHour = oneMinute * 60;
    var oneDay = oneHour * 24;

    var seconds = Math.floor((millseconds % oneMinute) / oneSecond);
    var minutes = Math.floor((millseconds % oneHour) / oneMinute);
    var hours = Math.floor((millseconds % oneDay) / oneHour);
    var days = Math.floor(millseconds / oneDay);

    var timeString = '';
    if (days !== 0) {

        timeString += (days !== 1) ? (days + ' gün ') : (days + ' gün ');

        if (hours !== 0) {
            timeString += (hours !== 1) ? (hours + ' saat ') : (hours + ' saat ');
        }

    }
    else if (hours !== 0) {

        timeString += (hours !== 1) ? (hours + ' saat ') : (hours + ' saat ');

        if (minutes !== 0) {
            timeString += (minutes !== 1) ? (minutes + ' dakika ') : (minutes + ' dakika ');
        }
    }
  
    else if (minutes !== 0) {

        timeString += (minutes !== 1) ? (minutes + ' dakika ') : (minutes + ' dakika ');

    }
    else {

         timeString += (seconds !== 1) ? (seconds + ' saniye ') : (seconds + ' saniye ');
         
    }

    return timeString;
};
});

app.filter('timeAgo', function() {
    return function(input) {
        if (input == null) return "";
        return jQuery.timeago(input);
    };
}) 

