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
            //var html_result = handle_result(data);
            //$("#dbQueryResult").html(html_result);
			handle_result(data);
        },
        error: function(error) {
        	console.log(error);
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});


function handle_result(data)
{
	if(data === true)
	{
		$("#dbQueryResult").append("<p>Success! :)</p>");
		return;
		//return "<p>Success! :)</p>";
	}
	else if(data === false)
	{
		$("#dbQueryResult").append("<p>Failure! ;(</p>");
		return;
		//return "<p>Failure! ;(</p>";
	}

	//return make_table(data);
	make_table(data);
}


function make_table(data)
{
	var header = true;
	var target = $("#dbQueryResult");
	target.append("<table><tr></tr></table>");
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
			line = line + "<th>" + obj[key1] + "</th>";
		}
		line = line + "</tr>";
		target.append(line);
	}
}