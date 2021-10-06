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
                            <label for="instype" class="form-label">Tipe Asuransi</label>
                            <select id="instype" name="instype" required style="width:100%" class="pilih">
                                <option value="ALL" selected>Semua Tipe Asuransi</option>
                                @foreach ($instype as $val)
                                    <option value="{{ $val->id }}">{{ $val->instype_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label for="cabang" class="form-label">Cabang</label>
                            <select id="cabang" name="cabang" required style="width:100%" class="pilih">
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
                            <select id="asuransi" name="asuransi" required style="width:100%" class="pilih">
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
                            <input id="range-periode" class="form-control w-full block mx-auto">
                            <input name="periode_start" type="hidden">
                            <input name="periode_end" type="hidden">
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label for="jenis" class="form-label">Jenis Laporan</label>
                            <select id="jenis" name="jenis" required style="width:100%" class="pilih">
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
    {{-- {{ dd($tableLaporan) }} --}}
        <div class="intro-y box mt-5">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{ $data->judul }}
                </h2>
            </div>
            <div class="p-5" id="hoverable-table">
                <div class="preview">
                    <div class="overflow-x-auto">
                        <table class="table nowrap">
                            <thead>
                                <tr>
                                    @foreach ($columns as $i => $v)    
                                        <th class="border border-b-2 dark:border-dark-5 whitespace-nowrap">{!! (preg_match('!\"([^\)]+)\"!',$v,$m))?($m[1]):("") !!}</th>
                                    @endforeach
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
            @if (empty($data))
                $('select').select2();
                
                function judul() {
                    $('[name="judul"]').val($('#jenis option:selected').text().trim() + " - " + $('#cabang option:selected').text().trim()  + " (" + $('#range-periode').val() + ")");
                };

                $('.pilih').on('change',function(){
                    judul();
                });
                
                $('#range-periode').inputmask("99/99/9999 - 99/99/9999");
                
                var start = moment();
                var end = moment().add(1, 'month');

                function cb(start, end) {
                    $('#range-periode').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                    $('[name="periode_start"]').val(start.format('YYYY-MM-DD'));
                    $('[name="periode_end"]').val(end.format('YYYY-MM-DD'));
                    judul();
                }

                $('#range-periode').daterangepicker({
                    autoApply: true,
                    showDropdowns: true,
                    startDate: start,
                    endDate: end,
                    locale: {
                        format: 'DD/MM/YYYY'
                    },
                }, cb);

                cb(start,end);

                $('[name="judul"]').val($('#jenis option:selected').text().trim() + " - " + $('#cabang option:selected').text().trim() + " (" + $('#range-periode').val() + ")");

            @else
                $(document).attr("title", "{{ $data->judul }}");
                var oldExportAction = function (self, e, dt, button, config) {
                    if (button[0].className.indexOf('buttons-excel') >= 0) {
                        if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
                        }
                        else {
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                        }
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                };
                var newExportAction = function (e, dt, button, config) {
                    var self = this;
                    var oldStart = dt.settings()[0]._iDisplayStart;
                    
                    dt.one('preXhr', function (e, s, data) {
                        // Just this once, load all data from the server...
                        data.start = 0;
                        data.length = 2147483647;
                        
                        dt.one('preDraw', function (e, settings) {
                            // Call the original action function
                            oldExportAction(self, e, dt, button, config);
                            
                            dt.one('preXhr', function (e, s, data) {
                                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                                // Set the property to what it was before exporting.
                                settings._iDisplayStart = oldStart;
                                data.start = oldStart;
                            });
                            
                            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                            setTimeout(dt.ajax.reload, 0);
                            
                            // Prevent rendering of the full data to the DOM
                            return false;
                        });
                    });
                    
                    // Requery the server with the new one-time export settings
                    dt.ajax.reload();
                };
                var tablenya = $('table').DataTable({
                    "dom": "lfBrtip",
                    "processing": true,
                    "serverSide": true,
                    "bLengthChange": true,
                    buttons: [
                        {
                            extend: 'excel',
                            text: "<i class='fa fa-file-excel' class='mr-2'></i> Export to Excel",
                            action: newExportAction
                        },
                    ],
                    "ajax": {
                        url: "{{ url('api/datalaporan') }}",
                        headers: {
                            'Authorization': `Bearer {{ Auth::user()->api_token }}`,
                        },
                        type: "POST",
                        data: function(d) {
                            d.search        = $("#DataTables_Table_0_filter label input").val();
                            d._token        = '{{ csrf_token() }}';
                            d.periode_start = "{{ $data->periode_start }}";
                            d.periode_end   = "{{ $data->periode_end }}";
                            d.instype       = "{{ $data->instype }}";
                            d.cabang        = "{{ $data->cabang }}";
                            d.asuransi      = "{{ $data->asuransi }}";
                            d.jenis         = "{{ $data->jenis }}";
                            d.dtable        = true;
                            // console.log('datanya: ', d);
                        },
                            // success: function(d) {
                            //     $.each(d.sql, function(i,v){
                            //         console.log(v.query);
                            //     });
                            //     console.log('success:', d);
                            // },
                        error: function(d) {
                            console.log('error:', d);
                        },
                    },
                    "createdRow": function(row, data, dataIndex) {
                        $(row).addClass("hover:bg-gray-200");
                    }
                });
                tablenya.on('draw', function() {
                    paginatioon(tablenya,$('ul.pagination'));
                    
                    feather.replace();
                    
                    $('.gotoPage').click(function() {
                        gotoPage($(this),tablenya);
                    });
                    $('table > tbody > tr').each(function() {
                        $(this).addClass('dark:hover:bg-dark-2');
                    });
                });
            @endif
        });
    </script>
@endsection
