$(document).ready(function() {
    $('#filterBtn').on('click', function() {
        let credential_token = localStorage.getItem('sanctum_token');
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        let item_id = $('#item_id').val();
        let transaction_type = $('#transaction_type').val();

        // re-create table
        let table = $("#tableList").DataTable();
        table.destroy();

        $('#tableList').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/report',
                type: 'GET',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + credential_token);
                },
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    item_id: item_id,
                    transaction_type: transaction_type,
                },
                error: function(xhr, error, thrown){
                    console.error(error);
                    if(xhr.responseJSON.success === false){
                        let message_start_date = xhr.responseJSON.errors.start_date;
                        let message_end_date = xhr.responseJSON.errors.end_date;

                        if(message_start_date !== undefined){
                            $('#error_start_date').text(message_start_date);
                        }else{
                            $('#error_start_date').text('');
                        }
                        // ---------------------------------------------------
                        if(message_end_date !== undefined){
                            $('#error_end_date').text(message_end_date);
                        }else{
                            $('#error_end_date').text('');
                        }
                    }
                    console.error(xhr);
                }
            },
            columnDefs:[
                {width: '15%', target: 0},
                {width: '15%', target: 1},
                {width: '30%', target: 2},
                {width: '5%', target: 3},
                {width: '5%', target: 4},
            ],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'transaction_no', name: 'transaction_no'},
                {data: 'item', name: 'item'},
                {data: 'type', name: 'type'},
                {data: 'qty', name: 'qty'},
                {data: 'description', name: 'description'},
            ],
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
            ]
        });
    });
});
