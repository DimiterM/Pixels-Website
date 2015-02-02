var info = {};

$(document).ready( function(){
    $('area').hover( function(){
		showInfo(this);
	}, function(){
		hideInfo(this);
	});
});

function showPos(event, id) {
	var el, x, y;

	el = document.getElementById('PopUp');
	if (window.event) {
		x = window.event.clientX + document.documentElement.scrollLeft
		+ document.body.scrollLeft;
		y = window.event.clientY + document.documentElement.scrollTop +
		+ document.body.scrollTop;
	}
	else {
		x = event.clientX + window.scrollX;
		y = event.clientY + window.scrollY;
	}
	x -= 2;
	y += 13;
	el.style.left = x + "px";
	el.style.top = y + "px";
	el.style.display = "block";
	//info feed
	var target = $("PopUp > ul > li");
	target.find("#id").innerHTML = info[id].id;
	target.find("#name").innerHTML = info[id].name;
	target.find("#link").innerHTML = info[id].link;
	target.find("#coords").innerHTML = info[id].coords;
	target.find("#datestamp").innerHTML = info[id].datestamp;
	target.find("#picture").attr('src', "/images/ads/"+filename);
}

function showInfo(id)
{
	var id = $(this).attr("alt");
	if( info[id] == undefined )
	{
		info[id] = $.get(
			'hover_areas.php',
			id		
		).done(function(data) {
		data = JSON.parse(data);
		});	
	}
	//show info bubble
	showPos(event, id);
}

function hideInfo(area)
{
	//hide info bubble
	$('#PopUp').style.display = 'none';
}