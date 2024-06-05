@extends('pages.layouts.userapp')
@section('contenue')
    <div class="card">
        {{-- <p>Bienvenue a l'equipe {{ $data['equipe']->name }}</p> --}}
        <h5 class="card-header">Bienvenue a l'equipe {{ $data['equipe']->name }}</h5>
        <h5 class="card-header">{{ $data['equipe']->etape }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Etape</th>
                        <th>coureur</th>
                        <th>Temps</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($data['etape'] as $travaux)
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
