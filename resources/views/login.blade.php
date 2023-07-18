<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Escapia | Login</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/ownassets/css/login.css"/>
</head>
<body>
    <script>
        const sanctumToken = localStorage.getItem('sanctum_token');

        if (sanctumToken) {
        // if sanctum token is available
          window.location.href = '/home';
        }
    </script>
    <div id="preloader" style="display: none;">
        <div id="loader"></div>
    </div>
    <div class="container-fluid mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-4">
                <div class="alert-error" style="display: none;">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <span id="text-alert-error"></span>
                </div>
                <div class="alert-success" style="display: none;">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <span id="text-alert-success"></span>
                </div>
                <div class="card card-login">
                    <div class="text-center fw-bold login-title fs-3 pt-3">LOGIN</div>
                    <div class="card-body">
                        <form id="loginForm">
                            <input type="hidden" class="form-control" id="_token" readonly value="{{ csrf_token() }}">
                            <div class="form-group mb-3 px-4">
                                <label for="username" class="form-label label-username">Username</label>
                                <input type="text" id="username" class="form-control" autofocus>
                                <span class="text-error" id="error-username"></span>
                            </div>
                            <div class="form-group mb-3 px-4">
                                <label for="password" class="form-label label-password">Password</label>
                                <input type="password" id="password" class="form-control">
                                <span class="text-error" id="error-password"></span>
                            </div>
                            <div class="px-4 mb-3">
                                <button class="btn-login">LOGIN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="/ownassets/js/login.js"></script>
</body>
</html>
