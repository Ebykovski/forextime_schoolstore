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
                }
        );

        $(document).on('submit', '#searchForm', function () {

            $('#listItems').empty().text('Wait for search...');
            
            $.getJSON(
                    apiUrl + '/goods/search',
                    $(this).serialize(),
                    function (response, statusText, jqXHR) {
                        $('#listItems').empty();

                        if (jqXHR.status == 200) {

                            var data = response.data;

                            $('#listItems').text(data.length + ' items found');

                            var i = 0;
                            data.forEach(function (item) {
                                console.log(item);
                                var row = $("<div>", {
                                    "class": "item " + (++i % 2 == 0 ? "odd" : "even")
                                })
                                        .append($('<h3>').text('#' + item.id + ' ' + item.category.name));

                                for (var j in item.options) {
                                    row.append(
                                            $("<div>")
                                            .append($('<b>').text(item.options[j].name + ':'))
                                            .append(item.options[j].value + '<br />')
                                            );
                                }

                                $('#listItems').append(row);
                            });
                        }
                    });

            return false;
        });
        
        
    });
})();


