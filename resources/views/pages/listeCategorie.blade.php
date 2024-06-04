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
                    <th>nom categorie</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($data['categorie'] as $travaux)
                <tr>
                    <td>{{ $travaux->name }}</td>
                    <td><a href="/DashCategorie?id={{ $travaux->id }}">Voir classement</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
