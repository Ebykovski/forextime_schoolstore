(function () {
    var apiUrl = '/api/v1';
    var apiKey = '1111-2222-3333-4444';

    $(document).ready(function () {

        $.getJSON(
                apiUrl + '/',
                {
                    apiKey: apiKey
                },
                function (response, statusText, jqXHR) {

                    if (jqXHR.status == 200) {

                        $.ajaxSetup({
                            headers: {
                                'X-Auth-Token': response.token
                            }
                        });
                    }
                });

        $(document).on('submit', '#searchForm', function () {

            $.getJSON(
                    apiUrl + '/goods/search',
                    $(this).serialize(),
                    function (response, statusText, jqXHR) {

                        if (jqXHR.status == 200) {

                            console.log(response);
                        }
                    });

            return false;
        })
    });
})();


