$(document).ready(function () {
    // numeric input field
    $('.numeric').keyup(function () {
        let inputValue = $(this).val();
        let numericRegex = /^[0-9]*$/;

        if (!numericRegex.test(inputValue)) {
            $(this).val(inputValue.replace(/[^0-9]/g, ''));
        }
    });

    // change qty stock value every change in item select
    $('#item_id').on('change', function() {
        let credential_token = localStorage.getItem('sanctum_token');
        let item_id = $('#item_id').val();

        $.ajax({
            url: '/api/item-show/' + item_id,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + credential_token
            },
            dataType: 'JSON',
            cache: false,
            success: function(response){
                $('#qty_stock').val(response.qty_stock);
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

    $('#btnAdjust').on('click', function() {
        let credential_token = localStorage.getItem('sanctum_token');
        let _token = $('#_token').val();
        let creator = $('#credential').text();
        let transaction_date = $('#transaction_date').val();
        let transaction_no = $('#transaction_no').val();
        let transaction_type = $('#transaction_type').val();
        let item_id = $('#item_id').val();
        let qty_stock = $('#qty_stock').val();
        let qty_adjust = $('#qty_adjust').val();
        let description = $('#description').val();

        $.ajax({
            url: '/api/adjustment',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + credential_token
            },
            data: {
                _token: _token,
                creator: creator,
                transaction_date: transaction_date,
                transaction_no: transaction_no,
                transaction_type: transaction_type,
                item_id: item_id,
                qty_stock: qty_stock,
                qty_adjust: qty_adjust,
                description: description
            },
            dataType: 'JSON',
            cache: false,
            success: function(response){
                // show toasts
                const liveToast = document.getElementById('successToast');
                const toast = new bootstrap.Toast(liveToast);
                toast.show();
                $('.toast-body').text(response.message);

                // convert Ymd to dmY
                let date_parts = transaction_date.split('-');
                let formatted_date = date_parts[2] + '-' + date_parts[1] + '-' + date_parts[0];

                // if 1 in , 2 close
                let type;
                if(transaction_type == 1){
                    type = 'In';
                }else{
                    type = 'Out';
                }

                // add row every adjust
                let addRow = $('#tableAdjust tbody');
                let trOpen = '<tr>';
                let tr_date = '<td>' + formatted_date + '</td>';
                let tr_transid = '<td>' + transaction_no + '</td>';
                let tr_type = '<td>' + type + '</td>';
                let tr_item = '<td>' + response.item + '</td>';
                let tr_qty = '<td>' + qty_adjust + '</td>';
                let tr_desc = '<td>' + description + '</td>';
                let trClose = '</tr>';

                addRow.append(trOpen + tr_date + tr_transid + tr_type + tr_item + tr_qty + tr_desc + trClose);
            },
            error: function(xhr, status, error){
                if(xhr.responseJSON.success == false){
                    if(xhr.status === 400){
                        // show error messages
                        let message_transaction_date = xhr.responseJSON.errors.transaction_date;
                        let message_transaction_no = xhr.responseJSON.errors.transaction_no;
                        let message_transaction_type = xhr.responseJSON.errors.transaction_type;
                        let message_item_id = xhr.responseJSON.errors.item_id;
                        let message_qty_stock = xhr.responseJSON.errors.qty_stock;
                        let message_qty_adjust = xhr.responseJSON.errors.qty_adjust;

                        if(message_transaction_date !== undefined){
                            $('#error_transaction_date').text(message_transaction_date);
                        }else{
                            $('#error_transaction_date').text('');
                        }
                        // ---------------------------------------------------
                        if(message_transaction_no !== undefined){
                            $('#error_transaction_no').text(message_transaction_no);
                        }else{
                            $('#error_transaction_no').text('');
                        }
                        // ---------------------------------------------------
                        if(message_transaction_type !== undefined){
                            $('#error_transaction_type').text(message_transaction_type);
                        }else{
                            $('#error_transaction_type').text('');
                        }
                        // ---------------------------------------------------
                        if(message_item_id !== undefined){
                            $('#error_item_id').text(message_item_id);
                        }else{
                            $('#error_item_id').text('');
                        }
                        // ---------------------------------------------------
                        if(message_qty_stock !== undefined){
                            $('#error_qty_stock').text(message_qty_stock);
                        }else{
                            $('#error_qty_stock').text('');
                        }
                        // ---------------------------------------------------
                        if(message_qty_adjust !== undefined){
                            $('#error_qty_adjust').text(message_qty_adjust);
                        }else{
                            $('#error_qty_adjust').text('');
                        }
                        // ---------------------------------------------------

                        // show toasts
                        const liveToast = document.getElementById('errorToast');
                        const toast = new bootstrap.Toast(liveToast);
                        toast.show();
                        $('.toast-body').text(xhr.responseJSON.message);
                    }else{
                        // show toasts
                        const liveToast = document.getElementById('errorToast');
                        const toast = new bootstrap.Toast(liveToast);
                        toast.show();
                        $('.toast-body').text(xhr.responseJSON.message);
                    }
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
