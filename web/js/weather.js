$("#validzip").click( function() {

	 let zip = $("#zipcode").val();

	$.ajax({url: "http://api.openweathermap.org/data/2.5/weather?zip=" + zip + ",fr&APPID=9acd5346505f852d4b01beb1c1315a60",

		error: function() {

			$("#city").html("error");
			$("#temperature").html("error");
			$("#tendance").html("error");
			$("#humidity").html("error");
			$("#wind").html("error");
		},

		dataType: "jsonp",

		success: function(data) {

			$("#city").html(data.name); 
			$("#temperature").html(Math.floor(data.main.temp - 273));
			$("#tendance").html(data.weather[0].main);
			$("#humidity").html(data.main.humidity);
			$("#wind").html(data.wind.speed);
		},

			type: "GET"	
	})
});


