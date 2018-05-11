(function(){
	if(typeof jQuery == 'undefined'){
		var script = document.createElement( "script" );
		script.type = "text/javascript";
		script.src = "https://app.ads4xxx.com/js/jquery-3.2.1.min.js";
		script.onload = function () {
			$('head').append($('<script>', {
				type:'text/javascript',
				src:'https://app.ads4xxx.com/js/banner.min.js'
			}));
                        $('head').append($('<script>', {
				type:'text/javascript',
				src:'https://app.ads4xxx.com/js/js-cookie.js'
			}));
		};
		document.getElementsByTagName("head")[0].appendChild(script);
	}
})();