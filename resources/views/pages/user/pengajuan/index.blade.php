@extends('layouts.dashboard-base')

@section('content')
    <div class="page-heading mb-3">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Pengusulan</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        @if ($status_permohonan == 0)
            <div class="card bg-secondary">
                <div class="card-body">
                    <p class="fw-bold mb-0 text-white"><i class="isax isax-warning-2 me-2"></i>Anda belum mengusulkan NIPPPK
                    </p>
                </div>
            </div>
        @elseif($status_permohonan == -1)
            <div class="card bg-danger">
                <div class="card-body">
                    <p class="fw-bold mb-0 text-white"><i class="isax isax-warning-2 me-2"></i>Anda belum melengkapi dokumen
                        tambahan pada menu "Berkas Wajib Tambahan"
                    </p>
                </div>
            </div>
        @elseif($status_permohonan == 1)
            <div class="card bg-primary">
                <div class="card-body">
                    <p class="fw-bold mb-0 text-white"><i class="isax isax-send-1 me-2"></i>Usulan anda telah terkirim ke
                        admin
                        verifikator</p>
                </div>
            </div>
        @elseif($status_permohonan == 2 || $status_permohonan == 4)
            <div class="card bg-warning">
                <div class="card-body">
                    <p class="fw-bold mb-0 text-white"><i class="isax isax-eye me-2"></i>Usulan anda sedang diverifikasi
                        oleh admin verifikator</p>
                </div>
            </div>
        @elseif($status_permohonan == 3)
            <div class="card bg-danger">
                <div class="card-body">
                    <p class="fw-bold mb-0 text-white"><i class="isax isax-notification-status me-2"></i>Terdapat berkas
                        persyaratan yang perlu direvisi</p>
                </div>
            </div>
        @elseif($status_permohonan == 5)
            <div class="card bg-success">
                <div class="card-body">
                    <p class="fw-bold mb-0 text-white"><i class="isax isax-tick-circle me-2"></i>Seluruh berkas usulan
                        NIPPPK
                        anda telah valid</p>
                </div>
            </div>
        @elseif($status_permohonan == 6)
            <div class="card bg-danger">
                <div class="card-body">
                    <p class="fw-bold mb-0 text-white"><i class="isax isax-close-circle me-2"></i>Usulan NIPPPK anda telah
                        ditolak oleh verifikator dengan catatan : <span
                            class="fw-normal fst-italic">{{ $permohonan->keterangan }}</span></p>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-body py-3">
                <h5 class="fs-6 mb-3">Data Diri PPPK</h5>
                <div class="row">
                    <div class="col-6">
                        <table class="table text-sm">
                            <tr>
                                <td>Nomor Peserta</td>
                                <th>: {{ $user->nomor_peserta ?? '-' }}</th>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <th>: {{ $user->nik ?? '-' }}</th>
                            </tr>
                            <tr>
                                <td>Nama Sesuai Ijazah</td>
                                <th>: {{ $user->name ?? '-' }}</th>
                            </tr>
                            <tr>
                                <td>Pendidikan</td>
                                <th>: {{ $user->pendidikan ?? '-' }}</th>
                            </tr>
                            <tr>
                                <td>Gelar Depan</td>
                                <th>: {{ $user->gelar_depan ?? '-' }}</th>
                            </tr>
                            <tr>
                                <td>Gelar Belakang</td>
                                <th>: {{ $user->gelar_belakang ?? '-' }}</th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table">
                            <tr>
                                <td>Tempat Lahir</td>
                                <th>: {{ $user->tempat_lahir ?? '-' }}</th>
                            </tr>
                            <tr>
                                <td>Tanggal Lahir</td>
                                <th>: {{ $user->tanggal_lahir ? Carbon\Carbon::parse($user->tanggal_lahir)->locale('id')->isoFormat('LL') : '-' }}</th>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <th>: {{ $user->jenis_kelamin ?? '-' }}</th>
                            </tr>
                            <tr>
                                <td>Jabatan yang dilamar</td>
                                <th>: {{ $user->jabatan_dilamar ?? '-' }}</th>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <th>: {{ $user->unit_kerja ?? '-' }}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('user.pengajuan.update') }}" method="POST">
            @method('PUT')
            @csrf
            <div class="card">
                <div class="card-header pb-2"></div>
                <div class="card-body">
                    <h5 class="fs-6 mb-3">Kelengkapan Berkas Data Usulan</h5>
                    @if (!$permohonan || $permohonan->status == 1)
                        @foreach ($berkas_persyaratan as $berkas)
                            @if ($berkas->get('is_active'))
                                <div class="form-group mb-3">
                                    <div class="row justify-content-between">
                                        <div class="col-auto"><label class="col-form-label fw-bold"
                                                for="{{ $berkas->get('berkas_key') }}">{{ $berkas->get('nama') }}{{ $berkas->get('is_required') ? '*' : '' }}</label>
                                        </div>
                                        <div class="col-auto text-primary d-flex align-items-center">
                                            <p class="d-inline mb-0" style="font-size:0.8rem;">
                                                Format Dokumen:
                                                <span class="me-3 fw-bold">{{ $berkas->get('nama_format') }}</span>
                                            </p>
                                            <p class="d-inline mb-0" style="font-size:0.8rem;">
                                                Ukuran Maksimal:
                                                <span class="fw-bold">{{ $berkas->get('batas_ukuran') }} KB</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-auto flex-grow-1">
                                            <input type="file" accept="{{ $berkas->get('tipe_format') }}"
                                                class="form-control upload-berkas"
                                                name="berkas[{{ $berkas->get('berkas_key') }}]"
                                                id="{{ $berkas->get('berkas_key') }}"
                                                data-max-size="{{ $berkas->get('batas_ukuran') }}">
                                        </div>
                                        <div class="col-lg-2 d-flex justify-content-end {{ !$berkas->get('berkas_filepath') ? 'd-none' : null }}"
                                            id="container-lihat-data-{{ $berkas->get('berkas_key') }}">
                                            <button type="button" class="btn btn-secondary btn-sm d-block w-100"
                                                onclick="lihatBerkas('{{ Storage::url($berkas->get('berkas_filepath')) }}')">
                                                <i class="isax isax-search-normal me-2"></i> Lihat Data</button>
                                        </div>
                                        <div class="col-lg-2 d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary btn-sm d-block w-100"
                                                onclick="uploadBerkas('{{ $berkas->get('berkas_key') }}','{{ encrypt($berkas->get('id')) }}', '{{ $permohonan ? encrypt($permohonan->id) : '' }}', 'container-lihat-data-{{ $berkas->get('berkas_key') }}')">
                                                <i class="isax isax-document-upload me-2"></i>Upload Berkas</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        {{-- CASE REVISI --}}
                    @elseif($permohonan->status == 3)
                        @foreach ($berkas_persyaratan as $berkas)
                            @if ($berkas->get('is_active'))
                                <div class="form-group mb-3">
                                    <div class="row justify-content-between">
                                        <div class="col-auto"><label class="col-form-label fw-bold"
                                                for="{{ $berkas->get('berkas_key') }}">{{ $berkas->get('nama') }}{{ $berkas->get('is_required') ? '*' : '' }}</label>
                                        </div>
                                        <div class="col-auto text-primary d-flex align-items-center">
                                            <p class="d-inline mb-0" style="font-size:0.8rem;">
                                                Format Dokumen:
                                                <span class="me-3 fw-bold">{{ $berkas->get('nama_format') }}</span>
                                            </p>
                                            <p class="d-inline mb-0" style="font-size:0.8rem;">
                                                Ukuran Maksimal:
                                                <span class="fw-bold">{{ $berkas->get('batas_ukuran') }} KB</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-auto flex-grow-1">
                                            @if ($berkas->get('status') == 'Revisi')
                                                <input type="file" accept="{{ $berkas->get('tipe_format') }}"
                                                    class="form-control upload-berkas"
                                                    name="berkas[{{ $berkas->get('berkas_key') }}]"
                                                    id="{{ $berkas->get('berkas_key') }}"
                                                    data-max-size="{{ $berkas->get('batas_ukuran') }}">
                                            @else
                                                <input type="text" class="form-control"
                                                    value="{{ $berkas->get('berkas_filename') }}" disabled>
                                            @endif
                                        </div>
                                        <div class="col-lg-2 d-flex justify-content-end {{ !$berkas->get('berkas_filepath') ? 'd-none' : null }}"
                                            id="container-lihat-data-{{ $berkas->get('berkas_key') }}">
                                            <button type="button" class="btn btn-secondary btn-sm d-block w-100"
                                                onclick="lihatBerkas('{{ Storage::url($berkas->get('berkas_filepath')) }}')">
                                                <i class="isax isax-search-normal me-2"></i> Lihat Data</button>
                                        </div>
                                        @if ($berkas->get('status') == 'Revisi')
                                            <div class="col-lg-2 d-flex justify-content-end">
                                                <button type="button" class="btn btn-primary btn-sm d-block w-100"
                                                    onclick="uploadBerkas('{{ $berkas->get('berkas_key') }}','{{ encrypt($berkas->get('id')) }}', '{{ $permohonan ? encrypt($permohonan->id) : '' }}', 'container-lihat-data-{{ $berkas->get('berkas_key') }}')">
                                                    <i class="isax isax-document-upload me-2"></i>Upload Berkas</button>
                                            </div>
                                        @endif
                                        <div class="col-lg-2 d-flex align-items-center justify-content-center">
                                            {!! $berkas->get('berkas_badge_status') !!}
                                        </div>
                                    </div>
                                    @if ($berkas->get('status') == 'Revisi')
                                        <div class="col-12">
                                            <p class="fw-bold text-danger mb-0 mt-2">Catatan revisi :
                                                {{ $berkas->get('catatan_revisi') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                        {{-- CASE SEDANG VERIFIKASI ATAU VERIFIKASI ULANG --}}
                    @else
                        @foreach ($berkas_persyaratan as $berkas)
                            @if ($berkas->get('is_active'))
                                <div class="form-group mb-3">
                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <label
                                                class="col-form-label fw-bold">{{ $berkas->get('nama') }}{{ $berkas->get('is_required') ? '*' : '' }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control"
                                                value="{{ $berkas->get('berkas_filename') }}" disabled>
                                        </div>
                                        <div class="col-lg-2 d-flex align-items-center justify-content-center">
                                            <a href="{{ Storage::url($berkas->get('berkas_filepath')) }}"
                                                class="btn btn-outline-primary text-decoration-none px-5"
                                                style="font-size: 0.8rem" target="_blank">Lihat</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            @if (!$permohonan || $permohonan?->status == 1 || $permohonan?->status == 3)
                <div class="row justify-content-end mb-5 mt-3">
                    <div class="col-lg-6 d-flex justify-content-end">
                        <button type="submit" class="btn px-5 btn-primary me-1" style="height: 50px"><i
                                class="isax isax-send-1 me-3"></i>Simpan dan Kirim</button>
                    </div>
                </div>
            @endif
        </form>
    </div>
@endsection


@push('scripts')
    <script>
        // check max size ketika class "upload-berkas" diubah
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

    <script>
        function lihatBerkas(url) {
            window.open(url, '_blank');
        }

        function setLinkBerkas(container_lihat_berkas, berkas_url) {
            // delete display none
            $('#' + container_lihat_berkas).removeClass('d-none');
            $('#' + container_lihat_berkas).find('button').attr('onclick', "lihatBerkas('" + berkas_url + "')");
        }

        function uploadBerkas(berkas_key, berkas_persyaratan_id, permohonan_id, container_lihat_berkas) {
            let berkas = $('#' + berkas_key).prop('files')[0];
            let form_data = new FormData();
            form_data.append('berkas', berkas);
            form_data.append('berkas_persyaratan_id', berkas_persyaratan_id);
            form_data.append('permohonan_id', permohonan_id);
            form_data.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: "{{ route('user.pengajuan.upload-berkas') }}",
                type: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon tunggu',
                        html: 'Sedang mengunggah berkas',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                    });
                },
                success: function(response) {
                    if (response.status) {
                        setLinkBerkas(container_lihat_berkas, response.data.berkas_url);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berkas berhasil diunggah',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Berkas gagal diunggah',
                            text: response.message,
                        });
                    }
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Berkas gagal diunggah',
                    });
                }
            });
        }
    </script>
@endpush
