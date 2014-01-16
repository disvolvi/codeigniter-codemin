function uploader(id,url){
    'use strict';

    $(id).fileupload({
        url: url
    });

    // Load existing files:
    $(id).addClass('fileupload-processing');
    $.ajax({
        url: $(id).fileupload('option', 'url'),
        dataType: 'json',
        context: $(id)[0]
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) {
        $(this).fileupload('option', 'done')
            .call(this, null, {result: result});
    });

}