@extends('layouts.master')
@section('title', 'Klaim')
@section('breadcrumb', 'Klaim')
@section('menu', 'Klaim')
@section('content')
    <div class="intro-y flex items-center mt-4">
        <h2 class="text-lg font-medium mr-auto">
            Data Klaim
        </h2>
    </div>
    {{-- {{ dd($tableLaporan) }} --}}
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-feather="umbrella" class="report-box__icon text-theme-10"></i> 
                        <div class="ml-auto">
                            <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer"> 33% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg> </div>
                        </div>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">4.710</div>
                    <div class="text-base text-gray-600 mt-1">Klaim Diajukan</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-feather="user-check" class="report-box__icon text-theme-6"></i> 
                        <div class="ml-auto">
                            <div class="report-box__indicator bg-theme-6 tooltip cursor-pointer"> 2% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down w-4 h-4 ml-0.5"><polyline points="6 9 12 15 18 9"></polyline></svg> </div>
                        </div>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">3.721</div>
                    <div class="text-base text-gray-600 mt-1">Klaim Disetujui</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-feather="award" class="report-box__icon text-theme-11"></i> 
                        <div class="ml-auto">
                            <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer"> 12% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg> </div>
                        </div>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">2.149</div>
                    <div class="text-base text-gray-600 mt-1">Klaim Dinilai</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-feather="shield" class="report-box__icon text-theme-9"></i> 
                        <div class="ml-auto">
                            <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer"> 22% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg> </div>
                        </div>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">152.040</div>
                    <div class="text-base text-gray-600 mt-1">Klaim Selesai</div>
                </div>
            </div>
        </div>
    </div>
    <div class="intro-y box mt-5">
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
                
                var start = moment().subtract(1, 'month'),
                    end   = moment();

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
                        url: "{{ url('api/dataklaim') }}",
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
