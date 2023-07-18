// Dont forget to always put Bearer Token (because the api is guarded by sanctum)
$(document).ready(function() {
    // numeric input field
    $('.numeric').keyup(function() {
        let inputValue = $(this).val();
        let numericRegex = /^[0-9]*$/;

        if(!numericRegex.test(inputValue)){
            $(this).val(inputValue.replace(/[^0-9]/g, ''));
        }
    });

    // get all item
    $(function() {
        let credential_token = localStorage.getItem('sanctum_token');
        $('#tableList').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/item-all',
                type: 'GET',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + credential_token);
                }
            },
            columns: [
                {data: 'item_code', name: 'item_code'},
                {data: 'item_name', name: 'item_name'},
                {data: 'package', name: 'package'},
                {data: 'stock', name: 'stock'},
                {data: 'action', name: 'action'},
            ]
        });
    });

    // import action
    $('#btnImport').on('click', function() {
        let credential_token = localStorage.getItem('sanctum_token');
        let _token = $('#_token').val();
        let creator = $('#credential').text();
        let fileInput = document.getElementById('importFile');
        let file = fileInput.files[0];

        let formData = new FormData();
        formData.append('file_import', file);
        formData.append('_token', _token);
        formData.append('creator', creator);

        $.ajax({
            url: '/api/item-import',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + credential_token
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                // show toasts
                const liveToast = document.getElementById('successToast');
                const toast = new bootstrap.Toast(liveToast);
                toast.show();
                $('.toast-body').text(response.message);

                // close modal after import
                $('#importModal').modal('hide');

                // re-create table
                let table = $("#tableList").DataTable();
                table.destroy();

                $('#tableList').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/api/item-all',
                        type: 'GET',
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization', 'Bearer ' + credential_token);
                        }
                    },
                    columns: [
                        {data: 'item_code', name: 'item_code'},
                        {data: 'item_name', name: 'item_name'},
                        {data: 'package', name: 'package'},
                        {data: 'stock', name: 'stock'},
                        {data: 'action', name: 'action'},
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
                }
                const liveToast = document.getElementById('errorToast');
                const toast = new bootstrap.Toast(liveToast);
                toast.show();
                $('.toast-body').text('Oops.. something went wrong with system.');
                console.error(error);
            }
        });


    });

    // update action
    $('.btnUpdate').on('click', function() {
        let credential_token = localStorage.getItem('sanctum_token');
        let _token = $('#_token').val();
        let creator = $('#credential').text();
        let htmlBtn = $(this).attr('id');
        let item_id = htmlBtn.slice(3);
        let item_name = $('#item_name' + item_id).val();
        let packaging = $('#packaging' + item_id).val();
        let qty_per_packaging = $('#qty_per_packaging' + item_id).val();

        $.ajax({
            url: '/api/item-update/' + item_id,
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + credential_token
            },
            data: {
                _token: _token,
                creator: creator,
                item_name: item_name,
                packaging: packaging,
                qty_per_packaging: qty_per_packaging,
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
                $('#editModal' + item_id).modal('hide');

                // re-create table
                let table = $("#tableList").DataTable();
                table.destroy();

                $('#tableList').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/api/item-all',
                        type: 'GET',
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization', 'Bearer ' + credential_token);
                        }
                    },
                    columns: [
                        {data: 'item_code', name: 'item_code'},
                        {data: 'item_name', name: 'item_name'},
                        {data: 'package', name: 'package'},
                        {data: 'stock', name: 'stock'},
                        {data: 'action', name: 'action'},
                    ]
                });
            },
            error: function(xhr, status, error){
                const liveToast = document.getElementById('errorToast');
                const toast = new bootstrap.Toast(liveToast);
                toast.show();
                $('.toast-body').text('Oops.. something went wrong with system.');
                console.error(error);
            }
        });
    });

    // delete action
    $('.btnDelete').on('click', function() {
        let credential_token = localStorage.getItem('sanctum_token');
        let _token = $('#_token').val();
        let creator = $('#credential').text();
        let htmlBtn = $(this).attr('id');
        let item_id = htmlBtn.slice(3);

        $.ajax({
            url: '/api/item-delete',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + credential_token
            },
            data: {
                _token: _token,
                creator: creator,
                item_id: item_id,
            },
            dataType: 'JSON',
            cache: false,
            success: function(response){
                // show toasts
                const liveToast = document.getElementById('successToast');
                const toast = new bootstrap.Toast(liveToast);
                toast.show();
                $('.toast-body').text(response.message);

                // close modal after delete
                $('#deleteModal' + item_id).modal('hide');

                // re-create table
                let table = $("#tableList").DataTable();
                table.destroy();

                $('#tableList').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/api/item-all',
                        type: 'GET',
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization', 'Bearer ' + credential_token);
                        }
                    },
                    columns: [
                        {data: 'item_code', name: 'item_code'},
                        {data: 'item_name', name: 'item_name'},
                        {data: 'package', name: 'package'},
                        {data: 'stock', name: 'stock'},
                        {data: 'action', name: 'action'},
                    ]
                });
            },
            error: function(xhr, status, error){
                const liveToast = document.getElementById('errorToast');
                const toast = new bootstrap.Toast(liveToast);
                toast.show();
                $('.toast-body').text('Oops.. something went wrong with system.');
                console.error(error);
            }
        });
    });
});
