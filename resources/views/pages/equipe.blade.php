@extends('pages.layouts.userapp')
@section('content')
    <h1>Bienvenue, {{ $equipe->name }}</h1>
    <p>Nom d'utilisateur: {{ $equipe->username }}</p>
    <p>ID de l'équipe: {{ $equipe->id }}</p>
    <!-- Ajoutez d'autres informations spécifiques à l'équipe ici -->
@endsection
