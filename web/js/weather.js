$("#validzip").click( function() {

	 let zip = $("#zipcode").val();

	$.ajax({url: "http://api.openweathermap.org/data/2.5/weather?zip=" + zip + ",fr&APPID=9acd5346505f852d4b01beb1c1315a60",

		error: function() {

			$("#city").html("Code Postal invalide !");
			$("#temperature").html("Code Postal invalide !");
			$("#tendance").html("Code Postal invalide !");
			$("#humidity").html("Code Postal invalide !");
			$("#wind").html("Code Postal invalide !");
		},

		dataType: "jsonp",

		success: function(data) {

			$("#city").html(data.name); 
			$("#temperature").html(Math.floor(data.main.temp - 273) + " °C");
			$("#tendance").html(data.weather[0].main);
			$("#humidity").html(data.main.humidity + " %");
			$("#wind").html(data.wind.speed + " m/s");
		},

			type: "GET"	
	})
});


