$( document ).ready(function() {

    var toggleLoginForm = $('#li-login');
    var loginFormContainer = $('#li-form');
    var loginForm = loginFormContainer.find('#form-login');
    var logoutLink = $('#li-logout');

    loginForm.submit(function( event ) {
        event.preventDefault();
        login();
    });

    toggleLoginForm.click(function(){
        loginFormContainer.toggle();
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            loginForm.find('.form-control').val('');
            $(this).addClass('active');
        }
    });

    logoutLink.click(function(event){
        event.preventDefault();
        $(this).addClass('active');
        logout();
    });

    $(this).on('registerSucceed', function( event, param ) {
        loginForm.find('.form-control').val('');
        loginForm.find('#_username').val(param);
        toggleLoginForm.addClass('active');
        loginFormContainer.show();
    });

    $(this).on('unregisterSucceed', function( event) {
        window.location.replace('home');
    });

    function login(){
        $.ajax({
            url: loginForm.attr('action'),
            data: loginForm.serialize(),
            type: loginForm.attr('method'),
            dataType: 'html',
            beforeSend: function(){
                loginForm.find(':input').prop('disabled', true);
            },
            success: function(data, textStatus, jqXHR) {
                if (jqXHR.status === 200){
                    if (data != '')
                        try {
                            var result = $.parseJSON(data);
                            if (isNumber(result.id)) loginSucceed();
                        }
                        catch(e) {
                            loginFailed('User credentials is not found.');
                        }
                } else if (jqXHR.status === 204){
                    loginFailed('User credentials is not found.');
                }
            },
            error:function(jqXHR, textStatus, errorThrown){
                loginFailed(errorThrown);
            }
        });
    }

    function loginSucceed(){
        $('.authorized').show();
        $('.guest').hide();
    }

    function loginFailed(param){
        var type = BootstrapDialog.TYPE_WARNING;
        var title = 'Login Failed';
        var message = '<b>'+param+'</b>';
        var buttons = [{
            label: 'OK',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];
        var dialog = BootstrapDialogShow(type, title, message, buttons);
        dialog.onHide(function(){
            loginForm.find('#_password').val('');
            loginForm.find(':input').prop('disabled', false);
        });
        dialog.open();
    }

    function logoutSucceed(){
        var type = BootstrapDialog.TYPE_INFO;
        var title = 'Logout Succeed';
        var message = '<b>You have been successfully logged.</b>';
        var buttons = [{
            label: 'OK',
            action: function(dialogItself){
                dialogItself.close();
            }
        }];
        var dialog = BootstrapDialogShow(type, title, message, buttons);
        dialog.onHidden(function(){
            window.location.replace('home');
        });
        dialog.open();
    }

    function logout(){
        $.post($('#route-logout').val(), function(data, textStatus, jqXHR){
            if (jqXHR.status === 200){
                logoutLink.removeClass('active');
                logoutSucceed();
            }
        })
    }
});