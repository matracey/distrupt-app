//user data structure, to send to server
var User = {
};

var App = {
	startup: function() {
		$("#topheader").hide();
		$("#login-page").hide();	
		$("#chooseroute-page").hide();
		$("#choosecommute-page").hide();
		$("#whotoalert-page").hide();
		$("#confirmation-page").hide();
		console.log($(location).attr('pathname'));
		if($(location).attr('pathname') == '') App.initialiseTimePicker();

		//next buttons
		$("#login").on("click", function(e){
			App.loginpageClicked();
		});				
		
		$("#login-button").on("click", function(e){
			App.loginClicked();
		});

		$("#choosecommute-next-button").on("click", function(e){
			App.chooseCommuteNextClicked();
		});

		$("#chooseroute-next-button").on("click", function(e){
			App.chooseRouteNextClicked();
		});

		$("#whotoalert-next-button").on("click", function(e){
			App.whotoalertNextClicked();
		});


	},

	loginpageClicked: function() {
		$("#home").hide();
		
		$("#login-page").show();
		$("#topheader").show();
	},	
	
	loginClicked: function() {
		$("#login-page").hide();

		User.id = $('#Email').val();

		$("#choosecommute-page").show();
	},

	chooseCommuteNextClicked: function() {
		$("#choosecommute-page").hide();
		this.calcRoute();
	},

	directionsService: new google.maps.DirectionsService(),
	calcRoute: function() {
	  var start = $("#choosecommute input[name=start-postcode]").val() + " near london UK";
	  var end = $("#choosecommute input[name=end-postcode]").val() + " near london UK";
	  var time = $("#choosecommute input[name=form-time]").val();
	  var hhmm = time.split(':');

	  User.timeAtWork = new Date(2013, 9, 28, parseInt(hhmm[0]), parseInt(hhmm[1]), 0, 0);

	  console.log(parseInt(hhmm[0]),parseInt(hhmm[1]));

	  var request = {
	      origin:start,
	      destination:end,
	      travelMode: google.maps.DirectionsTravelMode.TRANSIT,
	      provideRouteAlternatives: true,
	      transitOptions: {
			  arrivalTime: User.timeAtWork
		  }
	  };

	  this.directionsService.route(request, function(response, status) {
	    console.log(response, status);
	    if (status == google.maps.DirectionsStatus.OK) {
	      App.directionCalculated(response);
	      //directionsDisplay.setDirections(response);
	    }
	  });
	},

	directionCalculated: function(response) {
		console.log(response.routes);

		var possibleRoutes = [];
		_.each(response.routes, function(route) {

			var trip = route.legs[0];

			var option = {
				duration: trip.duration.text,
				directions: []
			};

			_.each(trip.steps, function(step){
				if (step.travel_mode == "TRANSIT" && ( step.instructions.indexOf("Subway") != -1 || step.instructions.indexOf("Underground") != -1 || step.instructions.indexOf("Light rail") != -1)) {
					var obj = {
						from: step.transit.departure_stop.name,
						to: step.transit.arrival_stop.name,
						line: step.transit.line.name
					};

					option.directions.push(obj);
				}
			});

			if (option.directions.length > 0) {
				possibleRoutes.push(option);
			}
		});


		if (possibleRoutes.length > 2) {
			possibleRoutes.length = 2;
		}
		this.possibleRoutes = possibleRoutes;

		//HERE BE FUCKING DRAGONS
		//i'm trying to clean up duplicates, hopefully without fucking up
		/*var cloneRoutes = JSON.parse(JSON.stringify(possibleRoutes));
		_.each(cloneRoutes, function(r1) {
			var d1 = r1.directions;

			_.each(possibleRoutes, function(r2){
				var d2 = r2.directions;
				console.log(r1, r2, d1, d2);

				if (d1[0].line == d2[0].line) {
					if (!d1[1] || d1[1].line == d2[1].line) {
						r1.duplicated = true;
					}
				}
			});
		});

		console.log(cloneRoutes);
		for (var i=cloneRoutes.length-1 ; i>0 ; i--) {
			//kill at most one duplicate
			if (cloneRoutes[i].duplicated) {
				cloneRoutes = _.reject(cloneRoutes, function(r){
					return cloneRoutes[i] == r
				});
				return;
			}
		}

		console.log(possibleRoutes);

		this.possibleRoutes = cloneRoutes;*/

		$("#chooseroute-page").show();
		WriteHTML.populateRouteChoice(this.possibleRoutes);
	},

	selectRoute: function(i) {
		console.log(i, App.possibleRoutes[i]);

		User.transport = App.possibleRoutes[i];
	},

	chooseRouteNextClicked: function() {
		if (!User.transport) {
			return;
		}

		$("#chooseroute-page").hide();
		$("#whotoalert-page").show();

		User.email = [];
		User.sms = [];

		$("#add-email-button").on('click', function() {
			var email = $("#add-email-form input[name=add-email]").val();
			WriteHTML.writeOneNewEmail(email);
			User.email.push(email);
			var email = $("#add-email-form input[name=add-email]").val('');
		});

		$("#add-number-button").on('click', function() {
			var number = $("#add-number-form input[name=add-number]").val();
			WriteHTML.writeOneNewNumber(number);
			User.sms.push(number);
			$("#add-number-form input[name=add-number]").val('');
		});
	},

	whotoalertNextClicked: function() {
		$("#whotoalert-page").hide();
		
		var data = "data: " + JSON.stringify(User);
		$.post("http://london.disruptapp.co.uk/api/users", data, function(response) {

		}).always(function(){
			$("#confirmation-page").show();

			WriteHTML.populationConfirmationPage(User);
		});
	},
}

$(document).ready(function() {
	App.startup();
});

// _.uniqObjects = function( arr ){
// 	return _.uniq( _.collect( arr, function( x ){
// 		return JSON.stringify( x );
// 	}));
// };