@extends('layouts.dashboard-base')

@section('content')
    <div class="page-heading mb-3">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Berkas Wajib Tambahan</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('user.dokumen-tambahan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @foreach ($dokumens as $dokumen)
                        <div class="form-group mb-3">
                            <div class="row justify-content-between">
                                <div class="col-auto"><label class="col-form-label fw-bold"
                                        for="{{ $dokumen['kode'] }}">{{ $dokumen['nama'] }}*</label>
                                </div>
                                <div class="col-auto text-primary d-flex align-items-center">
                                    <p class="d-inline mb-0" style="font-size:0.8rem;">
                                        Format Dokumen:
                                        <span class="me-3 fw-bold">pdf</span>
                                    </p>
                                    <p class="d-inline mb-0" style="font-size:0.8rem;">
                                        Ukuran Maksimal:
                                        <span class="fw-bold">1024 KB</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-auto flex-grow-1">
                                    @if (!$dokumen['is_upload'])
                                        <input type="file" accept=".pdf" class="form-control upload-berkas"
                                            name="berkas[{{ $dokumen['kode'] }}]" id="{{ $dokumen['kode'] }}"
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
                    <div class="form-group mb-3">
                        @if (!$is_all_upload)
                            <button type="submit" class="btn btn-primary w-100">Simpan Berkas</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $("body").on("change", ".upload-berkas", function() {
            let id = $(this).attr('id');
            let berkas = $(this).prop('files')[0];
            let batas_ukuran_berkas = $(this).data('maxSize');
            let ukuran_berkas = berkas.size / 1024;
            if (ukuran_berkas > batas_ukuran_berkas) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ukuran berkas melebihi batas maksimal!',
                });
                $(this).val('');
            }

            // cek apakah type file sesuai ekstensi
            let type_file = $(this).attr('accept');
            // let type_file_berkas = get extension file
            let type_file_berkas = berkas.name.split('.').pop();
            if (type_file.indexOf(type_file_berkas) == -1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Type file berkas tidak sesuai!',
                });
                $(this).val('');
            }
        });
    </script>
@endpush
