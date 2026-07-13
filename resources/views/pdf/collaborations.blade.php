@extends('layouts.pdf')

@section('title', 'Daftar Kolaborasi Dosen')
@section('subtitle', 'Menampilkan ' . $collaborations->count() . ' pasangan kolaborasi')

@section('content')
    <table>
        <thead>
            <tr>
                <th>Dosen 1</th>
                <th>Kelompok Keahlian 1</th>
                <th>Dosen 2</th>
                <th>Kelompok Keahlian 2</th>
                <th>Publikasi Bersama</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($collaborations as $collaboration)
                <tr>
                    <td>{{ $collaboration->lecturerOne->name }}</td>
                    <td><span class="badge">{{ $collaboration->lecturerOne->research_group }}</span></td>
                    <td>{{ $collaboration->lecturerTwo->name }}</td>
                    <td><span class="badge">{{ $collaboration->lecturerTwo->research_group }}</span></td>
                    <td>{{ $collaboration->collaboration_count }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada data kolaborasi.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
