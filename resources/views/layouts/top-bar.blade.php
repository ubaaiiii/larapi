<!-- BEGIN: Top Bar -->
<div class="top-bar">
    <!-- BEGIN: Breadcrumb -->
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="{{ url('') }}">Application</a> <i data-feather="chevron-right" class="breadcrumb__icon"></i>
        <a href="" class="breadcrumb--active">@yield('breadcrumb')</a>
    </div>
    <!-- END: Breadcrumb -->
    <div class="intro-x relative mr-3 sm:mr-6 <?= empty($search) ? '' : $search ?>">
        <div class="search">
            <input id="text-search" type="text" class="search__input form-control border-transparent placeholder-theme-13"
                placeholder="Search Inquiry..." name="search">
            <i id="icon-search" data-feather="search" class="search__icon dark:text-gray-300"></i>
        </div>
    </div>
    <script>
        function redirectInquiry($q) {
            window.location.href = "{{ url('inquiry') }}/"+$q;
        }
        $(document).ready(function(){
            $('#text-search').on('keypress',function(e){
                if(e.which == 13) {
                    var q = $(this).val();
                    redirectInquiry(q);
                }
            });
            $('#icon-search').click(function(){
                var q = $('#text-search').val();
                redirectInquiry(q);
            })
        })
    </script>
    <!-- BEGIN: Account Menu -->
    <div class="intro-x dropdown w-8 h-8">
        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button"
            aria-expanded="false">
            <img alt="Rubick Tailwind HTML Admin Template" src="{{ url('public\dist\images\1077114.png') }}">
        </div>
        <div class="dropdown-menu w-56">
            <div class="dropdown-menu__content box bg-theme-26 dark:bg-dark-6 text-white">
                <div class="p-4 border-b border-theme-27 dark:border-dark-3">
                    <div class="font-medium">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-theme-28 mt-0.5 dark:text-gray-600">{{ Auth::user()->level }}</div>
                </div>
                <div class="p-2">
                    <a href="{{ url('profile') }}"
                        class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                        <i data-feather="user" class="w-4 h-4 mr-2"></i> Profil </a>
                    <a href="{{ url('profile/reset') }}"
                        class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                        <i data-feather="lock" class="w-4 h-4 mr-2"></i> Ubah Katasandi </a>
                </div>
                <div class="p-2 border-t border-theme-27 dark:border-dark-3">
                    <a href="#" id="keluar"
                        class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                        <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> Keluar </a>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Account Menu -->
</div>
<!-- END: Top Bar -->
