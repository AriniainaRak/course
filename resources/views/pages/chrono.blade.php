@extends('pages.layouts.app')
@section('temps')
    active
@endsection
@section('contenue')
    <div class="card mb-4">
        <h5 class="card-header">Resultat</h5>
        <div class="table-responsive text-nowrap">
            <form action="/insert" method="post">
                @csrf
                <div class="card mb-4">
                    <input type="hidden" name="table" value="etape_assignments">
                    <p>Coureur: <select name="idcoureur" id="defaultSelect" class="form-select">
                            @foreach ($data['coureurs'] as $arr)
                                <option value="{{ $arr->id }}">{{ $arr->name }}</option>
                            @endforeach
                        </select>
                    </p>
                    <p>Etape : <select name="idetape" id="defaultSelect" class="form-select">
                            @foreach ($data['etapes'] as $veh)
                                <option value="{{ $veh->id }}">{{ $veh->name }}</option>
                            @endforeach
                        </select>
                    </p>
                    <p>Depart : <input type="time" type="datetime-local" name="depart" id=""></p>
                    <p>Arriver : <input type="time" type="datetime-local" name="arriver" id=""></p>
            </form>
        </div>
    </div>
@endsection
