@extends('layouts.master')
@section('title', 'Laporan')
@section('breadcrumb', 'Laporan')
@section('menu', 'Laporan')
@section('content')
    <div class="intro-y flex items-center mt-4">
        <h2 class="text-lg font-medium mr-auto">
            Cetak Laporan
        </h2>
    </div>
    @if(empty($data))
        <div class="intro-y box py-10 sm:py-20 mt-5">
            <form action="{{ url('laporan') }}" method="POST">
                @csrf
                <input type="hidden" name="judul">
                <div class="px-5 sm:px-20 dark:border-dark-5">
                    <div class="grid grid-cols-12 gap-4 gap-y-5">
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label for="type-insurance" class="form-label">Tipe Asuransi</label>
                            <select id="type-insurance" name="type-insurance" required style="width:100%">
                                <option value="ALL" selected>Semua Tipe Asuransi</option>
                                @foreach ($instype as $val)
                                    <option value="{{ $val->id }}">{{ $val->instype_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label for="cabang" class="form-label">Cabang</label>
                            <select id="cabang" name="cabang" required style="width:100%">
                                @role('broker|insurance|checker|approver|adm')
                                <option value="ALL" selected>Semua Cabang</option>
                                @endrole
                                @foreach ($cabang as $val)
                                    <option value="{{ $val->id }}" @if ($val->id === Auth::user()->cabang) selected="true" @endif>{{ $val->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label for="asuransi" class="form-label">Asuransi</label>
                            <select id="asuransi" name="asuransi" required style="width:100%">
                                <option value="ALL" selected>Semua Asuransi</option>
                                @foreach ($asuransi as $val)
                                    <option value="{{ $val->id }}">
                                        {{ $val->nama_asuransi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label for="range-periode" class="form-label">Periode</label>
                            <input id="range-periode" data-daterange="true" class="datepicker form-control w-full block mx-auto">
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label for="jenis" class="form-label">Jenis Laporan</label>
                            <select id="jenis" name="jenis" required style="width:100%">
                                @foreach ($laporan as $val)
                                    <option value="{{ $val->id }}" d-date="{{ $val->lapdate }}">
                                        {{ $val->lapdesc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="intro-y col-span-12 flex items-center justify-center sm:justify-end mt-5">
                            <button type="submit" class="btn btn-primary w-24 ml-2">Cetak</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
    @if (!empty($data))
    {{-- {{ dd($data) }} --}}
        <div class="intro-y box mt-5">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{ $data->judul }}
                </h2>
            </div>
            <div class="p-5" id="hoverable-table">
                <div class="preview">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">First Name</th>
                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Last Name</th>
                                    <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">Username</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('select').select2();

            $('[name="judul"]').val($('#jenis option:selected').text().trim() + " - " + $('#cabang option:selected').text().trim() + " (" + $('#range-periode').val() + ")");
            $('#jenis').on('select2:select',function(){
                $('[name="judul"]').val($('#jenis option:selected').text().trim() + " - " + $('#cabang option:selected').text().trim()  + " (" + $('#range-periode').val() + ")");
            })
            @if (!empty($data))
                var tablenya = $('table').DataTable();
                tablenya.on('draw', function() {
                    paginatioon(tablenya,$('ul.pagination'));
                    
                    feather.replace();
                    
                    $('.gotoPage').click(function() {
                        gotoPage($(this),tablenya);
                    });
                });
                tablenya.draw();
            @endif
        });
    </script>
@endsection
