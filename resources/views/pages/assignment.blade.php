@extends('pages.layouts.userapp')
@section('trajet')
    active
@endsection
@section('contenue')
    <div class="card mb-4">
        <h5 class="card-header">Assignement</h5>
        <div class="table-responsive text-nowrap">
            <form action="/insert" method="post">
                @csrf
                <div class="card mb-4">
                <input type="hidden" name="table" value="etape_assignments">
                <p>Coureur: <select name="idcoureur" id="defaultSelect" class="form-select">
                        @foreach ($data['coureurs'] as $arr)
                            <option value="{{ $arr->id }}">{{ $arr->nom }}</option>
                        @endforeach
                    </select>
                </p>
                <p>Etape : <select name="idetape" id="defaultSelect" class="form-select">
                        @foreach ($data['etapes'] as $veh)
                            <option value="{{ $veh->id }}">{{ $veh->nom }}</option>
                        @endforeach
                    </select>
                </p>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Etape</th>
                        <th>Coureur</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($data['etape_assignments'] as $dep)
                        <tr>
                            <td>{{ $dep->heure}}</td>
                            <td>{{ $dep->date}}</td>
                            {{-- <td><a href="/delete?table=departs&id={{ $dep->id }}">Supprimer</a></td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
