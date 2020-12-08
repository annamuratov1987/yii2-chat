(function ($) {
    let form = $('#chat-form');
    form.on('beforeSubmit', function(event) {
        let data = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: data,
            success: function (data) {
                if (data.result == 'ok'){
                    location.reload();
                }
                if (data.result == 'error'){
                    alert(data.message);
                    window.location.href = '/login';
                }
            },
            error: function(jqXHR, errMsg) {
                alert(errMsg);
            }
        });
        return false; // prevent default submit
    });

    $('.btn-incorrect').on('click', function () {
        $.ajax({
            url: 'chat/message/'+this.dataset.id+'/incorrect',
            type: 'POST',
            success: function (data) {
                if (data.result == 'ok'){
                    location.reload();
                }
                if (data.result == 'error'){
                    alert(data.message);
                }
            },
            error: function(jqXHR, errMsg) {
                alert(errMsg);
            }
        });
    });

    $('.btn-correct').on('click', function () {
        $.ajax({
            url: 'chat/message/'+this.dataset.id+'/correct',
            type: 'POST',
            success: function (data) {
                if (data.result == 'ok'){
                    location.reload();
                }
                if (data.result == 'error'){
                    alert(data.message);
                }
            },
            error: function(jqXHR, errMsg) {
                alert(errMsg);
            }
        });
    });

    $('.btn-add-admin').on('click', function () {
        $.ajax({
            url: 'user/'+this.dataset.id+'/add-admin',
            type: 'POST',
            success: function (data) {
                if (data.result == 'ok'){
                    location.reload();
                }
                if (data.result == 'error'){
                    alert(data.message);
                }
            },
            error: function(jqXHR, errMsg) {
                alert(errMsg);
            }
        });
    });

    $('.btn-remove-admin').on('click', function () {
        $.ajax({
            url: 'user/'+this.dataset.id+'/remove-admin',
            type: 'POST',
            success: function (data) {
                if (data.result == 'ok'){
                    location.reload();
                }
                if (data.result == 'error'){
                    alert(data.message);
                }
            },
            error: function(jqXHR, errMsg) {
                alert(errMsg);
            }
        });
    });
})(window.jQuery);