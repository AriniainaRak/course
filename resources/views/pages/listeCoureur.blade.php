@extends('pages.layouts.app')
@section('listeCoureur')
active
@endsection
@section('contenue')
<div class="card">
    <h5 class="card-header">liste coureur</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>nom</th>
                    <th>Numéro dossard</th>
                    <th>Genre</th>
                    <th>Date de naissance</th>
                    <th>Equipe</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($data['coureur'] as $travaux)
                <tr>
                    <td>{{ $travaux->name }}</td>
                    <td>{{ $travaux->dossard_number }}</td>
                    <td>{{ $travaux->gender }}</td>
                    <td>{{ $travaux->birth_date }}</td>
                    <td>{{ $travaux->idequipe }}</td>
                    {{-- <td><a href="{{ route('detaillistedevis', ['idTypeMaison' => $travaux->idtype_maison]) }}">Detail</a> --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
