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
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <link href="public/dist/images/logo.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Rubick admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Rubick Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@1.1/dist/css/tom-select.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>@yield('title') | BDS General</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="public/dist/css/app.css" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body class="main">
    @include('layouts.mobile-menu')
    <div class="flex">
        @include('layouts.side-menu')
        <div class="content">
            @include('layouts.top-bar')
            @yield('content')
        </div>
    </div>
    @include('layouts.footer')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="public/dist/js/app.js"></script>
    <script src="public/js/function.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@1.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dark-mode-switcher').removeAttr('style');
            $('.dark-mode-switcher__toggle').click(function() {
                $("html").toggleClass('light dark');
            });

            $(".side-menu:contains('@yield('menu')')").addClass('side-menu--active');

            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() + 5 >= $(document).height()) {
                    $('.dark-mode-switcher').css('display', 'none');
                } else {
                    $('.dark-mode-switcher').removeAttr('style');
                }
            });

            $(document).on('keydown', '.allow-decimal', function(event) {


                if (event.shiftKey == true) {
                    event.preventDefault();
                }

                if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <=
                        105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event
                    .keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

                } else {
                    event.preventDefault();
                }

                if ($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                    event.preventDefault();

            });

        });
    </script>
    @yield('script');
</body>

</html>
