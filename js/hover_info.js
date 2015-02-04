// list of data for all hovered ads
var info = {};

$(document).ready( function(event) {
	$('area').hover( function(event) {
		showInfo(this, event);
	}, function() {
		hideInfo(this);
	});
});

function showPos(event, id) {
	var el, x, y;

	el = document.getElementById('PopUp');

	if (window.event) {
		x = window.event.clientX 
			+ document.documentElement.scrollLeft
			+ document.body.scrollLeft;
		y = window.event.clientY 
			+ document.documentElement.scrollTop 
			+ document.body.scrollTop;
	}
	else {
		x = event.clientX + window.scrollX;
		y = event.clientY + window.scrollY;
	}
	
	x -= 1;
	y += 1;

	el.style.left = x + "px";
	el.style.top = y + "px";
	el.style.display = "block";

	//info feed
	var target = $("#PopUp tr");
	target.find("#id").html(info[id].id);
	target.find("#name").html(info[id].name);
	target.find("#link").html(info[id].link);
	target.find("#coords").html(info[id].coords);
	target.find("#datestamp").html(info[id].datetime);

	// put img
	$("#picture").attr('src', info[id].filename);
}

function showInfo(element, event)
{
	var id = $(element).attr("alt");
	if(info[id] == undefined)
	{
		$.get(
			"controllers/hover_areas.php",
			{id: id},
			"application/json"	
		).done(function(data) {
			//show info bubble
			info[id] = JSON.parse(data);
			showPos(event, id);
		});	
	}
	else
	{
		// data has already been requested before
		showPos(event, id);
	}
}

function hideInfo(area)
{
	//hide info bubble
	$('#PopUp').hide();
}