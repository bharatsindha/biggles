<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>

    <title>Muval</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/request/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Our Min CSS -->
    <link href="{{ asset('assets/css/request/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/request/fontawesome-all.min.css') }}">


    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script> -->
    <!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/additional-methods.js"></script> -->
    <!-- <script src="{{ asset('assets/js/request/script.js') }}"></script> -->
</head>
<body class="register_page">

<!-- Header html code start -->
<header>
    <div class="header_inner d-flex justify-content-between">
        <div class="logo d-flex align-items-center">
            <a href="#"><img src="assets/media/logo.svg"/></a>
        </div>
        <div class="call">
            <a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>Call 1300 168 825</span></a>
        </div>
    </div>
    <div class="header_space">
        <span></span>
    </div>
</header>
<!-- Header html code end -->

<!-- Home page html code start -->
<section class="row page_main_content">

    <!-- Moving form html code start -->
    <article class="register_main_section">

        @if( Session::has('success'))
            <div class="alert alert-success pos_fixed">
                <button data-dismiss="alert" class="close" type="button"><span>×</span><span
                        class="sr-only">Close</span></button>
                <span class="text-semibold"> {{ Session::get('success') }}</span>
            </div>
        @endif

        {{ Form::open(['route' => 'register_company.store', 'id' => 'company-registration']) }}
        @csrf
        <h2>Register your removal company</h2>
        <div class="accordine open">
            <div class="accordine_content">
                <div class="accordine_title d-flex align-items-center justify-content-between">
                    <h3>1. Your business</h3>
                </div>
            </div>
            <div class="accordine_icon">
                <img class="open" src="assets/img/down-arrow.png">
                <img class="close" src="assets/img/light_down.png">
            </div>
            <div class="form_design">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {!!  Form::text('name', old('name'),['id' => 'name','required' => 'required']) !!}
                        @if($errors->has('name'))
                            <div class="text text-danger">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <label>Company name</label>
                    </div>
                    <div class="form-group col-md-6">
                        {!!  Form::text('abn', old('abn'),['id' => 'abn','required' => 'required']) !!}
                        @if($errors->has('abn'))
                            <div class="text text-danger">
                                {{ $errors->first('abn') }}
                            </div>
                        @endif
                        <label>ABN</label>
                    </div>
                </div>
                <div class="form-group">
                    {!!  Form::text('address', old('address'),['id' => 'address','required' => 'required']) !!}
                    @if($errors->has('address'))
                        <div class="text text-danger">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                    <label for="inputAddress">Business address</label>
                </div>
                <div class="form-group">
                    {!!  Form::text('website', old('website'),['id' => 'website','required' => 'required']) !!}
                    @if($errors->has('website'))
                        <div class="text text-danger">
                            {{ $errors->first('website') }}
                        </div>
                    @endif
                    <label>Company Website</label>
                </div>
                <button type="button" class="btn btn-primary move-to-registration">Next</button>
            </div>
        </div>
        <div class="accordine">
            <div class="accordine_content">
                <div class="accordine_title d-flex align-items-center justify-content-between">
                    <h3>2. Registration email</h3>
                </div>
            </div>
            <div class="accordine_icon">
                <img class="open" src="assets/img/down-arrow.png">
                <img class="close" src="assets/img/light_down.png">
            </div>
            <div class="form_design">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {!!  Form::text('user_name', old('user_name'),['id' => 'user_name','required' => 'required']) !!}
                        @if($errors->has('user_name'))
                            <div class="text text-danger">
                                {{ $errors->first('user_name') }}
                            </div>
                        @endif
                        <label>User Name</label>
                    </div>
                    <div class="form-group col-md-6">
                        {!!  Form::text('email', old('email'),['id' => 'email','required' => 'required']) !!}
                        @if($errors->has('email'))
                            <div class="text text-danger">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        <label>User Email</label>
                    </div>
                </div>
                <button type="button" class="btn btn-primary move-to-contact">Next</button>
            </div>
        </div>
        <div class="accordine">
            <div class="accordine_content">
                <div class="accordine_title d-flex align-items-center justify-content-between">
                    <h3>3. Contact information</h3>
                </div>
            </div>
            <div class="accordine_icon">
                <img class="open" src="assets/img/down-arrow.png">
                <img class="close" src="assets/img/light_down.png">
            </div>
            <div class="form_design">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        {!!  Form::text('phone', old('phone'),['id' => 'phone','required' => 'required']) !!}
                        @if($errors->has('phone'))
                            <div class="text text-danger">
                                {{ $errors->first('phone') }}
                            </div>
                        @endif
                        <label>Company Phone</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="register_company d-flex align-items-center justify-content-between">
            <p>By continuing with Muval you agree to our <span>terms and conditions</span> and
                <span>privacy policy</span></p>
            <button type="submit">Register company</button>
        </div>
        {{ Form::close() }}
    </article>
    <!-- Moving form html code end -->

    <!-- Home page Sidebar html code start -->
    <sidebar class="register_page_sidebar">
        <div class="register_sidebar_content">
            <img src="assets/img/register.png">
            <h4>Why partner with Muval?</h4>
            <p><img src="assets/img/right_inclusion.png">Mattress protection</p>
            <p><img src="assets/img/right_inclusion.png">Mattress protection</p>
            <p><img src="assets/img/right_inclusion.png">Mattress protection</p>
        </div>
    </sidebar>
    <!-- Home page Sidebar html code end -->
</section>
<!-- Home page html code start -->
</body>
</html>
