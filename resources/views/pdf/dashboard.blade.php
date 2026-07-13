@extends('layouts.pdf')

@section('title', 'Ringkasan Dashboard Riset Dosen FIF')
@section('subtitle', 'Ringkasan jumlah dosen, publikasi, bidang AI, dan kolaborasi')

@section('content')
    <div class="section-title">Ringkasan</div>
    <table>
        <thead>
            <tr><th>Metrik</th><th>Nilai</th></tr>
        </thead>
        <tbody>
            <tr><td>Total Dosen</td><td>{{ $totalLecturers }}</td></tr>
            <tr><td>Total Publikasi</td><td>{{ $totalPublications }}</td></tr>
            <tr><td>Jumlah Bidang AI</td><td>{{ $totalAiFields }}</td></tr>
            <tr><td>Total Koneksi Kolaborasi</td><td>{{ $totalCollaborations }}</td></tr>
        </tbody>
    </table>

    <div class="section-title">Kelompok Keahlian</div>
    <table>
        <thead>
            <tr><th>Kelompok Keahlian</th><th>Jumlah Dosen</th></tr>
        </thead>
        <tbody>
            @foreach ($researchGroups as $group => $count)
                <tr><td>{{ $group }}</td><td>{{ $count }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Publikasi Terbaru</div>
    <table>
        <thead>
            <tr><th>Dosen</th><th>Judul Publikasi</th><th>Tahun</th></tr>
        </thead>
        <tbody>
            @forelse ($recentPublications as $pub)
                <tr><td>{{ $pub->lecturer->name }}</td><td>{{ $pub->title }}</td><td>{{ $pub->year }}</td></tr>
            @empty
                <tr><td colspan="3">Belum ada data publikasi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Kolaborasi Teraktif</div>
    <table>
        <thead>
            <tr><th>Dosen 1</th><th>Dosen 2</th><th>Jumlah Publikasi Bersama</th></tr>
        </thead>
        <tbody>
            @forelse ($topCollaborations as $collab)
                <tr><td>{{ $collab->lecturerOne->name }}</td><td>{{ $collab->lecturerTwo->name }}</td><td>{{ $collab->collaboration_count }}</td></tr>
            @empty
                <tr><td colspan="3">Belum ada data kolaborasi.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
