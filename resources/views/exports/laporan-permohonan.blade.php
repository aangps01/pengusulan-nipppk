<table>
    <tr></tr>
    <tr>
        <td colspan="5" style="font-weight: bold; text-align:center;">Laporan Data Permohonan</td>
    </tr>
    <tr></tr>
    <thead>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">NIK</th>
            <th style="font-weight: bold;">Tanggal Pengusulan</th>
            <th style="font-weight: bold;">Tanggal Persetujuan</th>
            <th style="font-weight: bold;">Status</th>
        </tr>
    </thead>
    <tBody>
        @foreach ($permohonans as $permohonan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $permohonan->user->nik }}</td>
                <td>{{ $permohonan->created_at->format('d-m-Y') }}</td>
                <td>{{ $permohonan->tanggal_validasi?->format('d-m-Y') }}</td>
                <td>{{ $permohonan->status_name }}</td>
            </tr>
        @endforeach
    </tBody>
</table>
