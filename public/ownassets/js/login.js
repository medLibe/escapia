$(document).ready(function() {
    // login
    $('#loginForm').submit(function(event){
        event.preventDefault();

        const username = $('#username').val();
        const password = $('#password').val();
        const _token = $('#_token').val();

        if(username.length < 1){
            $('#error-username').text('Username tidak boleh kosong.');
        }else{
            $('#error-username').text('');
        }

        if(password.length < 1){
            $('#error-password').text('Password tidak boleh kosong.');
        }else{
            $('#error-password').text('');
        }

        if(username === '' || password === ''){
            $('.alert-error').show();
            $('#text-alert-error').text('Username atau password tidak boleh kosong.');
            return false;
        }

        $.ajax({
            url: '/api/auth',
            type: 'POST',
            cache: false,
            dataType: 'JSON',
            data: {
                username: username,
                password: password,
                _token: _token,
            },
            success: function(response){
                $('.alert-error').hide();
                $('.alert-success').show();
                $('#text-alert-success').text(response.message);
                localStorage.setItem('sanctum_token', response.token);

                $('#preloader').show();
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 5000);
            },
            error: function(xhr, error, status){
                if(xhr.status === 401){
                    $('.alert-error').show();
                    $('.alert-error').show();
                    $('#text-alert-error').text(xhr.responseJSON.message);
                }
            }
        });
    });
});
