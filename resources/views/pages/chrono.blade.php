@extends('pages.layouts.app')
@section('chrono')
    active
@endsection
@section('contenue')
    <div class="card mb-4">
        <h5 class="card-header">Temps</h5>
         <div class="table-responsive text-nowrap">
            <table class="table">
        <thead class="table-light">
            <tr>
                <th>Etape</th>
                <th>Coureur</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach ($etape_assignments as $ech)
                <tr>
                    <td>{{ $ech->name }}</td>
                    <td>
                        @foreach ($ech->coureurs as $coureur)
                            {{ $coureur->name }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($ech->coureurs as $coureur)
                            <a href="{{ route('showAffecterTempsForm', ['idetape' => $ech->id, 'idcoureur' => $coureur->id]) }}">Affecter Temps</a><br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
@endsection
