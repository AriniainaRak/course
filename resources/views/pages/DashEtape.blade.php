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
                    <th>Etape</th>
                    <th>Nom coureur</th>
                    <th>Equipe</th>
                    <th>rang</th>
                    <th>Point</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($data['classement_generale'] as $travaux)
                <tr>
                    <td>{{ $travaux->etape }}</td>
                    <td>{{ $travaux->coureur_nom }}</td>
                    <td>{{ $travaux->equipe_nom }}</td>
                    <td>{{ $travaux->rang }}</td>
                    <td>{{ $travaux->points }}</td>
                    {{-- <td><a href="{{ route('detaillistedevis', ['idTypeMaison' => $travaux->idtype_maison]) }}">Detail</a> --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
