$( document ).ready(function() {
    var detailLink = $('#li-detail');
    var unsubscribeBtn = $('#unsubscribe');
    var detailForm = $('#form-detail');
    var dialog = null;

    detailForm.submit(function( event ) {
        event.preventDefault();
    });

    unsubscribeBtn.click(function(){
        var buttons = [{
            id: 'btn-yes',
            label: 'Yes',
            cssClass: 'btn-danger',
            action: function(dialog){
                var $button = this;
                dialog.setClosable(false);
                dialog.getButton('btn-cancel').disable();
                $button.disable();
                $button.spin();
                unregister();
            }
        }, {
            id: 'btn-cancel',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
            }
        },
            {
            id: 'btn-ok',
            label: 'OK',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];
        dialog = BootstrapDialogShow(BootstrapDialog.TYPE_DANGER, 'Warning', '<b>Do you want to confirm this process?</b>', buttons);
        dialog.open().getButton('btn-ok').hide();
    });

    function unregister(){
        var user = new User();
        user.setId(detailForm.find('#_id').val());
        user.setController(detailForm.attr('action'));
        user.setToken(detailForm.find('#_csrf_token_detail').val());
        user.destroy(onSuccess, onError,  onComplete);
    }

    function unregisterFailed(textError, data){
        var title = 'Unregistration Failed';
        var type = BootstrapDialog.TYPE_WARNING;
        var message = '';
        switch (textError){
            case 'invalid_user' :
                message = '<b>User might have been already logged out.</b>';
                break;
            case 'bad_request' :
                type = BootstrapDialog.TYPE_DANGER;
                message = '<b>Server did not get the request.</b>';
                break;
            case 'error' :
                type = BootstrapDialog.TYPE_DANGER;
                message = '<b>'+data+'</b>';
                break;
            case 'unknown':
                message = '<b>Server did not response.</b>';
                break;
        }
        dialog.setTitle(title);
        dialog.setType(type);
        dialog.setMessage(message);
        dialog.getButton('btn-yes').stopSpin();
        dialog.getButton('btn-yes').enable();
        dialog.getButton('btn-yes').hide();
        dialog.getButton('btn-cancel').hide();
        dialog.getButton('btn-ok').show();
    }

    function unregisterSuccess(){
        dialog.setTitle('Unregistration Success');
        dialog.setType(BootstrapDialog.TYPE_SUCCESS);
        dialog.setMessage('<b>adiós, hasta luego!...</b>');
        dialog.getButton('btn-yes').stopSpin();
        dialog.getButton('btn-yes').enable();
        dialog.getButton('btn-yes').hide();
        dialog.getButton('btn-cancel').hide();
        dialog.getButton('btn-ok').show();
        dialog.onHide(function(){
            $(document).trigger('unregisterSucceed');
        });
    }

    function onSuccess(data, textStatus, jqXHR){
        if (jqXHR.status === 204){
            unregisterSuccess();
        } else {
            var textError = 'unknown';
            if (data != ''){
                var result = $.parseJSON(data);
                textError = (result.error !== undefined) ? result.error : textError;
            }
            unregisterFailed(textError);
        }
    }

    function onError(jqXHR, textStatus, errorThrown){
        var textError = textStatus;
        if (jqXHR.status === 400 && jqXHR.responseText != ''){
            textError =  $.parseJSON(jqXHR.responseText).error;
        }
        unregisterFailed(textError, errorThrown);
    }

    function onComplete(){
        dialog.setClosable(true);
    }

    eventPreventer('click', detailLink);
    detailLink.addClass('active');
});