(function(){
	if(typeof jQuery == 'undefined'){
		var script = document.createElement( "script" );
		script.type = "text/javascript";
		script.src = "http://localhost/native-optimize/public/js/jquery-3.2.1.min.js";
		script.onload = function () {
			$('head').append($('<script>', {
				type:'text/javascript',
				src:'http://localhost/native-optimize/public/js/nativeop51.min.js'
			}));
		};
		document.getElementsByTagName("head")[0].appendChild(script);
	}
})();