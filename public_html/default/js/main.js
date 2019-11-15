$(document).ready(function () {

    $('body').on('click', '[data-deleteparent]', function () {
        var par = $(this).data('deleteparent');
        $(this).parents(par).remove();
    });

    $('body').on('click', '#add-field', function () {
        getShab('doc_field_table', false, function (res) {
            $('#new-field').append(res);
        });
    });


    $('body').on('click', '#add-field-doc', function () {
        var repl = {
            value: $('#select-field option:selected').data('key'),
            name: $('#select-field option:selected').data('name'),
        };

        getShab('structure', repl, function (res) {
            $('#new-field-doc').append(res);
        });
    });

});


function getShab(name, replace, callback) {
    var timeObj = new Date();
    $.get('/html_shabs/' + name + '.txt?d='+timeObj.getTime(), function (res) {
        if (replace)
            $.each(replace, function (i, el) {
                res = res.replace('{' + i + '}', el);
            });

        if (callback)
            callback(res);

    });
}