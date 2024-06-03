<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Chronos;
use App\Models\Coureurs;
use App\Models\Equipes;
use App\Models\Etape_assignments;
use App\Models\Etapes;
use App\Models\Points;
// use App\Models\Chronos;
// use Carbon\Carbon;
use illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Str;
// use Dompdf\Dompdf;
// use Dompdf\Options;
// use Illuminate\Support\Facades\Validator;

class MyController extends Controller
{

    public function login()
    {
        return view('pages/login');
    }

    public function loginUtil()
    {
        return view('pages/loginUtil');
    }

    public function logAdmin(Request $request)
    {
        $user = Admins::where('username', '=', $request->username)->first();
        // echo $user;
        if ($user) {
            if ($request->pswd == $user->pswd) {
                # code...
                $request->session()->put('loginId', $user->idadmin);
                Session::put('loginId', $user->idadmins);
                return view('pages/admin');
            } else {
                // echo "diso mdp";
                return back()->with('fail', 'Mot de passe incorrect');
            }
        } else {
            return back()->with('fail', 'Cette user n`est pas encore enregistrer');
        }
    }

    public function logUtil(Request $request)
    {
        $request->validate([
            // 'email' => 'required',
            'password' => 'required'
        ]);
        $user = Equipes::where('username', '=', $request->username)->first();
        if ($user) {
            if ($request->password == $user->password) {
                # code...
                Session::put('loginId', $user->id);
                return view('pages/equipe');
            } else {
                return back()->with('fail', 'Mot de passe incorrect');
            }
        } else {
            return back()->with('fail', 'Cette Email n`est pas encore enregistrer');
        }
    }


    public function admin()
    {
        $id = Session::get('loginId');
        if ($id) {
            // $data = [
            //     'detail' => DetailDevis::all()
            // ];
            return view('pages/admin');
        } else {
            return redirect('/login');
        }
    }

    public function logout()
    {
        Session::forget('loginId');
        return redirect('/login');
    }

    function insert(Request $request)
    {
        // echo $request['id_vehicule'];
        if (!empty($request)) {
            $modelName = 'App\Models\\' . $request['table'];
            $instance = app()->make($modelName);
            echo $instance;
            foreach ($instance->getFillable() as $fillable) {
                $instance[$fillable] = $request[$fillable];
            }
            $instance->save();
            return back()->with('success', 'insert effectuer');
        } else {
            return back()->with('fail', 'input invalide');
        }
    }

    // admin
    public function listeEtapes()
    {
        $data = [
            'etapes' => Etapes::all()
        ];
        return view('pages/listeEtapes', compact('data'));
    }

    // equipe
    public function listeEtape()
    {
        $data = [
            'etapes' => Etapes::all()
        ];
        return view('pages/listeEtape', compact('data'));
    }

    public function chrono()
    {

        // $etapeAssignments = Etapes::with('coureurs')->get();

        // Debugging
        // dd($etapeAssignments);


        $data = [
            'chrono' => Chronos::all(),
            'etape' => Etapes::all(),
            'coureur' => Coureurs::all()
        ];
        return view('pages/chrono', compact('data'));
    }

    public function point()
    {
        $data = [
            'point' => Points::all()
        ];
        return view('pages/point', compact('data'));
    }

    public function listeCoureur()
    {
        $data = [
            'coureur' => Coureurs::all()
        ];
        return view('pages/listeCoureur', compact('data'));
    }

    public function etape_assignment()
    {
        $data = [
            'etape_assignments' => Etape_assignments::all(),
            'etape' => Etapes::all(),
            'coureur' => Coureurs::all()
        ];
        return view('pages.etape_assignment', compact('data'));
    }

