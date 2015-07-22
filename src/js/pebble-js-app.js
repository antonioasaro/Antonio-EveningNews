Pebble.addEventListener("ready",
	function(e) {
		Pebble.getTimelineToken(
  			function (token) {
   				console.log('My timeline token is ' + token);
  			},
  			function (error) { 
    			console.log('Error getting timeline token: ' + error);
  			}
		);  
      	Pebble.timelineSubscribe('all-users',
        	function () {
            	console.log('Subscribing to all-users');
         	},
         	function (errorString) {
            	console.log('Error subscribing to topic: ' + errorString);
         	}
     	);
		Pebble.timelineSubscriptions(
  			function (topics) {
				console.log('Subscription list: ' + topics.join(', '));
  			},
  			function (errorString) {
    			console.log('Error getting subscriptions: ' + errorString);
  			}
		);
   	}
);