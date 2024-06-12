<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('title',  config('app.name') )</title>

    <meta charset="utf-8"/>
    <meta name="description" content="AsignArt Admin"/>
    <meta name="keywords" content="admin"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="Assign Art"/>
    <meta property="og:site_name" content="AsignArt | Admin"/>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}"/>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    @section('style')
    <link href="{{asset('css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/login.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    @show
</head>
<body>
    <div class="from-align">
        <div class="container">
            <div class="d-flex justify-content-center">
                <h5 class="login-title">Authorised access only!</h5>
            </div>
            <div class="d-flex justify-content-center text-center login-textbg">
                <p>This area is for the exclusive use of authorised persons. Access by unauthorised individuals is strictly prohibited and may <br /> be subject to action. If you are an unauthorised user, please exit this page immediately.</p>
            </div>
            <div  class="d-flex justify-content-center ">
                <div class="d-flex flex-center  w-md-480px p-10 logo-form">
                    <div class="w-md-450px ">
                        <form  method="post" action="{{ url('login') }}">
                            @csrf
                            <img class="logo-img" src="{{ asset('images/logo/default.svg') }}" alt="logo">
                            <div class="text-gray-400 fw-bold fs-4">
                                @if (session()->has('message'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('message') }}
                                </div>
                                @endif
                                @error('email')
                                <div data-field="email" data-validator="notEmpty" style="color:red;font-size:15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 mt-4">
                                <label for="exampleInputEmail1" class="form-label form-name">Email</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                name="email" value="{{old('email')}}" autocomplete="off" required>
                                <span class="field-error d-block" id="email-error" style="height:0px;"></span>
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputPassword1" class="form-label form-name">Password</label>
                                <input type="password" class="form-control" id="password-field"  name="password" required>
                                <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                            </div>
                            <h6 class="forder-text">Forgot Password?
{{--                                <span class="contact-line" href="#exampleModalToggle" data-bs-toggle="modal">Contact Admin</span>--}}
                                <a class="contact-line" href="#" target="_top">Contact Admin</a>
                            </h6>
                            <div class='d-flex justify-content-end '>
                                <button type="submit" class="btn apply-btn">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Contact Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="formFieldInput">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label form-name">Email</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label form-name">Message:</label>
                            <textarea class="form-control msg-text" id="message-text"></textarea>
                        </div>
                        <div class='d-flex justify-content-end mt-10'>
                            <button type="button" class="btn apply-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
@section('script')
<script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
$('button[type="submit"]').prop('disabled', true);
$(document).ready(function() {
$(document).on('blur', 'input[name="email"]', function (e) {
    myFunction();
});
$(document).on('keyup', ' input[name="password"]', function (e) {
    myFunction();
});
$(document).on('change', ' input[name="email"],input[name="password"]', function (e) {
    var email = $('input[name="email"]').val();
    var password = $('input[name="password"]').val();
    if (email && password ) {
        myFunction();
    }else{
        $('button[type="submit"]').prop('disabled', true);
    }
});
});
function myFunction() {
    var email = $('input[name="email"]').val();
    var password = $('input[name="password"]').val();
    $('#email-error').html('');
    if (email) {
        $.ajax({
        url: "{{ route('emailcheck') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            email: email
        },
        success: function (response) {
            if(response.exists == 'not'){
                $('button[type="submit"]').prop('disabled', false);
                $('#email-error').html('');
            }
            if (response.exists && password.trim() !== '') {
                $('button[type="submit"]').prop('disabled', false);
                var data = 'mailto:services@asign.art?subject=Request%20Password%20Reset&body=User%20Code:' + response.user.code +
           '%0DUser%20Name:' + response.user.name +
           '%0DRole:' + response.user.role_name +
           '%0DLocation:' + response.user.branch_name +
           '%0DPhone:' + response.user.mobile_number +
           '%0DEmail:' + response.user.email;
            $('.contact-line').attr('href', data);
            } else {
                if (response.exists) {
                    $('button[type="submit"]').prop('disabled', true);
                    $('#email-error').html('');
                } else {
                    $('#email-error').html('Email domain name should be asign.art').css('color', 'red');
                    $('.contact-line').attr('href', '#');
                }
                $('button[type="submit"]').prop('disabled', true);

            }
        }
    });
    }
}
myFunction();
</script>
@show
</body>
</html>
