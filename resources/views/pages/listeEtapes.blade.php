@extends('pages.layouts.app')
@section('listeEtape')
active
@endsection
@section('contenue')
<div class="card">
    <h5 class="card-header">liste étape</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>nom</th>
                    <th>Longueur</th>
                    <th>Nombre de coureur par equipe</th>
                    <th>rang</th>
                    <th>Voir classement</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($data['etapes'] as $travaux)
                <tr>
                    <td>{{ $travaux->name }}</td>
                    <td>{{ $travaux->longueur }}</td>
                    <td>{{ $travaux->coureurs_per_equipe }}</td>
                    <td>{{ $travaux->rang }}</td>
                    <td><a href="/DashEtape?idetape={{ $travaux->id }}">Voir classement</a></td>
                    <td><a href="/etapepdf?idetape={{ $travaux->id }}">PDF</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
