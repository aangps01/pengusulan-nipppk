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
                    <div class="col-lg-4">
                        <label for="status" class="col-form-label">Status Validasi</label>
                        <select id="status" class="form-select filter-select" data-column="5">
                            <option value=""></option>
                            <option value="1" class="text-sm">Pengajuan Baru</option>
                            <option value="2" class="text-sm">Sedang Verifikasi</option>
                            <option value="3" class="text-sm">Revisi</option>
                            <option value="4" class="text-sm">Verifikasi Ulang</option>
                            <option value="5" class="text-sm">Selesai</option>
                            <option value="6" class="text-sm">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="tanggal_pengajuan" class="col-form-label">Tanggal Pengajuan</label>
                        <input type="text" class="form-control filter-select" name="tanggal_pengajuan"
                            id="tanggal_pengajuan" placeholder="Masukkan tanggal pengajuan" autocomplete="off">
                    </div>
                    <div class="col-lg-4">
                        <label for="tanggal_validasi" class="col-form-label">Tanggal Validasi</label>
                        <input type="text" class="form-control filter-select" name="tanggal_validasi"
                            id="tanggal_validasi" placeholder="Masukkan tanggal validasi" autocomplete="off">
                    </div>
                </div>
                <button class="btn btn-primary d-block w-100 mt-3" onclick="resetSearch()">Reset Filter</button>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="data-permohonan-table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="">NIK</th>
                                <th class="">Tanggal Pengusulan</th>
                                <th class="">Tanggal Persetujuan</th>
                                <th class="">Status</th>
                                <th class="d-none">id</th>
                                <th class="d-none">status</th>
                                <th class="d-none">tanggal pengusulan timestamp</th>
                                <th class="d-none">tanggal validasi timestamp</th>
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

        function resetSearch() {
            $('#status').val('').trigger('change');
            $('input[name="tanggal_pengajuan"]').val('').trigger('change');
            $('input[name="tanggal_validasi"]').val('').trigger('change');
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
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')).trigger('change');
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
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')).trigger('change');
        });
        $('input[name="tanggal_validasi"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('').trigger('change');
        });
    </script>
    <script>
        var table = $('#data-permohonan-table').DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            ajax: {
                url: "{{ url()->current() }}",
                data: function(d) {
                    d.status = $('#status').val();
                    d.tanggal_pengajuan = $('input[name="tanggal_pengajuan"]').val();
                    d.tanggal_validasi = $('input[name="tanggal_validasi"]').val();
                }
            },
            dom: 'lBfrtip',
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="isax isax-export-1 me-2"></i>Export Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: [1]
                },
                action: function(e, dt, node, config) {
                    // target blank
                    window.open("{{ route('admin.permohonan.export') }}?status=" + $('#status').val() +
                        "&tanggal_pengajuan=" + $('input[name="tanggal_pengajuan"]').val() +
                        "&tanggal_validasi=" + $('input[name="tanggal_validasi"]').val());

                    // window.location.href = "{{ url('admin/permohonan/export') }}?status=" + $('#status').val() +
                    //     "&tanggal_pengajuan=" + $('input[name="tanggal_pengajuan"]').val() +
                    //     "&tanggal_validasi=" + $('input[name="tanggal_validasi"]').val();
                }
            }],
            language: {
                url: "{{ asset('assets/vendors/datatables-lang-id.json') }}"
            },
            columnDefs: [{
                targets: [5, 6, 7, 8],
                visible: false,
            }, {
                targets: [5, 6],
                className: 'text-center',
            }, {
                targets: [2, 3],
                orderData: [7, 8],
            }],
            // creating row

            createdRow: function(row, data, dataIndex) {
                // set data active bg
                if (data[6] == 1 || data[6] == 4) {
                    $(row).addClass('data-active');
                }
                $(row).addClass('clickable-row');
                $(row).attr('onclick', 'window.location.href = "{{ url('admin/permohonan/verifikasi') }}/' +
                    data[
                        5] +
                    '"');
            },
        });

        $('.filter-select').on('change', function() {
            table.ajax.reload();
        });
    </script>
@endpush
