(function () {
    var apiUrl = '/api/v1';
    var apiKey = '1111-2222-3333-4444';

    $(document).ready(function () {

        // autenticate app (get auth token)
        $.getJSON(
                apiUrl + '/',
                {
                    apiKey: apiKey
                },
                function (response, statusText, jqXHR) {

                    if (jqXHR.status == 200) {

                        // everytime, by send API request, send auth token
                        $.ajaxSetup({
                            headers: {
                                'X-Auth-Token': response.token
                            }
                        });
                    }
                }
        );

        // on submit search form
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
                                    "class": "item " + (++i % 2 == 0 ? "odd" : "even"),
                                    "data-id": item.id
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

        // by click on item row show edit form
        $(document).on('click', '.item', function () {
            var item_id = $(this).data('id');

            $('#editFormWrap').slideDown();

            $.getJSON(
                    apiUrl + '/goods/' + item_id,
                    function (response, statusText, jqXHR) {

                        if (jqXHR.status == 200) {

                            var data = response.data;

                            $('#editForm h2').text('Edit goods #' + data.id);
                            $('#editForm input[name=id]').val(data.id);
                            $('#editForm .category').val(data.category.id);

                            $('#optionsList').empty();

                            for (var i in data.options) {
                                var item = data.options[i];

                                $('#optionsList').append($('<div>').append($('<label>').text(item.name))
                                        .append($('<input>', {
                                            'type': 'text',
                                            'name': 'option[' + item.id + ']',
                                            'required': 'required',
                                            'id': 'option-' + item.id
                                        }).val(item.value)));
                            }

                            console.log(data);
                        }
                    });
        });

        // close edit form
        $('#editForm .cancel').on('click', function () {
            $('#editFormWrap').slideUp();
        });

        // by change category of goods request options from server 
        $('#editForm .category').on('change', function () {
            var category_id = $(this).val();

            $('#optionsList').empty();

            $.getJSON(
                    apiUrl + '/categories/' + category_id + '/options',
                    function (response, statusText, jqXHR) {

                        if (jqXHR.status == 200) {

                            var data = response.data;

                            for (var i in data) {
                                var item = data[i];

                                $('#optionsList').append($('<div>').append($('<label>').text(item.name))
                                        .append($('<input>', {
                                            'type': 'text',
                                            'name': 'option[' + item.id + ']',
                                            'required': 'required',
                                            'id': 'option-' + item.id
                                        }).val(item.value)));
                            }

                        }
                    }
            );
        });

        // on submit goods changes
        $('#editForm').on('submit', function () {
            var data = $(this).serialize();

            var goods_id = $('#editForm input[name=id]').val();

            var url = goods_id > 0 ? '/goods/' + goods_id : '/goods'

            $.ajax({
                method: "POST",
                url: apiUrl + '/goods' + (goods_id > 0 ? '/' + goods_id : ''),
                data: data,
                dataType: "json"
            }).done(function (data, textStatus, jqXHR) {
                console.log('done');
                console.log(data);
                console.log(textStatus);
                console.log(jqXHR.status);
            }).fail(function (data, textStatus, jqXHR) {
                console.log('fail');
                console.log(data);
                console.log(textStatus);
                console.log(jqXHR.status);

            });

            return false;
        });
    });
})();


