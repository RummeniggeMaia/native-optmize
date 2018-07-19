(function(){
	// var url = 'http://localhost/native-optimize/public';
	var url = 'https://app.ads4xxx.com';
	var version = '0001';//new Date().getMilliseconds();
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
		$('head').append($('<script>', {
			type:'text/javascript',
			src: url + '/js/js-cookie.js?v=' + version
		}));
	}
})();
