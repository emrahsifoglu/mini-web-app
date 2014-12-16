$( document ).ready(function() {

    var registerLink = $('#li-register');
    var registerForm = $('#form-register');
    var birthday = $('#_birthday');
    var user = new User();
    var dialog = null;
    var step = 0;

    birthday.datepicker({
        startDate: "01/01/1950",
        endDate: "12/31/" + new Date().getFullYear().toString()});

    registerForm.submit(function(event) {
        event.preventDefault();
        register();
    });

    function register(){
        dialog = null;
        step = 1;

        user.prepare();
        user.setToken(registerForm.find('#_csrf_token_register').val());
        user.setController(registerForm.attr('action'));
        user.setUsername($.trim(registerForm.find('#_username').val()));
        user.setFirstname($.trim(registerForm.find('#_firstname').val()));
        user.setLastname($.trim(registerForm.find('#_lastname').val()));
        user.setEmail(registerForm.find('#_email').val());
        user.setBirthday(birthday.val());

        var _password = $.trim(registerForm.find('#_password').val());
        var _password_confirm = $.trim(registerForm.find('#_password_confirm').val());

        if (_password == _password_confirm) user.setPassword(_password);
        if (!user.isValid()){
            registerFailed('not_valid', user.getErrors());
        } else {
            dialog = BootstrapDialogShow(BootstrapDialog.TYPE_INFO, 'Information', '<b>Please wait...1...saving</b>', [{ id: 'btn-wait' }]);
            dialog.onShow(function(dialog){
                registerForm.find(':input').prop('disabled', true);
                dialog.getButton('btn-wait').disable();
                dialog.getButton('btn-wait').spin();
            });
            dialog.onShown(function(){
                user.save(onSuccess, onError, onComplete);
            });
            dialog.setClosable(false);
            dialog.open();
        }
    }

    function onSuccess(data, textStatus, jqXHR){
        if (jqXHR.status === 201){
            registerSucceed(user.getUsername(), user.getEmail());
        } else {
            registerFailed('unknown', null);
        }
    }

    function onError(jqXHR, textStatus, errorThrown){
        var textError = textStatus;
        if (jqXHR.status === 409 && jqXHR.responseText != ''){
            textError =  $.parseJSON(jqXHR.responseText).error;
        }
        registerFailed(textError, errorThrown);
    }

    function onComplete(){
        console.log('register: complete');
    }

    function registerSucceed(username, email){
        step = 2;
        $(document).trigger('registerSucceed', username );

        if (dialog == null){
            dialog = BootstrapDialogShow(BootstrapDialog.TYPE_INFO, 'Information', '', [{ id: 'btn-wait' }]);
            dialog.onShow(function(dialog){
                registerForm.find(':input').prop('disabled', true);
                dialog.getButton('btn-wait').disable();
                dialog.getButton('btn-wait').spin();
            });
            dialog.setClosable(false);
            dialog.open();
        }

        $.ajax({
            type: 'POST',
            url: $('#route-email').val(),
            data: { username:username, email:email },
            success: function(data, textStatus, jqXHR){
                if (jqXHR.status === 200){
                    dialog.setType(BootstrapDialog.TYPE_SUCCESS);
                    dialog.setTitle('Information');
                    dialog.setMessage('Email has been send.');
                    dialog.setClosable(true);
                    dialog.setButtons([{
                        id:'btn-ok',
                        label:'OK',
                        action: function(dialogItself){
                            dialogItself.close();
                        }}]);
                    dialog.getButton('btn-ok').enable();
                } else {
                    registerFailed('error', 'Email is not send.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                registerFailed('error', 'Email is not send.');
            }
        });
    }

    function registerFailed(textError, data){
        var title = 'Registration Failed';
        var message = '';
        var type = BootstrapDialog.TYPE_WARNING;
        switch (textError){
            case 'username_is_taken':
                title = data;
                message = '<b>User registration is failed because username might be taken.</b>';
                break;
            case 'not_valid':
                title = 'Form Validation';
                type = BootstrapDialog.TYPE_WARNING;
                message = '';
                $.each(data, function(i, error){
                    message += '<p><b>'+error.msg+'</b></p>';
                });
                break;
            case 'bad_request' :
                type = BootstrapDialog.TYPE_DANGER;
                message = '<b>Server did not get the request.</b>';
                break;
            case 'error':
                type = BootstrapDialog.TYPE_DANGER;
                message = '<b>'+data+'</b>';
                break;
            case 'unknown':
                type = BootstrapDialog.TYPE_WARNING;
                message = '<b>Server did not response.</b>';
                break;
        }
        var buttons = [{
            label: 'OK',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];

        if (dialog == null){
            dialog = BootstrapDialogShow(type, title, message, buttons);
        } else {
            dialog.setType(type);
            dialog.setTitle(title);
            dialog.setMessage(message);
            dialog.setButtons(buttons);
        }
        dialog.setClosable(true);
        if (step < 2){
            dialog.onHidden(function(){
                registerForm.find(':input').prop('disabled', false);
            });
        }
        if (!dialog.opened) dialog.open();
    }

    eventPreventer('click', registerLink);
    registerLink.addClass('active');

});