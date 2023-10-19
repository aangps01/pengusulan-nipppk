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
        {{-- CARD SHOW $permohonan->user->nik --}}
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-between mb-4">
                    <div class="col-auto">
                        <h5 class="fs-6">Detail Berkas Persyaratan</h5>
                    </div>
                    <div class="col-auto">
                        {!! $permohonan->badge_status !!}
                    </div>
                </div>
                @if ($permohonan->status == 4 || $permohonan->status == 5)
                    <button onclick="resetPermohonan()" class="btn btn-primary mb-3"><i
                            class="isax isax-edit-2 me-2"></i>Reset Permohonan</button>
                @endif
                @if ($permohonan->status == 6)
                    <span class="badge bg-danger mb-4">Alasan Penolakan : {{ $permohonan->keterangan }}</span>
                @endif
                @foreach ($berkas_permohonan as $berkas)
                    <div class="form-group mb-3">
                        <label class="col-form-label fw-bold"
                            for="{{ $berkas->get('key') }}">{{ $berkas->get('nama') }}{{ $berkas->get('is_required') ? '*' : '' }}</label>
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="{{ $berkas->get('filename') }}"
                                    style="height: 50px;" disabled>
                            </div>
                            <div class="col-lg-2">
                                @if ($berkas->get('status') != 'Belum Verifikasi')
                                    <button type="button" class="btn btn-outline-primary d-block w-100"
                                        onclick="lihatData('{{ $berkas->get('filepath') }}')" style="height: 50px;">
                                        Lihat Data</button>
                                @else
                                    <button type="button" class="btn btn-primary d-block w-100"
                                        onclick="validasiBerkas(this)" style="height: 50px;"
                                        data-detail-id="{{ $berkas->get('detail_berkas_id') }}"
                                        data-nama="{{ $berkas->get('nama') }}" data-url="{{ $berkas->get('filepath') }}">
                                        Verifikasi</button>
                                @endif
                            </div>
                            <div class="col-lg-2 d-block text-center">
                                {!! $berkas->get('badge_status') !!}
                            </div>
                            @if ($berkas->get('status') == 'Revisi')
                                <div class="col-12">
                                    <p class="fw-bold text-danger mb-0 mt-2">Revisi :
                                        {{ $berkas->get('keterangan') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <span
                    class="mb-3 badge {{ $permohonan->is_upload_dokumen_wajib_tambahan ? 'bg-success' : 'bg-danger' }}">{{ $permohonan->is_upload_dokumen_wajib_tambahan ? 'Dokumen tambahan sudah lengkap' : 'Dokumen tambahan belum lengkap' }}</span>
                @foreach ($dokumens as $dokumen)
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-auto"><label class="col-form-label fw-bold"
                                    for="{{ $dokumen['kode'] }}">{{ $dokumen['nama'] }}*</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-auto flex-grow-1">
                                @if (!$dokumen['is_upload'])
                                    <input type="file" accept=".pdf" class="form-control upload-berkas"
                                        value="-"
                                        data-max-size="1024">
                                @else
                                    <div class="row">
                                        <div class="col-9">
                                            <input type="text" class="form-control" value="{{ $dokumen['nama'] }}"
                                                disabled>
                                        </div>
                                        <div class="col-3">
                                            <a href="{{ Storage::url($dokumen['filepath']) }}"
                                                class="btn btn-secondary w-100">Download</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group d-flex justify-content-between mb-4">
            @if ($permohonan->status != 6 && $permohonan->status != 3 && $permohonan->status != 5)
                <button onclick="tolakPermohonan();" class="btn btn-danger px-4 py-3"><i
                        class="isax isax-close-circle me-2"></i> Tolak</button>
                @if ($is_semua_berkas_terverifikasi)
                    <button onclick="selesaiPermohonan();" type="button" class="btn btn-primary px-4 py-3"><i
                            class="isax isax-tick-circle me-2"></i> Selesai</button>
                @else
                    <button type="button" class="btn btn-primary px-4 py-3" disabled><i
                            class="isax isax-tick-circle me-2"></i> Selesai</button>
                @endif
            @endif
        </div>

        {{-- MODAL VALIDASI BERKAS --}}
        <div class="modal fade" id="modalValidasiBerkas" tabindex="-1" aria-labelledby="modalValidasiBerkasLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalValidasiBerkasLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="berkas"></div>
                    </div>
                    <div class="modal-footer justify-content-between section-verifikasi-berkas">
                        <div class="left">
                            <a id="download-berkas" class="btn btn-outline-primary" style="height: 50px; line-height:36px"
                                download=""><i class="isax isax-document-download me-2"></i> Download Dokumen</a>
                        </div>
                        <div class="right">
                            <button type="button" class="btn btn-outline-primary me-2" onclick="revisiBerkas();"
                                style="height: 50px;"><i class="isax isax-edit-2 me-2"></i> Revisi</button>
                            <button type="button" onclick="submitValidBerkas();" class="btn btn-primary"
                                style="height: 50px;"><i class="isax isax-tick-circle me-2"></i> Valid</button>
                        </div>
                    </div>
                    <div class="modal-footer section-verifikasi-berkas d-block d-none">
                        <input type="hidden" id="detail_berkas_id">
                        <div class="form-group mb-3">
                            <label for="" class="form-label"></label>
                            <input type="text" class="form-control" name="catatan_revisi_berkas"
                                id="catatan_revisi_berkas" style="height: 60px;" placeholder="Catatan Revisi">
                        </div>
                        <div class="form-group text-end mt-3">
                            <button type="button" class="btn btn-outline-primary me-2 px-4"
                                onclick="batalRevisiBerkas();" style="height: 50px;"><i
                                    class="isax isax-close-circle me-2"></i> Batal</button>
                            <button type="button" class="btn btn-primary" onclick="submitRevisiBerkas();"
                                style="height: 50px;"><i class="isax isax-send-2 me-2"></i> Kirim
                                Revisi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function lihatData(url) {
            window.open(url, '_blank');
        }

        function validasiBerkas(e) {
            var detail_id = $(e).data('detail-id');
            let nama = $(e).data('nama');
            let url = $(e).data('url');

            $('#modalValidasiBerkasLabel').html(nama);
            $('#berkas').html('<iframe src="' + url + '" width="100%" height="500px"></iframe>');
            $('#download-berkas').attr('href', url);
            $('#detail_berkas_id').val(detail_id);

            $('#modalValidasiBerkas').modal('show');
        }

        function revisiBerkas() {
            $('.section-verifikasi-berkas').toggleClass('d-none');
            $('.section-revisi-berkas').toggleClass('d-none');
        }

        function batalRevisiBerkas() {
            $('.section-verifikasi-berkas').toggleClass('d-none');
            $('.section-revisi-berkas').toggleClass('d-none');
        }

        function submitRevisiBerkas() {
            var id = $('#detail_berkas_id').val();
            var catatan_revisi = $('#catatan_revisi_berkas').val();
            if (catatan_revisi == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Catatan revisi tidak boleh kosong!',
                });
                return;
            }
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan melakukan revisi pada berkas ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, revisi!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.permohonan.berkas.revisi') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "catatan_revisi": catatan_revisi,
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Berhasil!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Berkas gagal direvisi.',
                                'error'
                            );
                        }
                    });
                }
            })
        }

        function submitValidBerkas() {
            var id = $('#detail_berkas_id').val();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan menandai berkas ini sebagai valid!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, valid!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.permohonan.berkas.valid') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Berhasil!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Berkas gagal divalidasi.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function tolakPermohonan() {
            // SWAL with input
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan menolak permohonan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Sweet alert with input
                    Swal.fire({
                        title: 'Masukkan alasan penolakan',
                        input: 'textarea',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        showLoaderOnConfirm: true,
                        // ajax
                        preConfirm: (alasan_penolakan) => {
                            // alasan penolakan required
                            if (alasan_penolakan == '') {
                                Swal.fire(
                                    'Gagal!',
                                    'Alasan penolakan tidak boleh kosong.',
                                    'error'
                                );
                                return;
                            }
                            $.ajax({
                                url: "{{ route('admin.permohonan.tolak') }}",
                                type: "POST",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id": "{{ encrypt($permohonan->id) }}",
                                    "keterangan": alasan_penolakan,
                                },
                                success: function(response) {
                                    if (response.status == 'success') {
                                        Swal.fire(
                                            'Berhasil!',
                                            response.message,
                                            'success'
                                        ).then((result) => {
                                            if (result.isConfirmed) {
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        Swal.fire(
                                            'Gagal!',
                                            response.message,
                                            'error'
                                        );
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Gagal!',
                                        'Permohonan gagal ditolak.',
                                        'error'
                                    );
                                }
                            });
                        },
                    })
                }
            });
        }

        function selesaiPermohonan() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan menyelesaikan permohonan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.permohonan.selesai') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": "{{ encrypt($permohonan->id) }}",
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Berhasil!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Permohonan gagal diselesaikan.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function resetPermohonan() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan mereset permohonan ini. Seluruh berkas akan butuh validasi ulang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.permohonan.reset') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": "{{ encrypt($permohonan->id) }}",
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Berhasil!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Permohonan gagal direset.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endpush
