<!-- BEGIN: Top Bar -->
<div class="top-bar">
    <!-- BEGIN: Breadcrumb -->
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="{{ url('') }}">Application</a> <i data-feather="chevron-right" class="breadcrumb__icon"></i>
        <a href="@yield('link')" class="breadcrumb--active">@yield('breadcrumb')</a>
    </div>
    <!-- END: Breadcrumb -->
    <div class="intro-x relative mr-3 sm:mr-6 <?= empty($search) ? '' : $search ?>">
        <div class="search">
            <input id="text-search" type="search" class="search__input form-control border-transparent placeholder-theme-13"
                placeholder="Cari di Inquiry..." name="search">
            <i id="icon-search" data-feather="search" class="search__icon dark:text-gray-300"></i>
        </div>
    </div>
    <script>
        function redirectInquiry($q) {
            window.location.href = "{{ url('inquiry') }}?q="+$q;
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
    <!-- BEGIN: Notifications -->
    <div class="intro-x dropdown mr-auto sm:mr-6">
        <div class="dropdown-toggle notification cursor-pointer" role="button" aria-expanded="false">
            <i data-feather="bell" class="notification__icon dark:text-gray-300"></i> </div>
        <div class="notification-content pt-2 dropdown-menu">
            <div class="notification-content__box dropdown-menu__content box dark:bg-dark-6">
                <div class="notification-content__title">Pemberitahuan</div>
                <div id="notif-content">

                </div>
                {{-- <div id="all-notif"> --}}
                    <div class="cursor-pointer relative flex items-center mt-5" onClick="window.location.href='{{ url('notifikasi') }}';">
                        <div class="ml-2 overflow-hidden">
                            <div class="flex items-center">
                                <a href="javascript:;" class="font-medium truncate mr-5"><i data-feather="inbox" class="mr-2"></i> Lihat
                                    Semua..</a>
                            </div>
                        </div>
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
    <script>
        function reloadNotif() {
            $.ajax({
                url:"{{ url('api/notifikasi') }}",
                headers: {
                    'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                },
                type: "GET",
                data: {"id":"{{ Auth::user()->id }}","limit":10},
                success: function(d) {
                    // console.log('d',d.length);
                    var konten  = $('#notif-content'),
                        baru = false;
                    
                    if (d.length > 0) {
                        var notif = "",
                            users = {!! json_encode(App\Models\User::select('id','name','username')->get()) !!},
                            i = 0,
                            text = "";

                        for (i=0; i<d.length; i++) {
                            var datte = new Date(d[i].created_at),
                                datee = new Date(),
                                diff = (datee.getTime() + datee.getTimezoneOffset() * 60000 - datte.getTime()) / 1000;
                            console.log(Math.floor(diff / 86400));
                            var data = JSON.parse(d[i].data);
                            if (i < 10) {
                                var id          = d[i].id,
                                    icon        = data.icon,
                                    newBadge    = "",
                                    text        = data.text,
                                    transid     = data.transid;

                                if (d[i].read_at == null) {
                                    if (baru == false) {
                                        baru = true;
                                    }
                                    newBadge = `  <div class="w-6 absolute right-0 bottom-0"><span class="text-xs px-1 rounded-full bg-theme-9 text-white mr-1">baru</span></div>`;
                                    text = `<b>`+text+`</b>`;
                                }

                                notif += `  <div class="cursor-pointer relative flex items-center mb-5 zoom-in" id="`+ id +`">
                                                <div class="ml-2 overflow-hidden">
                                                    <div class="flex items-center">
                                                        `+ newBadge +`
                                                        <a href="javascript:;" class="font-medium truncate mr-5">`+ transid +`</a>
                                                        <div class="text-xs text-gray-500 ml-auto whitespace-nowrap">`+ d[i].created_at +`</div>
                                                    </div>
                                                    <div class="w-full truncate text-gray-600 mt-0.5 tooltip" data-theme="light" title="`+ text +`">`+ text +`</div>
                                                </div>
                                            </div>`;
                            } else {
                                break;
                            }
                        }
                        if (d.length > 10) {
                            $('#all-notif').show();
                        } else {
                            $('#all-notif').hide();
                        }
                    } else {
                        notif = `<div class="relative flex items-center mt-5">
                                    <div class="ml-2 overflow-hidden">
                                        <div class="flex items-center">
                                            <div class="text-xs text-gray-500 ml-auto whitespace-nowrap"><i data-feather="alert-circle"
                                                    class="w-4 h-4 mr-2"></i> Belum ada pemberitahuan</div>
                                        </div>
                                    </div>
                                </div>`;    
                    }
                    if (baru) {
                        $('.notification').addClass('notification--bullet');
                    } else {
                        $('.notification').removeClass('notification--bullet');
                    }
                    konten.empty();
                    konten.append(notif);
                    feather.replace();
                },
                error: function(d) {
                    console.log('d',d);
                },
            });
        }
        $(document).ready(function(){
            reloadNotif();
            $(window).focus(function(){
                reloadNotif();
            });
        });
    </script>
    <!-- END: Notifications -->
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
                    <div class="text-xs text-theme-28 mt-0.5 dark:text-gray-600">{{ Auth::user()->getRoleNames()[0] }}</div>
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