var apiUrl = '/api/v1';
var authToken = null;


$(document).ready(function () {


    $.getJSON(apiUrl + '/', function (response) {

        if (response.status == 200) {
            var data = response.data;

            authToken = data.token;

            $.ajaxSetup({
                headers: {
                    'X-Auth-Token': authToken
                }
            });
        }
    });

    $(document).on('submit', '#searchForm', function () {

        $.getJSON(
                apiUrl + '/goods/search',
                $(this).serialize(),
                function (response) {

                    if (response.status == 200) {
                        var data = response.data;

                        console.log(data);
                    }
                });

        return false;
    })
})


