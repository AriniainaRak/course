@extends('pages.layouts.app')
<!DOCTYPE html>
<html>
<head>
    <title>Insert Data</title>
</head>
<body>
    <h1>Insert Data</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('team.storeData') }}" method="POST">
        @csrf
        <!-- Ajoutez ici les champs de votre formulaire -->
        <label for="data">Data:</label>
        <input type="text" id="data" name="data" required>

        <button type="submit">Submit</button>
    </form>

    <form action="{{ route('team.logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
