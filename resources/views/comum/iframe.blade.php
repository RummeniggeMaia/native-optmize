<html>
	<head>
		<meta name="robots" content="nofollow">
		<meta name="googlebot" content="noindex">
		<script src="{{$url}}/js/jquery-3.2.1.min.js"></script>
		<script src="{{$url}}/js/js-cookie.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
				var url = '{{ $url }}/api';
				var wg = '{{ $widget_hashid }}';
				if (wg === null) {
					console.log('Native Optimize não encontrado.');
					return;
				}
				var cont = Cookies.get('banner51');
				if (cont) {
					cont++;
				} else {
					cont = 1;
				}
				var date = new Date();
				date.setTime(date.getTime() + (60 * 60 * 1000));
				Cookies.set('banner51', cont, {expires: date, path: '/'});
				$.ajax({
					dataType: "json",
					accepts: "application/json",
					method: 'GET',
					url: url + '/random_creatives?wg=' + wg + '&cont=' + cont
				}).done(function (x) {
					if (x != null && x.length> 0) {
						v = x[0];
						a = $('<a>', {
							title: v.name,
							href: v.url,
							target: '_blank',
							click: function () {
								$.post({
									url: url + '/clicks',
									dataType: "json",
									data: {
										click_id: v.click_id,
										ct: v.hashid,
										wg: wg,
										cp: v.campaign_id
									}
								});
							}
						});
						img = $('<img>', {
							src: v.image
						});
						$('#banner51').append(a.append(img));
					} else {
						console.log("NativeOptimize Banner: Nao ha anuncios");
					}
				}).fail(function () {
					console.log('NativeOptimize Banner: Widget não encontrado no sistema.');
				});
			});
		</script>
	</head>
	<body>
		<div id="banner51"></div>
	</body>
</html>