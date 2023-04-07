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
        var table = $('#data-permohonan-table').DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            ajax: {
                url: "{{ url()->current() }}",
            },
            dom: 'lfrtip',
            language: {
                url: "{{ asset('assets/vendors/datatables-lang-id.json') }}"
            },
            columnDefs: [{
                targets: [5, 6, 7],
                visible: false,
            }, {
                targets: [5, 6],
                className: 'text-center',
            }, {
                targets: [2],
                orderData: [7],
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
    </script>
@endpush
