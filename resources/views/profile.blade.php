@extends('layouts.master')
@section('title', 'Profile')
@section('breadcrumb', 'Profile')
@section('menu', 'Profile')
@section('content')
    <h2 class="intro-y text-lg font-medium mt-5">
        Profile
    </h2>
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
            <div class="intro-y box mt-5">
                <div class="relative flex items-center p-5">
                    <div class="w-12 h-12 image-fit">
                        <img alt="Rubick Tailwind HTML Admin Template" class="rounded-full"
                            src="https://image.flaticon.com/icons/png/512/1077/1077114.png">
                    </div>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base">{{ Auth::user()->name }}</div>
                        <div class="text-gray-600">{{ Auth::user()->getRoleNames()[0] }}</div>
                    </div>
                </div>
                <div class="p-5 border-t border-gray-200 dark:border-dark-5">
                    <a class="flex link-profile items-center text-theme-1 dark:text-theme-10 font-medium"
                        data-target="#informasi" href="#"> <i data-feather="activity" class="w-4 h-4 mr-2"></i>
                        Data Informasi </a>
                    <a class="flex link-profile items-center mt-5" data-target="#changepw" href="#"> <i data-feather="lock"
                            class="w-4 h-4 mr-2"></i>
                        Ubah
                        Katasandi
                    </a>
                </div>
            </div>
        </div>
        <!-- END: Profile Menu -->
        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
            <!-- BEGIN: Display Information -->
            <div class="intro-y box lg:mt-5 card-profile" id="informasi">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Data Informasi
                    </h2>
                </div>
                <div class="p-5">
                    <form id="frm-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="method" value="update">
                        <div class="flex flex-col-reverse xl:flex-row flex-col">
                            <div class="flex-1 mt-6 xl:mt-0">
                                <div class="grid grid-cols-12 gap-x-5">
                                    <div class="col-span-12 2xl:col-span-6">
                                        <div>
                                            <label for="nama" class="form-label">Nama</label>
                                            <input id="nama" type="text" class="form-control" name="name"
                                                placeholder="Nama Lengkap" value="{{ Auth::user()->name }}" required>
                                        </div>
                                        <div class="mt-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input id="username" type="text" class="form-control" name="username"
                                                placeholder="Username untuk Login" value="{{ Auth::user()->username }}"
                                                required>
                                        </div>
                                        <div class="mt-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input id="email" type="text" class="form-control" name="email"
                                                placeholder="Alamat Email" value="{{ Auth::user()->email }}" required>
                                        </div>
                                        <div class="mt-3">
                                            <label for="notelp" class="form-label">Nomor Telepon</label>
                                            <div class="input-group">
                                                <div class="input-group-text">+62</div>
                                                <input id="notelp" type="text" class="form-control"
                                                    placeholder="Nomor Telepon" name="notelp" aria-describedby="notelp"
                                                    value="{{ Auth::user()->notelp }}" required>
                                            </div>
                                        </div>
                                        @if(in_array(Auth::user()->getRoleNames()[0],['broker','adm']))
                                            <div class="mt-3">
                                                <label for="cabang" class="form-label">Cabang</label>
                                                <select id="cabang" data-search="true" class="tom-select w-full" name="cabang"
                                                    required @unlessrole('adm|broker') readonly @endunlessrole>
                                                    @foreach ($cabang as $cab)
                                                        <option value="{{ $cab->id }}" @if ($cab->id === Auth::user()->cabang) selected="true" @endif>
                                                            {{ $cab->nama_cabang }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-3">
                                                <label for="level" class="form-label">Level</label>
                                                <select id="level" data-search="true" class="tom-select w-full" name="level"
                                                    required @unlessrole('adm|broker') readonly @endunlessrole>
                                                    @foreach ($level as $lvl)
                                                        <option value="{{ $lvl->msid }}" @if ($lvl->msid === Auth::user()->level) selected="true" @endif>
                                                            {{ $lvl->msdesc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-3">
                                                <label for="parent" class="form-label">Parent</label>
                                                <select id="parent" data-search="true" class="tom-select w-full" name="parent"
                                                    required @unlessrole('adm|broker') readonly @endunlessrole>
                                                    @foreach ($parent as $prt)
                                                        <option value="{{ $prt->id }}" @if ($prt->id === Auth::user()->parent_id) selected="true" @endif>
                                                            {{ $prt->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="mt-3">
                                                <label for="cabang" class="form-label">Cabang</label>
                                                <input id="cabang" type="text" class="form-control" placeholder="Cabang"
                                                    value="{{ $cabang->filter(function($item) {
                                                        return $item->id == Auth::user()->id_cabang;
                                                    })->first()->nama_cabang; }}" disabled>
                                            </div>
                                            <div class="mt-3">
                                                <label for="level" class="form-label">Level</label>
                                                <input id="level" type="text" class="form-control" placeholder="Level"
                                                    value="{{ $level->filter(function($item) {
                                                        return $item->msid == Auth::user()->getRoleNames()[0];
                                                    })->first()->msdesc; }}" disabled>
                                            </div>
                                            <div class="mt-3">
                                                <label for="parent" class="form-label">Parent</label>
                                                <input id="parent" type="text" class="form-control" placeholder="Parent"
                                                    value="{{ $parent->filter(function($item) {
                                                        return $item->id == Auth::user()->id_parent;
                                                    })->first()->name; }}" disabled>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-20 mt-3">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Display Information -->
            <!-- BEGIN: Personal Information -->
            <div class="intro-y box mt-5 card-profile" id="changepw" style="display:none">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Ubah Katasandi
                    </h2>
                </div>
                <div class="p-5">
                    <form id="frm-password">
                        @csrf
                        <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="method" value="update">
                        <input type="checkbox" name="update_pw" checked hidden>
                        <div class="grid grid-cols-12 gap-x-5">
                            <div class="col-span-12 xl:col-span-6">
                                <div>
                                    <label for="update-profile-form-6" class="form-label">Katasandi Lama</label>
                                    <div class="input-group">
                                        <input id="update-profile-form-6" name="old_password" type="password" class="form-control" required placeholder="Katasandi Lama">
                                        <div class="input-group-text"><button tabindex="-1" type="button" class="form-control toggle-password"><i class="fa fa-eye"></i></button></div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="update-profile-form-7" class="form-label">Katasandi Baru</label>
                                    <div class="input-group">
                                        <input id="update-profile-form-7" name="new_password" type="password" class="form-control" required placeholder="Katasandi Baru">
                                        <div class="input-group-text"><button tabindex="-1" type="button" class="form-control toggle-password"><i class="fa fa-eye"></i></button></div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="update-profile-form-7" class="form-label">Ulangi Katasandi Baru</label>
                                    <div class="input-group">
                                        <input id="update-profile-form-7" name="new_password_1" type="password" class="form-control" required placeholder="Ulangi Katasandi Baru">
                                        <div class="input-group-text"><button tabindex="-1" type="button" class="form-control toggle-password"><i class="fa fa-eye"></i></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="btn btn-primary w-20 mr-auto">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Personal Information -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('.toggle-password').click(function(){
                var icon = $(this).children('i');
                icon.toggleClass('fa-eye fa-eye-slash');

                if (icon.attr('class').indexOf("slash") >= 0) {
                    var tipe = "text";
                } else {
                    var tipe = "password";
                }

                $(this).parent().parent().find('input').attr('type',tipe);
            });

            $('.link-profile').click(function() {
                $('.link-profile').removeClass('text-theme-1 dark:text-theme-10 font-medium');
                $(this).addClass('text-theme-1 dark:text-theme-10 font-medium');

                $('.card-profile').css('display', 'none');
                $($(this).attr('data-target')).removeAttr('style');
            });

            $('#frm-data').submit(function(e){
                e.preventDefault();
                var data = $(this).serializeArray();
                console.log('data',data);
                $.ajax({
                    type: "post",
                    url: "{{ url('api/user') }}",
                    data: data,
                    success: function (d) {
                        console.log('d',d);
                        Swal.fire(
                            'Berhasil!',
                            d.message,
                            'success'
                        ).then(function() {
                            Swal.fire(
                                'Perhatian',
                                'Halaman web akan direfresh untuk memperbaharui data',
                                'info'
                            ).then(function() {
                                location.reload(true);
                            });
                        });
                    },
                    error: function (d) {
                        var message = "";
                        $.each(d.responseJSON.errors, function (i, v) { 
                             $.each(v, function (n, m) { 
                                  $.each(m, function (o, p) { 
                                       message += p+"<br>";
                                  });
                             });
                        });
                        Swal.fire(
                            'Gagal!',
                            message,
                            'error'
                        );
                    },
                });
            });

            $('#frm-password').submit(function(e){
                e.preventDefault();
                var data = $(this).serializeArray();
                if (data[5].value !== data[6].value) {
                    Swal.fire(
                        'Gagal!',
                        'Katasandi baru tidak sama',
                        'error'
                    );
                } else {
                    data['update_pw'] = true;
                    $.ajax({
                        type: "post",
                        url: "{{ url('api/user') }}",
                        data: data,
                        success: function (d) {
                            console.log('d',d);
                            Swal.fire(
                                'Berhasil!',
                                d.message,
                                'success'
                            ).then(function() {
                                Swal.fire(
                                    'Perhatian',
                                    'Harap melakukan login kembali untuk melihat perubahan',
                                    'info'
                                ).then(function() {
                                    location.href="{{ url('logout') }}";
                                })
                            });
                        },
                        error: function (d) {
                            var message = "";
                            $.each(d.responseJSON.errors, function (i, v) { 
                                 $.each(v, function (n, m) { 
                                      $.each(m, function (o, p) { 
                                           message += p+"<br>";
                                      });
                                 });
                            });
                            Swal.fire(
                                'Gagal!',
                                message,
                                'error'
                            )
                        },
                    });
                }
            });
        });
    </script>
@endsection
