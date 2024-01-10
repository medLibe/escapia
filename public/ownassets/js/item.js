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
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa-solid fa-copy"></i> Copy',
                    className: 'btn btn-secondary me-1'
                },
                {
                    extend: 'excel',
                    title: 'Report Stock Movement',
                    text: '<i class="fa-solid fa-file-excel"></i> Excel',
                    className: 'btn btn-success me-1',
                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-file-export"></i> Print',
                    className: 'btn btn-danger me-1',
                    title: 'Report Stock Movement',
                    customize: function(win) {
                        $(win.document.body).find('h1').text('Report Stock Movement');
                        $(win.document.body).css('font-size', '14px');
                    },
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa-solid fa-print"></i> PDF',
                    className: 'btn btn-light',
                    title: 'Report Stock Movement',
                },
            ],
            lengthMenu: [10, 25, 50, 100],
            pageLength: 25,
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
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="fa-solid fa-copy"></i> Copy',
                            className: 'btn btn-secondary me-1'
                        },
                        {
                            extend: 'excel',
                            title: 'Report Stock Movement',
                            text: '<i class="fa-solid fa-file-excel"></i> Excel',
                            className: 'btn btn-success me-1',
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa-solid fa-file-export"></i> Print',
                            className: 'btn btn-danger me-1',
                            title: 'Report Stock Movement',
                            customize: function(win) {
                                $(win.document.body).find('h1').text('Report Stock Movement');
                                $(win.document.body).css('font-size', '14px');
                            },
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa-solid fa-print"></i> PDF',
                            className: 'btn btn-light',
                            title: 'Report Stock Movement',
                        },
                    ],
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 25,
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

    $('#btnCreate').on('click', function() {
        let credential_token = localStorage.getItem('sanctum_token');
        let _token = $('#_token').val();
        let creator = $('#credential').text();
        let item_code = $('#item_code').val();
        let item_name = $('#item_name').val();
        let packaging = $('#packaging').val();
        let qty_per_packaging = $('#qty_per_packaging').val();

        $.ajax({
            url: '/api/item-store',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + credential_token
            },
            data: {
                _token: _token,
                creator: creator,
                item_code: item_code,
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
                $('#createModal').modal('hide');

                // re-create table
                let table = $("#tableList").DataTable();
                table.destroy();

                $('#tableList').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/api/item-all',
                        type: 'GET',
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('Authorization', 'Bearer ' + credential_token);
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="fa-solid fa-copy"></i> Copy',
                            className: 'btn btn-secondary me-1'
                        },
                        {
                            extend: 'excel',
                            title: 'Report Stock Movement',
                            text: '<i class="fa-solid fa-file-excel"></i> Excel',
                            className: 'btn btn-success me-1',
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa-solid fa-file-export"></i> Print',
                            className: 'btn btn-danger me-1',
                            title: 'Report Stock Movement',
                            customize: function(win) {
                                $(win.document.body).find('h1').text('Report Stock Movement');
                                $(win.document.body).css('font-size', '14px');
                            },
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa-solid fa-print"></i> PDF',
                            className: 'btn btn-light',
                            title: 'Report Stock Movement',
                        },
                    ],
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 25,
                    columns: [
                        { data: 'item_code', name: 'item_code' },
                        { data: 'item_name', name: 'item_name' },
                        { data: 'package', name: 'package' },
                        { data: 'stock', name: 'stock' },
                        { data: 'action', name: 'action' },
                    ]
                });

                // generate next item code
                $('#item_code').val(response.item_code);
            },
            error: function(xhr, status, error){
                const status_code = xhr.status;

                if(status_code === 400){
                    let errors = xhr.responseJSON.errors;

                    if(errors.item_code !== undefined){
                        $('#error_item_code').text(errors.item_code);
                    }else{
                        $('#error_item_code').text('');
                    }

                    if(errors.item_name !== undefined){
                        $('#error_item_name').text(errors.item_name);
                    }else{
                        $('#error_item_name').text('');
                    }

                    if(errors.packaging !== undefined){
                        $('#error_packaging').text(errors.packaging);
                    }else{
                        $('#error_packaging').text('');
                    }

                    if(errors.qty_per_packaging !== undefined){
                        $('#error_qty_per_packaging').text(errors.qty_per_packaging);
                    }else{
                        $('#error_qty_per_packaging').text('');
                    }
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
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="fa-solid fa-copy"></i> Copy',
                            className: 'btn btn-secondary me-1'
                        },
                        {
                            extend: 'excel',
                            title: 'Report Stock Movement',
                            text: '<i class="fa-solid fa-file-excel"></i> Excel',
                            className: 'btn btn-success me-1',
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa-solid fa-file-export"></i> Print',
                            className: 'btn btn-danger me-1',
                            title: 'Report Stock Movement',
                            customize: function(win) {
                                $(win.document.body).find('h1').text('Report Stock Movement');
                                $(win.document.body).css('font-size', '14px');
                            },
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa-solid fa-print"></i> PDF',
                            className: 'btn btn-light',
                            title: 'Report Stock Movement',
                        },
                    ],
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 25,
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
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="fa-solid fa-copy"></i> Copy',
                            className: 'btn btn-secondary me-1'
                        },
                        {
                            extend: 'excel',
                            title: 'Report Stock Movement',
                            text: '<i class="fa-solid fa-file-excel"></i> Excel',
                            className: 'btn btn-success me-1',
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa-solid fa-file-export"></i> Print',
                            className: 'btn btn-danger me-1',
                            title: 'Report Stock Movement',
                            customize: function(win) {
                                $(win.document.body).find('h1').text('Report Stock Movement');
                                $(win.document.body).css('font-size', '14px');
                            },
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa-solid fa-print"></i> PDF',
                            className: 'btn btn-light',
                            title: 'Report Stock Movement',
                        },
                    ],
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 25,
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
