(function(){
	var url = 'http://localhost/native-optimize/public';
	// var url = 'https://app.ads4xxx.com';
	var version = new Date().getMilliseconds();//'0005';
	if(typeof jQuery == 'undefined'){
		var script = document.createElement( "script" );
		script.type = "text/javascript";
		script.src = url + "/js/jquery-3.2.1.min.js";
		script.onload = function () {
			$('head').append($('<script>', {
				type:'text/javascript',
				src: url + '/js/js-cookie.js?v=' + version
			}));
			$('head').append($('<script>', {
				type:'text/javascript',
				src: url + '/js/nativeop51.min.js?v=' + version
			}));
		};
		document.getElementsByTagName("head")[0].appendChild(script);
	} else {
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = url + '/js/js-cookie.js?v=' + version;
		document.head.appendChild(script);
		script = document.createElement("script");
		script.type = "text/javascript";
		script.src = url + '/js/nativeop51.min.js?v=' + version;
		document.head.appendChild(script);
	}
})();
