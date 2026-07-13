@extends('layouts.pdf')

@section('title', 'Daftar Profil Dosen')
@section('subtitle', 'Menampilkan ' . $lecturers->count() . ' dosen' . ($hasActiveFilter ? ' (hasil filter aktif)' : ''))

@section('content')
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kode</th>
                <th>NIP</th>
                <th>Program Studi</th>
                <th>Kelompok Keahlian</th>
                <th>Bidang Keahlian</th>
                <th>JFA</th>
                <th>Sitasi</th>
                <th>H-Index</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lecturers as $lecturer)
                <tr>
                    <td>{{ $lecturer->name }}</td>
                    <td>{{ $lecturer->lecturer_code }}</td>
                    <td>{{ $lecturer->code }}</td>
                    <td>{{ $lecturer->study_program }}</td>
                    <td><span class="badge">{{ $lecturer->research_group }}</span></td>
                    <td>{{ $lecturer->field }}</td>
                    <td>{{ $lecturer->academic_rank }}</td>
                    <td>{{ $lecturer->citation_count }}</td>
                    <td>{{ $lecturer->h_index }}</td>
                </tr>
            @empty
                <tr><td colspan="9">Tidak ada dosen yang cocok dengan filter ini.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
