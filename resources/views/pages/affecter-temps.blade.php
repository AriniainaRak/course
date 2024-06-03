@extends('pages.layouts.app')
@section('chrono')
    active
@endsection
@section('contenue')
    <div class="card mb-4">
        <h5 class="card-header">Temps</h5>
         <div class="table-responsive text-nowrap">
            <form action="{{ route('affecterTemps') }}" method="POST">
                @csrf
                <input type="hidden" name="idetape" value="{{ $etape->id }}">
                <input type="hidden" name="idcoureur" value="{{ $coureur->id }}">

                <label for="heure_depart">Heure de départ (hh:mm:ss):</label>
                <input type="text" name="heure_depart" id="heure_depart">

                <label for="heure_arrivee">Heure d'arrivée (hh:mm:ss):</label>
                <input type="text" name="heure_arrive" id="heure_arrive">

                <button type="submit">Enregistrer</button>
            </form>
    </div>
@endsection
