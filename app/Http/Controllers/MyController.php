<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Coureurs;
use App\Models\Equipes;
use App\Models\Etape_assignments;
use App\Models\Etapes;
// use App\Models\Chronos;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
                if (Hash::check($request->password, $user->password)) {

                    Session::put('loginId', $user->id);
                    // $data = [
                    //     'calendrier' => calendrier::all(),
                    //     'sport' => sport::all(),
                    //     'lieu' => lieu::all()
                    // ];
                    return view('pages/equipe');
                }
            } else {
                return back()->with('fail', 'Mot de passe incorrect');
            }
        } else {
            return back()->with('fail', 'Cette Email n`est pas encore enregistrer');
        }
    }

    // Méthode pour gérer la connexion via le formulaire
    // public function logUtil(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);

    //     $user = Equipes::where('username', $request->username)->first();

    //     if ($user) {
    //         if (Hash::check($request->password, $user->password)) {
    //             // Set user session
    //             Session::put('loginId', $user->id);
    //             return view('pages/equipe');
    //         } else {
    //             return back()->with('fail', 'Mot de passe incorrect');
    //         }
    //     } else {
    //         return back()->with('fail', 'Ce nom d’utilisateur n’est pas encore enregistré');
    //     }
    // }

    // Méthode pour gérer l'accès via le token unique
    public function accessViaToken($access_token)
    {
        $team = Equipes::where('access_token', $access_token)->first();

        if ($team) {
            // Enregistrez l'équipe dans la session
            Session::put('loginId', $team->id);
            return view('pages/equipe');
        } else {
            return redirect('/')->with('fail', 'Invalid access token.');
        }
    }

    public function dashboard()
    {
        $teamId = Session::get('loginId');
        $team = Equipes::find($teamId);

        if ($team) {
            return view('pages.equipe', compact('team'));
        } else {
            return redirect('/logiUtil')->with('fail', 'Please login first.');
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
            'etape_assignment' => Etape_assignments::all(),
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

    // public function stockerChronos(Request $request)
    // {
    //     $request->validate([
    //         'idcoureur' => 'required',
    //         'idetape' => 'required',
    //         'heure_depart' => 'required|date_format:H:i:s', // Valide le format hh:mm:ss
    //         'heure_arrivee' => 'required|date_format:H:i:s', // Valide le format hh:mm:ss
    //     ]);

    //     $coureurID = $request->input('idcoureur');
    //     $etapeID = $request->input('idetape');
    //     $heureDepart = $request->input('heure_depart');
    //     $heureArrivee = $request->input('heure_arrivee');

    //     // Enregistrer le chrono dans la base de données
    //     $chrono = new Chronos();
    //     $chrono->coureur_id = $coureurID;
    //     $chrono->etape_id = $etapeID;
    //     $chrono->heure_depart = $heureDepart;
    //     $chrono->heure_arrivee = $heureArrivee;
    //     $chrono->save();

    //     // Récupérer les informations pour la session
    //     $etape = Etapes::find($etapeID);
    //     $coureur = Coureurs::find($coureurID);
    //     $equipe = $coureur->equipe; // Assure-toi que le modèle Coureur a une relation définie avec Equipe

    //     // Stocker les informations dans la session
    //     session([
    //         'etape' => $etape,
    //         'coureur' => $coureur,
    //         'equipe' => $equipe,
    //         'heure_depart' => $heureDepart,
    //         'heure_arrivee' => $heureArrivee,
    //     ]);

    //     // Redirection avec un message de succès
    //     return redirect()->back()->with('success', 'Chrono stocké avec succès.');
    // }
}
