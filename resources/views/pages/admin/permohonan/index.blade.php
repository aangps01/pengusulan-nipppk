@extends('layouts.dashboard-base')

@section('content')
    <div class="page-heading mb-3">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Permohonan</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <label class="col-form-label"
                               for="status">Status Validasi</label>
                        <select class="form-select filter-select"
                                data-column="5"
                                id="status">
                            <option value=""></option>
                            <option class="text-sm"
                                    value="1">Pengajuan Baru</option>
                            <option class="text-sm"
                                    value="2">Sedang Verifikasi</option>
                            <option class="text-sm"
                                    value="3">Revisi</option>
                            <option class="text-sm"
                                    value="4">Verifikasi Ulang</option>
                            <option class="text-sm"
                                    value="5">Selesai</option>
                            <option class="text-sm"
                                    value="6">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="col-form-label"
                               for="tanggal_pengajuan">Tanggal Pengajuan</label>
                        <input autocomplete="off"
                               class="form-control filter-select"
                               id="tanggal_pengajuan"
                               name="tanggal_pengajuan"
                               placeholder="Masukkan tanggal pengajuan"
                               type="text">
                    </div>
                    <div class="col-lg-3">
                        <label class="col-form-label"
                               for="tanggal_validasi">Tanggal Validasi</label>
                        <input autocomplete="off"
                               class="form-control filter-select"
                               id="tanggal_validasi"
                               name="tanggal_validasi"
                               placeholder="Masukkan tanggal validasi"
                               type="text">
                    </div>
                    <div class="col-lg-3">
                        <label class="col-form-label"
                               for="jenis">Jenis CPPPK</label>
                        <select class="form-select filter-select"
                                data-column="5"
                                id="jenis">
                            <option value=""></option>
                            <option class="text-sm"
                                    value="nakes">Tenaga Kesehatan</option>
                            <option class="text-sm"
                                    value="guru">Guru</option>
                            <option class="text-sm"
                                    value="teknis">Teknis</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary d-block w-100 mt-3"
                        onclick="resetSearch()">Reset Filter</button>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover"
                           id="data-permohonan-table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="">NIK</th>
                                <th class="">Nama</th>
                                <th class="">Tanggal Pengusulan</th>
                                <th class="">Tanggal Persetujuan</th>
                                <th class="">Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .data-active>* {
            background: rgba(47, 65, 133, 0.2) !important;
            /* clear box shadow */
            box-shadow: none !important;
        }

        .data-active:hover {
            background: rgba(77, 108, 222, 0.2) !important;
            /* clear box shadow */
            box-shadow: none !important;
        }

        .clickable-row {
            cursor: pointer;
        }

        .dt-buttons {
            display: inline;
            margin-left: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $('#status').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Semua',
            dropdownParent: $('#status').parent(),
            allowClear: true,
        });
        $('#jenis').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Semua',
            dropdownParent: $('#jenis').parent(),
            allowClear: true,
        });

        function resetSearch() {
            $('#status').val('').trigger('change');
            $('input[name="tanggal_pengajuan"]').val('').trigger('change');
            $('input[name="tanggal_validasi"]').val('').trigger('change');
            $('#jenis').val('').trigger('change');
        }

        $('input[name="tanggal_pengajuan"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            },
            alwaysShowCalendars: true,
        });
        $('input[name="tanggal_pengajuan"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
                .trigger('change');
        });
        $('input[name="tanggal_pengajuan"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('').trigger('change');
        });

        $('input[name="tanggal_validasi"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            },
            alwaysShowCalendars: true,
        });
        $('input[name="tanggal_validasi"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
                .trigger('change');
        });
        $('input[name="tanggal_validasi"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('').trigger('change');
        });
    </script>
    <script>
        var table = $('#data-permohonan-table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            ajax: {
                url: "{{ route('admin.permohonan.table') }}",
                data: function(d) {
                    d.status = $('#status').val();
                    d.tanggal_pengajuan = $('input[name="tanggal_pengajuan"]').val();
                    d.tanggal_validasi = $('input[name="tanggal_validasi"]').val();
                    d.jenis = $('#jenis').val();
                }
            },
            language: {
                url: "{{ asset('assets/vendors/datatables-lang-id.json') }}"
            },
            columns: [{
                    data: null,
                    name: 'index',
                    searchable: false,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nik',
                    name: 'nik'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'tanggal_pengajuan',
                    name: 'tanggal_pengajuan'
                },
                {
                    data: 'tanggal_validasi',
                    name: 'tanggal_validasi'
                },
                {
                    data: 'status',
                    name: 'status'
                },
            ],

            createdRow: function(row, data, dataIndex) {
                // set data active bg
                if (data[7] == 1 || data[7] == 4) {
                    $(row).addClass('data-active');
                }
                $(row).addClass('clickable-row');
                $(row).attr('onclick', 'window.location.href = "{{ url('admin/permohonan/verifikasi') }}/' +
                    data.id +
                    '"');
            },
        });

        $('.filter-select').on('change', function() {
            table.ajax.reload();
        });
    </script>
@endpush
