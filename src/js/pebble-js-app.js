Pebble.addEventListener("ready",
   function(e) {
      Pebble.timelineSubscribe('all-users',
         function () {
            console.log('Subscribed to all-users');
         },
         function (errorString) {
            console.log('Error subscribing to topic: ' + errorString);
         }
     );
   }
);