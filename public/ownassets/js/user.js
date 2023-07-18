$(document).ready(function() {
    // disable space button
    $('#username').on('keydown', function(event){
        let keyCode = event.keyCode || event.which;

        if(keyCode === 32){
            event.preventDefault();
        }
    });

    // get all user
    $(function() {
        let credential_token = localStorage.getItem('sanctum_token');
        $('#tableList').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/user-all',
                type: 'GET',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + credential_token);
                }
            },
            columns: [
                {data: 'username', name: 'username'},
                {data: 'password', name: 'password'},
            ]
        });
    });

    $('#btnCreate').on('click', function() {
        let credential_token = localStorage.getItem('sanctum_token');
        let _token = $('#_token').val();
        let creator = $('#credential').text();
        let username = $('#username').val();
        let password = $('#password').val();
        let password_confirmation = $('#password_confirmation').val();

        $.ajax({
            url: '/api/user-store',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + credential_token
            },
            data: {
                _token: _token,
                creator: creator,
                username: username,
                password: password,
                password_confirmation: password_confirmation
            },
            dataType: 'JSON',
            cache: false,
            success: function(response){
                // show toasts
                const liveToast = document.getElementById('successToast');
                const toast = new bootstrap.Toast(liveToast);
                toast.show();
                $('.toast-body').text(response.message);

                // close modal after create
                $('#createModal').modal('hide');

                // re-create table
                let table = $("#tableList").DataTable();
                table.destroy();

                $('#tableList').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/api/user-all',
                        type: 'GET',
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization', 'Bearer ' + credential_token);
                        }
                    },
                    columns: [
                        {data: 'username', name: 'username'},
                        {data: 'password', name: 'password'},
                    ]
                });
            },
            error: function(xhr, status, error){
                if(xhr.responseJSON.success == false){
                    // show toasts
                    const liveToast = document.getElementById('errorToast');
                    const toast = new bootstrap.Toast(liveToast);
                    toast.show();
                    $('.toast-body').text(xhr.responseJSON.message);

                    // show error messages
                    let message_username = xhr.responseJSON.errors.username;
                    let message_password = xhr.responseJSON.errors.password;

                    if(message_username !== undefined){
                        $('#error_username').text(message_username);
                    }else{
                        $('#error_username').text('');
                    }
                    // ---------------------------------------------------
                    if(message_password !== undefined){
                        $('#error_password').text(message_password);
                    }else{
                        $('#error_password').text('');
                    }
                    // ---------------------------------------------------

                }else{
                    const liveToast = document.getElementById('errorToast');
                    const toast = new bootstrap.Toast(liveToast);
                    toast.show();
                    $('.toast-body').text('Oops.. something went wrong with system.');
                    console.error(error);
                }
            }
        });
    });
});
