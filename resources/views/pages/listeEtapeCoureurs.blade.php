@extends('pages.layouts.userapp')
@section('listeEtape')
active
@endsection
@section('contenue')
<div class="card">
    {{-- <h5 class="card-header">Etape {{ $data['coureurs']->etape }}</h5> --}}
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Temps</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($data['coureurs'] as $travaux)
                <tr>
                    <td>{{ $travaux->etape }}</td>
                    <td>{{ $travaux->coureur_nom }}</td>
                    <td>{{ $travaux->temps_parcours }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
