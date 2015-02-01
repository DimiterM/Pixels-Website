var canvas = document.getElementById("coordsCanvas");
canvas.addEventListener("click", get_click_on_canvas, false);

var points = [];

function get_click_on_canvas(event)
{
	var x = Math.floor((event.pageX - this.offsetLeft + this.scrollLeft) / 10) * 10;
	var y = Math.floor((event.pageY - this.offsetTop + this.scrollTop) / 10) * 10;

	points.push([x, y]);

	canvas.width = canvas.width;
	var context = canvas.getContext('2d');
	context.fillStyle = "#FFFFFF";
	context.fillRect(x, y, 10, 10);
	context.stroke();

	if(points.length == 2)
	{
		var topX = Math.min(points[0][0], points[1][0]);
		var topY = Math.min(points[0][1], points[1][1]);
		var width = Math.abs(points[1][0] - points[0][0]) + 10;
		var height = Math.abs(points[1][1] - points[0][1]) + 10;

		context.fillStyle = "#FFFFFF";
		context.fillRect(topX, topY, width, height);
		context.stroke();
		
		var coords = [
			[topX, topY], 
			[topX + width, topY], 
			[topX + width, topY + height], 
			[topX, topY + height], 
			[topX, topY]
		];
		coords = coords.map(function(x) { return x[0] + " " + x[1]; });

		document.forms[0].elements['coords'].value = "POLYGON((" + coords.join(", ") + "))";
		document.forms[0].elements['qty'].value = (width / 10) * (height / 10);

		points = [];
	}
}