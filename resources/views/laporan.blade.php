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
    <div class="intro-y box py-10 sm:py-20 mt-5">
        <div class="px-5 sm:px-20 dark:border-dark-5">
            <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                <div class="intro-y col-span-12 sm:col-span-6">
                    <label for="type-insurance" class="form-label">Tipe Asuransi</label>
                    <select id="type-insurance" name="type-insurance" required style="width:100%">
                        <option value="ALL" selected>Semua Tipe Asuransi</option>
                        @foreach ($instype as $val)
                            <option value="{{ $val->msid }}">{{ $val->msdesc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="intro-y col-span-12 sm:col-span-6">
                    <label for="cabang" class="form-label">Cabang</label>
                    <select id="cabang" name="cabang" required style="width:100%">
                        <option value="ALL" selected>Semua Cabang</option>
                        @foreach ($cabang as $val)
                            <option value="{{ $val->msid }}" @if ($val->msid === Auth::user()->cabang) selected="true" @endif>{{ $val->msdesc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="intro-y col-span-12 sm:col-span-6">
                    <label for="asuransi" class="form-label">Asuransi</label>
                    <select id="asuransi" name="asuransi" required style="width:100%">
                        <option value="ALL" selected>Semua Asuransi</option>
                        @foreach ($asuransi as $val)
                            <option value="{{ $val->msid }}">
                                {{ $val->msdesc }}
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
                    <button class="btn btn-primary w-24 ml-2">Cetak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('select').select2();
        });
    </script>
@endsection
