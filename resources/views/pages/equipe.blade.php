@extends('pages.layouts.userapp')
@section('contenue')
    <div class="card">
        <p>Bienvenue a l'equipe {{ $data['equipe']->name }}</p>
    </div>
@endsection
