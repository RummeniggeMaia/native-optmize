$(document).ready(function () {
	url = 'https://app.ads4xxx.com/';
	wg = $('#nativeop51-list').data('wg');
	if (wg === null) {
		console.log('Native Optimize não encontrado.');
		return;
	}
	var cont = Cookies.get('nativeop51');
	if (cont) {
		cont++;
	} else {
		var date = new Date();
		date.setTime(date.getTime() + (60 * 60 * 1000));
		Cookies.set('nativeop51', 1, {
			expires: date,
			path: '/'
		});
		cont = 1;
	}
	$.ajax({
		dataType: "json",
		accepts: "application/json",
		method: 'GET',
		url: url + '/random_creatives?wg=' + wg + '&cont=' + cont
	}).done(function (x) {
		con = $('<div>');
		ul = $('<ul>').css({
			'list-style-type': 'none',
			'margin': 0,
			'padding': 0,
		});
		$.each(x, function (i, v) {
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
							wg: wg
						}
					});
				}
			}).css({
				'color': 'white',
				'text-decoration': 'none',
				'font-weight': 'bold',
			}).hover(function () {
				$(this).css({
					'color': 'blue'
				});
			}, function () {
				$(this).css({
					'color': 'white'
				});
			});
			img = $('<img>', {
				src: v.image,
				width: 230,
				height: 180,
			});
			li = $('<li>').css({
				'position': 'relative',
				'float': 'left',
				'margin': '0px 0px 0px 5px'
			});
			box1 = $('<div>').css({
				'width': '230px',
				'height': '275px',
				'border': 'none',
				'padding': '1px',
				'background-color': '#181818',
			});
			box1.append(a.clone(true, true).append(img));
			li.append(box1);
			ul.append(li);
		});
		con.append(ul);
		$('#nativeop51-list').append(con);
	}).fail(function () {
		console.log('Native Optimize: Widget não encontrado no sistema.');
	});
});