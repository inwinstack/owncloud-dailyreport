var baseUrl = OC.generateUrl('/apps/usage_amount');

$.ajax({
    url: baseUrl + '/usages',
    type: 'GET',
    contentType: 'application/json',
    data: []
}).done(function (response) {
    // handle success
    if (response.length > 0){
        response.map(function(data){
            var append_string = "<tr><td>" + data.display_name + "</td>" + "<td>" + data.email + "</td>" +
              "<td>" + Math.round((data.user_usage/1024)*100)/100 + " MB" + "</td>" + 
              "<td>" + Math.round((data.total/1024)*100)/100 + " MB" +"</td>" + 
              "<td>" + data.created_at + "</td></tr>";
            $("#history tbody").append(append_string);          
        });
    }else{
        $("#history tbody").html("<tr><td colspan='5' >查無使用者用量歷史資訊</td></tr>");
    }
}).fail(function (response, code) {
    // handle failure
});