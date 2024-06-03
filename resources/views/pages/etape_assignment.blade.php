@extends('pages.layouts.userapp')
@section('echeance')
    active
@endsection
@section('contenue')
    <div class="card mb-4">
        <h5 class="card-header">Affectation coureur</h5>
        <div class="table-responsive text-nowrap">
            <form action="/insert" method="get">
                @csrf
                <input type="hidden" name="table" value="etape_assignments">
                <p>Etape : <select name="idetape" id="defaultSelect" class="form-select">
                        @foreach ($data['etape'] as $veh)
                            <option value="{{ $veh->id }}">{{ $veh->name }}</option>
                        @endforeach
                    </select>
                </p>
                <p>Coureur : <select name="idcoureur" id="defaultSelect" class="form-select">
                        @foreach ($data['coureur'] as $cour)
                            <option value="{{ $cour->id }}">{{ $cour->name }}</option>
                        @endforeach
                    </select>
                </p>
                <p><button type="submit" class="btn btn-dark">Inserer</button></p>
            </form>
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
            @foreach ($data['etape_assignments'] as $ech)
                <tr>
                    <td>
                        @if ($ech->etape)
                            {{ $ech->etape->name }}
                        @endif
                    </td>
                    <td>
                        @if ($ech->coureur)
                            {{ $ech->coureur->name }}
                        @endif
                    </td>
                    {{-- <td><a href="/delete?table=echeances&id={{ $ech->id }}">Supprimer</a></td>
                    <td><a href="/update_echeance?table=echeances&id={{ $ech->id }}">Mise à jour</a></td> --}}
                </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>
@endsection
