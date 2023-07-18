$(document).ready(function() {
    // count user
    $(function() {
        let token = localStorage.getItem('sanctum_token');
        $.ajax({
            url: '/api/user-count',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            cache: false,
            dataType: 'JSON',
            success: function(response){
                $('#user').text(response.user);
            },
            error: function(xhr, error, status){
                console.error(error);
            }
        });
    });

    // count item
    $(function() {
        let token = localStorage.getItem('sanctum_token');
        $.ajax({
            url: '/api/item-count',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            cache: false,
            dataType: 'JSON',
            success: function(response){
                $('#item').text(response.item);
            },
            error: function(xhr, error, status){
                console.error(error);
            }
        });
    });

    $(function() {
        let token = localStorage.getItem('sanctum_token');
        $.ajax({
            url: '/api/item-inflow-count',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            cache: false,
            dataType: 'JSON',
            success: function(response){
                $('#inflow').text(response.inflow);
            },
            error: function(xhr, error, status){
                console.error(error);
            }
        });
    });

    $(function() {
        let token = localStorage.getItem('sanctum_token');
        $.ajax({
            url: '/api/item-outflow-count',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            cache: false,
            dataType: 'JSON',
            success: function(response){
                $('#outflow').text(response.outflow);
            },
            error: function(xhr, error, status){
                console.error(error);
            }
        });
    });
});