    public function resetDatabase(Request $request)
    {
        // Vérifiez si l'utilisateur est bien un admin
        // if (!auth()->user()->isAdmin()) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        // Exécution des requêtes de réinitialisation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('penalties')->truncate();
        // DB::table('results')->truncate();
        DB::table('etape_assignments')->truncate();
        DB::table('etapes')->truncate();
        DB::table('coureur_categories')->truncate();
        DB::table('coureurs')->truncate();
        DB::table('categories')->truncate();
        DB::table('equipes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return response()->json(['success' => 'Database reset successfully']);
    }

    public function showChrono()
    {
        $etapeAssignments = Etapes::with('coureurs')->get();
        return view('pages.chrono', ['etape_assignments' => $etapeAssignments]);
    }

    public function showAffecterTempsForm($idetape, $idcoureur)
    {
        $etape = Etapes::find($idetape);
        $coureur = Coureurs::find($idcoureur);

        if (!$etape || !$coureur) {
            return redirect()->back()->with('fail', 'Étape ou coureur non trouvé.');
        }

        return view('pages.affecter-temps', compact('etape', 'coureur'));
    }

    public function affecterTemps(Request $request)
    {
        $request->validate([
            'idetape' => 'required|exists:etapes,id',
            'idcoureur' => 'required|exists:coureurs,id',
            'heure_depart' => 'required|date_format:H:i:s',
            'heure_arrive' => 'required|date_format:H:i:s',
        ]);

        // Debugging output to check received data
        Log::info('Request Data:', $request->all());

        // Mise à jour ou création de l'enregistrement Chronos
        $temps = Chronos::updateOrCreate(
            [
                'idetape' => $request->idetape,
                'idcoureur' => $request->idcoureur
            ],
            [
                'heure_depart' => $request->heure_depart,
                'heure_arrive' => $request->heure_arrive
            ]
        );

        // Vérifier que l'opération a réussi
        if ($temps) {
            return redirect()->back()->with('success', 'Temps affecté avec succès.');
        } else {
            return redirect()->back()->with('fail', 'Échec de l\'affectation du temps.');
        }
    }

    public function importPoint(Request $request)
    {
        // Vérifier si un fichier CSV a été envoyé
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');

            // Vérifier si le fichier est un CSV
            if ($file->getClientOriginalExtension() === 'csv') {
                // Récupérer le contenu du fichier CSV
                $contents = file_get_contents($file->path());
                $lines = explode("\n", $contents);

                // Parcourir chaque ligne du CSV à partir de la deuxième ligne
                for ($i = 1; $i < count($lines); $i++) {
                    $line = $lines[$i];
                    $data = explode(';', $line);

                    // Vérifier si la ligne est valide
                    if (count($data) === 2) {
                        // Créer un nouvel enregistrement dans la table Points
                        $point = new Points();
                        $point->classement = $data[0];
                        $point->points = $data[1];
                        $point->save();
                    }
                }
                return redirect()->back()->with('success', 'Importation CSV réussie.');
            } else {
                return redirect()->back()->with('error', 'Le fichier doit être au format CSV.');
            }
        }

        return redirect()->back()->with('error', 'Aucun fichier CSV n\'a été envoyé.');
    }

    public function importEtape(Request $request)
    {
        // Vérifier si un fichier CSV a été envoyé
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');

            // Vérifier si le fichier est un CSV
            if ($file->getClientOriginalExtension() === 'csv') {
                // Récupérer le contenu du fichier CSV
                $contents = file_get_contents($file->path());
                $lines = explode("\n", $contents);

                // Parcourir chaque ligne du CSV à partir de la deuxième ligne
                for ($i = 1; $i < count($lines); $i++) {
                    $line = $lines[$i];
                    $data = explode(';', $line);

                    // Vérifier si la ligne est valide
                    if (count($data) === 2) {

                        $longueur = (float)str_replace(',', '.', $data[1]);
                        // Créer un nouvel enregistrement dans la table Points
                        $etape = new Points();
                        $etape->name = $data[0];
                        $etape->longueur = $longueur;
                        $etape->nbcoureur = $data[2];
                        $etape->datedepart = $data[3];
                        $etape->heure_depart = $data[4];
                        $etape->save();
                    }
                }
                return redirect()->back()->with('success', 'Importation CSV réussie.');
            } else {
                return redirect()->back()->with('error', 'Le fichier doit être au format CSV.');
            }
        }

        return redirect()->back()->with('error', 'Aucun fichier CSV n\'a été envoyé.');
    }
}
