<!DOCTYPE html>
<!--
Template Name: Rubick - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <link href="{{ url('public/dist/images/logo.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Rubick admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Rubick Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>Login | BDS General</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" type="text/css" href="{{ url('public/vendor/fontawesome/all.css') }}" />
    <link rel="stylesheet" href="{{ url('public/dist/css/app.css') }}" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body class="login">
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="" class="-intro-x flex items-center pt-5">
                    <img alt="Rubick Tailwind HTML Admin Template" class="w-6"
                        src="{{ url('public/dist/images/logo.svg') }}">
                    <span class="text-white text-lg ml-3"> <span class="font-medium">BDS</span> General</span>
                </a>
                <div class="my-auto">
                    <img alt="Rubick Tailwind HTML Admin Template" class="-intro-x w-1/2 -mt-16"
                        src="{{ url('public/dist/images/illustration.svg') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                        Bina Dana Sejahtera
                    </div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-gray-500">Sistem Informasi
                        Asuransi Perbankan
                    </div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div
                    class="my-auto mx-auto xl:ml-20 bg-white dark:bg-dark-1 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <form id="form-login" method="POST" action="{{ route('login') }}">
                        @csrf
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Login
                        </h2>
                        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">Bina Dana Sejahtera. Sistem
                            Informasi Asuransi Perbankan</div>
                        <div class="intro-x mt-8">
                            <input type="text" class="intro-x login__input form-control py-3 px-4 border-gray-300 block" required
                                name="username" placeholder="Username">
                            <input type="password" required
                                class="intro-x login__input form-control py-3 px-4 border-gray-300 block mt-4"
                                name="password" placeholder="Password">
                            @if ($errors->any())
                                <h4>{{ $errors->first() }}</h4>
                            @endif
                        </div>
                        <div class="intro-x flex text-gray-700 dark:text-gray-600 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input name="remember_me" id="remember-me" type="checkbox"
                                    class="form-check-input border mr-2">
                                <label class="cursor-pointer select-none" for="remember-me">Remember me</label>
                            </div>
                            {{-- <a href="">Forgot Password?</a> --}}
                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top"><i class="fa fa-unlock-alt mr-2 mb-2"></i>Masuk</button>
                            <a href="https://api.whatsapp.com/send/?phone=6287877200523&text=Hai%2C%20saya%20ingin%20mengajukan%20permohonan%20user%20DigiSIAP%3A%0ANama%20%3A%20%0AUsername%20%3A%20%0AEmail%20%3A%20%0ANo.%20Telp%20%3A%20%0ANama%20Mitra%20(%20Asuransi%20%2F%20Bank%20)%20%3A%20%0ACabang%20Mitra%20%3A%20%0ANama%20Broker%20yg%20Dikenal%20%3A%20%0A%0ATerima%20Kasih"
                                target="_blank" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top"><i class="fa fa-id-card mr-2"></i>Daftar</a>
                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>
    <!-- BEGIN: Dark Mode Switcher-->
    <div data-url="#"
        class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box dark:bg-dark-2 border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10"
        style="display:none;">
        <div class="mr-4 text-gray-700 dark:text-gray-300">Dark Mode</div>
        <div class="dark-mode-switcher__toggle border"></div>
    </div>
    <!-- END: Dark Mode Switcher-->
    <!-- BEGIN: JS Assets-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{{ url('public/dist/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dark-mode-switcher').removeAttr('style');
            $('.dark-mode-switcher__toggle').click(function() {
                $("html").toggleClass('light dark');
            });

            var frm = $('#form-login'),
                btn = frm.find(':submit');

            // frm.submit(function(e) {
            //     e.preventDefault();
            //     btn.attr('disabled', true);
            //     $.ajax({
            //         url: "{{ url('api/login') }}",
            //         method: "POST",
            //         data: frm.serialize(),
            //         success: function(d) {
            //             console.log(d);
            //         },
            //         error: function(d) {
            //             console.log(d);
            //         }
            //     })
            // });
        });
    </script>
    <!-- END: JS Assets-->
</body>

</html>