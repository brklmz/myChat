$(document).on("click", ".kaydet", function() {
    var msg = $('#msgg').val();


    console.log(msg);

    $.ajax({
        url: "http://localhost:8000/chat/",
        type: 'POST',
        dataType: "json",
        data: {
            'data': msg
        },
        success: function(result) {
            console.log(result);

        }
    })
});