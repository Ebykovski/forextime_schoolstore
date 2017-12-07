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

        // get and fill categories list
        $.getJSON(
                apiUrl + '/categories',
                function (response, statusText, jqXHR) {
                    if (jqXHR.status == 200) {

                        var ul = $("<ul>");

                        response.data.forEach(function (item) {
                            ul.append($("<li>").append($("<a>", {
                                'data-id': item.id,
                                'href': '/goods/category/' + item.id
                            }).text(item.name)));
                        });

                        $('#categories').append(ul);
                    }
                });


        /**
         * get and fill Tags list
         */
        function fillTags() {
            $.getJSON(
                    apiUrl + '/tags',
                    $(this).serialize(),
                    function (response, statusText, jqXHR) {
                        if (jqXHR.status == 200) {

                            var ul = $("<ul>");

                            response.data.forEach(function (item) {
                                ul.append($("<li>").append($("<a>", {
                                    'data-name': item.name,
                                    'href': '/goods/tag/' + encodeURIComponent(item.name)
                                }).text(item.name + ' (' + item.weight + ')')));
                            });

                            $('#tags').empty().append(ul);
                        }
                    });
        }

        fillTags();

        $(document).on('click', '#tags a', function () {

            $('#query').val($(this).data('name'));
            $('#searchForm').submit();
            
            return false;
        });

        $(document).on('click', '#categories a', function () {

            alert('Not implemented');
            
            return false;
        });

        // get and fill goods list
        $.getJSON(
                apiUrl + '/goods',
                function (response, statusText, jqXHR) {
                    if (jqXHR.status == 200) {
                        if (response.total > 0) {
                            fillTable(response.data);
                        } else {
                            $('#listItems').empty().text('0 items found');
                        }
                    }
                });

        // on submit search form
        $(document).on('submit', '#searchForm', function () {

            $('#listItems').empty().text('Wait for search...');

            $.getJSON(
                    apiUrl + '/goods/search',
                    $(this).serialize(),
                    function (response, statusText, jqXHR) {
                        if (jqXHR.status == 200) {
                            if (response.total > 0) {
                                fillTable(response.data);
                            } else {
                                $('#listItems').empty().text('0 items found');
                            }
                        }

                        // update Tags list
                        fillTags();
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

        // create new goods button click
        $('#addGoods').on('click', function () {
            $('#editForm h2').text('Create goods');
            $('#editForm input[name=id]').val('');
            $('#editForm .category').val('');
            $('#optionsList').empty();

            $('#editFormWrap').slideDown();
        });

        // close edit form
        $('#editForm .cancel').on('click', function () {
            $('#editFormWrap').slideUp();
        });

        // by change category request options for category
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

        /**
         * Fill goods table from response array
         * 
         * @param {array} data
         */
        function fillTable(data) {

            $('#listItems').empty();

            var i = 0;
            data.forEach(function (item) {

                $('#listItems').append(buildRow(item, {
                    "class": "item " + (++i % 2 == 0 ? "odd" : "even"),
                    "data-id": item.id
                }));
            });
        }

        /**
         * Build goods row
         * 
         * @param {object} item
         * @param {object} attr
         * @returns {jQuery}
         */
        function buildRow(item, attr) {

            attr = attr || {};

            var row = $("<div>", attr)
                    .append($('<h3>').text('#' + item.id + ' ' + item.category.name));

            for (var j in item.options) {
                row.append(
                        $("<div>")
                        .append($('<b>').text(item.options[j].name + ':'))
                        .append(item.options[j].value + '<br />')
                        );
            }

            return row;
        }
    });
})();


