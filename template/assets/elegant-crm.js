(function($){
    $.ajax({
        url: 'http://worldclockapi.com/api/json/utc/now',
        dataType: 'json',
        crossDomain: true,
        async:true,
        success: function(data){
            $('#current_time').val(data.currentDateTime);
        }
    });

    $('#elegant-crm-form').on('submit', function(e){
        e.preventDefault();

        // Clear previous errors.
        $('.elegant-form-msg').remove();
        $('.has-error').removeClass('has-error');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: $(this).serialize(),
            success: function(data){
                $('.elegant-form-groups').hide();
            },
        }).complete(function(jqXHR){
            var json = jqXHR.responseJSON;

            if(typeof json.data.msg !== 'undefined'){
                var typeClass = json.success ? 'success' : 'errors';
                $('#elegant-crm-form').prepend('<div class="elegant-form-msg '+ typeClass +'">' + json.data.msg + '</div>');
            }

            if(typeof json.data.missing_fields !== 'undefined'){
                $.each(json.data.missing_fields, function(i, f){
                    $('.form-group-' + f).addClass('has-error');
                });
            }
        });
    });
})(jQuery);