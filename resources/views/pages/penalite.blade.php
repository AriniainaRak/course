@extends('pages.layouts.app')
@section('echeance')
    active
@endsection
@section('contenue')
    <div class="card mb-4">
        <h5 class="card-header">Add Penalty</h5>
        <div class="table-responsive text-nowrap">
            <form action="/insert" method="get">
                @csrf
                <input type="hidden" name="table" value="penalities">
                <p>Etape : <select name="idetape" id="defaultSelect" class="form-select">
                        @foreach ($data['etape'] as $veh)
                            <option value="{{ $veh->id }}">{{ $veh->name }}</option>
                        @endforeach
                    </select>
                </p>
                <p>Equipe : <select name="idequipe" id="defaultSelect" class="form-select">
                        @foreach ($data['equipe'] as $cour)
                            <option value="{{ $cour->id }}">{{ $cour->name }}</option>
                        @endforeach
                    </select>
                </p>
                <p><input type="text" class="form-control" name="penalty" placeholder="hh:mm:ss">Pen</p>
                <p><button type="submit" class="btn btn-dark">Inserer</button></p>
            </form>
        </div>
    </div>
    <div class="card mb-4">
        <h5 class="card-header">Penalty</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Etape</th>
                        <th>Equipe</th>
                        <th>Penalty</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
            @foreach ($data['penalty'] as $ech)
                <tr>
                    <td>
                        @if ($ech->etape)
                            {{ $ech->etape->name }}
                        @endif
                    </td>
                    <td>
                        @if ($ech->equipe)
                            {{ $ech->equipe->name }}
                        @endif
                    </td>
                    <td>{{ $ech->penalty }}</td>
                     <td><a href="/delete?table=penalities&id={{ $ech->id }}">Supprimer</a></td>
                    {{-- <td><a href="/update_echeance?table=echeances&id={{ $ech->id }}">Mise à jour</a></td>  --}}
                </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>
@endsection
