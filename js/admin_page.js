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
            var html_result = handle_result(data);
            $("#dbQueryResult").html(html_result);
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
		return "<p>Success! :)</p>";
	}
	else if(data === false)
	{
		return "<p>Failure! ;(</p>";
	}

	return make_table(data);
}


function make_table(data)
{
	return "<p> TODO </p>";
}