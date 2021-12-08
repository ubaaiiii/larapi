<!DOCTYPE html>
<html class="{{ Auth::user()->mode }}">
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
    <link href="{{ url('public/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <title>@yield('title') | BDS General</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ url('public/dist/css/app.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/vendor/daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/vendor/datatables/datatables.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/vendor/fontawesome/all.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('public/vendor/quill/quill.bubble.css') }}" />
    <!-- END: CSS Assets-->
    <script src="{{ url('public/vendor/jquery/jquery-3.6.0.min.js') }}"></script>
    @yield('header')
    <style>
        .has-error .select2-selection {
            /*border: 1px solid #a94442;
            border-radius: 4px;*/
            border-color:rgb(255, 0, 0) !important;
        }
    </style>
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
    <script src="{{ url('public/dist/js/app.js') }}"></script>

    {{-- script vendor --}}
    <script type="text/javascript" src="{{ url('public/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/vendor/select2/id.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/vendor/quill/quill.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/vendor/sweetalert2/sweetalert2.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/js/jquery.inputmask.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/vendor/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/vendor/daterangepicker/daterangepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/vendor/datatables/datatables.min.js') }}"></script>

    {{-- script yang biasa diload saat awal awal --}}
    <script src="{{ url('public/js/function.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".side-menu:contains('@yield('menu')')").addClass("side-menu--active");

            $(".dark-mode-switcher__toggle").click(function() {
                $("html").toggleClass("light dark");
                var v = 1;
                if ($("html").hasClass("light")) {
                    v = 0;
                }
                $.ajax({
                    url: "{{ url('mode') }}" + "/" + v,
                    type: "GET",
                    success: function(d) {
                        // console.log(d);
                    },
                });
            });

            $('#keluar').click(function() {
                $.ajax({
                    url: "{{ url('api/logout') }}",
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Authorization',
                            'Bearer {{ Auth::user()->api_token }}');
                    },
                    success: function(data) {
                        window.location.replace("{{ url('logout') }}");
                    },
                    error: function(data) {
                        console.log('error', data);
                    }
                });
            });
        });
    </script>

    {{-- script bawaan halaman yang diload --}}
    @yield('script')
</body>

</html>
