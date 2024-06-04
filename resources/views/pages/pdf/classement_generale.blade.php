<!DOCTYPE html>
<html>
<head>
    <title>Classement Général</title>
    <style>
        body {
            font-family: DejaVu Sans;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Classement Général</h1>

    @if($classement_generale->isEmpty())
        <p>Aucun classement trouvé pour cette étape.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Etape</th>
                    <th>Nom Coureur</th>
                    <th>Rang</th>
                    <th>Temps</th>
                    <th>Equipe</th>
                    <th>Points</th>
                    <!-- Ajouter d'autres colonnes nécessaires -->
                </tr>
            </thead>
            <tbody>
                @foreach($classement_generale as $classement)
                    <tr>
                        <td>{{ $classement->etape }}</td>
                        <td>{{ $classement->coureur_nom }}</td>
                        <td>{{ $classement->rang }}</td>
                        <td>{{ $classement->temps_parcours }}</td>
                        <td>{{ $classement->equipe_nom }}</td>
                        <td>{{ $classement->points }}</td>
                        <!-- Ajouter d'autres colonnes nécessaires -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
