var http = require('http');
var html_strip = require('htmlstrip-native');
var url = 'http://192.168.0.14'

var options = {
    include_script : false,
    include_style : false,
    compact_whitespace : true,
  include_attributes : { 'alt': true }
};


http.get(url, function(response) {
  parseResponse(response);
})

var parseResponse = function(response) {
  var data = "";
  response.on('data', function(chunk) {
    data += chunk;
  });
  response.on('end', function(chunk) {

	var text = html_strip.html_strip(data,options);
	var pm25=text.indexOf('PM 2.5');
	var pm10=text.indexOf('PM 1.0');
	var pm100=text.indexOf('PM 10');
	var pc=text.indexOf('Particle counters');
	var hum=text.indexOf('Humidity');
	var pressure=text.indexOf('Pressure');
	if(pm25 > -1) {
		console.log("PM 2.5 "+text.substr(pm25+7,pm10-pm25-6-8));
	}
	if(pm10 > -1) {
		console.log("PM 1.0 "+text.substr(pm10+13,pm100-pm10-2));
	}
	if(pm100 > -1) {
		console.log("PM 10  "+text.substr(pm100+18,pc-pm100-24));
	}
	if(hum > -1) {
		console.log("Hum    "+text.substr(hum+9,2));
	}
	if(pressure > -1) {
		console.log("Pres   "+text.substr(pressure+9,pm25-pressure-14));
	}
  });

}
 

