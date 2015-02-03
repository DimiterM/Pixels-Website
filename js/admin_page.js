$(".dbQuery").submit(function(event){
	event.stopPropagation();
	event.preventDefault();

    var formData = new FormData(this);
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        async: true,
        success: function (data) {
            data = JSON.parse(data);
			handle_result(data);
			console.log(data);
        },
        error: function(error) {
        	$("#dbQueryResult").empty();
        	$("#dbQueryResult").append("<p>Error!!! :X</p>");
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});


function handle_result(data)
{
	$("#dbQueryResult").empty();

	if(data === true)
	{
		$("#dbQueryResult").append("<p>Success! :)</p>");
		return;
	}
	else if(data === false)
	{
		$("#dbQueryResult").append("<p>Failure! ;(</p>");
		return;
	}
	else if(data[0] === false)
	{
		$("#dbQueryResult").append("<p>Empty result set.</p>");
		return;
	}

	make_table(data);
}


function make_table(data)
{
	var header = true;
	var target = $("#dbQueryResult");
	target.append("<table></table>");
	target = target.find("table");
	for (var key in data)
	{
		var obj = data[key];
		if(header)
		{
			var header_code = "<tr>";
			for(var key1 in obj)
			{
				header_code = header_code + "<th>" + key1 + "</th>";
			}
			header_code = header_code + "</tr>";
			target.append(header_code);
			header = false;
		}
		
		var line = "<tr>";
		for(var key1 in obj)
		{
			line = line + "<td>" + obj[key1] + "</td>";
		}
		line = line + "</tr>";
		target.append(line);
	}
}


// put 'toggle' buttons to hide forms (for better visibility)
$(document).ready(function(){
	$("<button type=\"button\" class=\"toggler\">Toggle form</button>")
		.insertBefore(".dbQuery, .userForm");

	$(".toggler").click(function(){
		$(this).next().toggle();
	});
});