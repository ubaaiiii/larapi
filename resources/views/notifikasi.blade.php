@extends('layouts.master')
@section('title', 'Pemberitahuan')
@section('breadcrumb', 'Pemberitahuan')
@section('menu', 'Pemberitahuan')
@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
    Pemberitahuan
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <select class="w-20 form-select box mt-3 sm:mt-0" id="limit-notif">
            <option>10</option>
            <option>25</option>
            <option>35</option>
            <option>50</option>
        </select>
        <div class="hidden md:block mx-auto text-gray-600">Showing 1 to 10 of 150 entries</div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                <input id="search-notif" type="text" class="form-control w-56 box pr-10 placeholder-theme-13" placeholder="Cari Pemberitahuan...">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search" onclick="reloadTable()"></i>
            </div>
        </div>
    </div>
    <div class="intro-y col-span-12" id="table-notif">
        
    </div>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <ul class="pagination">
            <li>
                <a class="pagination__link" href=""> <i class="w-4 h-4" data-feather="chevrons-left"></i> </a>
            </li>
            <li>
                <a class="pagination__link" href=""> <i class="w-4 h-4" data-feather="chevron-left"></i> </a>
            </li>
            <li> <a class="pagination__link" href="">...</a> </li>
            <li> <a class="pagination__link" href="">1</a> </li>
            <li> <a class="pagination__link pagination__link--active" href="">2</a> </li>
            <li> <a class="pagination__link" href="">3</a> </li>
            <li> <a class="pagination__link" href="">...</a> </li>
            <li>
                <a class="pagination__link" href=""> <i class="w-4 h-4" data-feather="chevron-right"></i> </a>
            </li>
            <li>
                <a class="pagination__link" href=""> <i class="w-4 h-4" data-feather="chevrons-right"></i> </a>
            </li>
        </ul>
    </div>
</div>
@endsection

@section('script')
<script>
    function reloadTable() {
        // console.log('limit',$('#limit-notif').val());
        // console.log('search',$('#search-notif').val());
        $.ajax({
            url:"{{ url('api/notifikasi') }}",
            headers: {
                'Authorization': `Bearer {{ Auth::user()->api_token }}`,
            },
            type: "GET",
            data: {
                "id"        : "{{ Auth::user()->id }}",
                "limit"     : $('#limit-notif').val(),
                "search"    : $('#search-notif').val()
            },
            success: function(d) {
                var konten  = $('#table-notif');
                if (d.length > 0) {
                    var notif = "",
                        users = {!! json_encode(App\Models\User::select('id','name','username')->get()) !!},
                        i = 0,
                        text = "";

                    for (i=0; i<d.length; i++) {
                        var data = JSON.parse(d[i].data);
                        if (i < 5) {
                            var name = $.map(users, function(j) {
                                if (j.id == data.user) {
                                    return j.name;
                                }
                            });
                            var id = d[i].id,
                                icon = data.icon;
                            if (d[i].read_at == null) {
                                text = `<b>`+data.text+`</b>`;
                            } else {
                                text = data.text;
                            }
                            notif += `  <div class="intro-y col-span-12 md:col-span-6 mb-2">
                                            <div class="box zoom-in" id="`+id+`">
                                                <div class="flex flex-col lg:flex-row items-center p-5">
                                                    <div class="w-12 h-12 image-fit lg:mr-1">
                                                        <i data-feather="square" class="w-12 h-12"></i>
                                                    </div>
                                                    <div class="lg:ml-2 lg:mr-auto text-center lg:text-left mt-3 lg:mt-0">
                                                        <a href="javascript:;" class="font-medium">`+name+`</a>
                                                        <div class="text-gray-600 text-xs mt-0.5">`+text+`</div>
                                                    </div>
                                                    <div class="flex mt-4 lg:mt-0">
                                                        <div class="text-xs text-gray-500 ml-auto whitespace-nowrap">`+prettyDate(d[i].created_at)+`</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                        }
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
                konten.empty();
                konten.append(notif);
                feather.replace();
            },
            error: function(d) {
                console.log('d',d);
            },
        });
    }
    $(document).ready(function() {
        reloadTable();
        $('#search-notif').on('keypress',function(e){
            if(e.which == 13) {
                reloadTable();
            }
        });
        $('#search-notif').keyup(function(){
            if(!$(this).val().length) {
                reloadTable();
            } else {
                return false;
            }
        });
    });
</script>
@endsection