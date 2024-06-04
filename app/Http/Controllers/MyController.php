<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Chronos;
use App\Models\Coureurs;
use App\Models\Equipes;
use App\Models\Etape_assignments;
use App\Models\Etapes;
use App\Models\Points;
use App\Models\Genres;
// use App\Models\Chronos;
// use Carbon\Carbon;
use illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
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

    // public function logUtil(Request $request)
    // {
    //     $request->validate([
    //         // 'email' => 'required',
    //         'password' => 'required'
    //     ]);
    //     $user = Equipes::where('username', '=', $request->username)->first();
    //     if ($user) {
    //         if ($request->password == $user->password) {
    //             # code...
    //             Session::put('loginId', $user->id);
    //             return view('pages/equipe');
    //         } else {
    //             return back()->with('fail', 'Mot de passe incorrect');
    //         }
    //     } else {
    //         return back()->with('fail', 'Cette Email n`est pas encore enregistrer');
    //     }
    // }

    public function logUtil(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $equipe = Equipes::where('username', '=', $request->username)->first();
        if ($equipe) {
            if ($request->password == $equipe->password) {
                // Mettre l'ID de l'équipe dans la session pour identifier l'équipe connectée
                Session::put('loginId', $equipe->id);
                // Rediriger vers la page spécifique de l'équipe
                return view('pages.equipe', ['id' => $equipe->id]);
            } else {
                // Mot de passe incorrect
                return back()->with('fail', 'Mot de passe incorrect');
            }
        } else {
            // Nom d'utilisateur non trouvé
            return back()->with('fail', 'Nom d\'utilisateur non trouvé');
        }
    }

    // public function logUtil(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);

    //     $equipe = Equipes::where('username', $request->username)->first();

    //     if ($equipe) {
    //         if ($request->password == $equipe->password) {
    //             Session::put('loginId', $equipe->id);
    //             return redirect()->route('equipe.page', ['id' => $equipe->id]);
    //         } else {
    //             return back()->with('fail', 'Mot de passe incorrect');
    //         }
    //     } else {
    //         return back()->with('fail', 'Nom d\'utilisateur non trouvé');
    //     }
    // }


    // public function show($id)
    // {
    //     $equipe = Equipes::find($id);
    //     if ($equipe) {
    //         return view('pages.equipe', ['equipe' => $equipe]);
    //     } else {
    //         return redirect()->back()->with('fail', 'Équipe non trouvée');
    //     }
    // }

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

    public function import()
    {
        $data = [
            'coureur' => Coureurs::all(),
            'etape' => Etapes::all(),
            'genre' => Genres::all()
        ];
        return view('pages/import', compact('data'));
    }

    public function importresult()
    {
        $data = [
            'coureur' => Coureurs::all(),
            'etape' => Etapes::all(),
            'genre' => Genres::all()
        ];
        return view('pages/importresult', compact('data'));
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
                    $data = str_getcsv($line, ';');

                    // Vérifier si la ligne est valide
                    if (count($data) >= 6) {
                        try {
                            $longueur = (float)str_replace(',', '.', $data[1]);
                            $dateDepart = \DateTime::createFromFormat('d/m/Y', $data[4]);
                            $heureDepart = \DateTime::createFromFormat('H:i:s', $data[5]);

                            // Créer un nouvel enregistrement dans la table Etape
                            $etape = new Etapes();
                            $etape->name = $data[0];
                            $etape->longueur = $longueur;
                            $etape->coureurs_per_equipe = (int)$data[2];
                            $etape->rang = (int)$data[3];
                            $etape->datedepart = $dateDepart ? $dateDepart->format('Y-m-d') : null;
                            $etape->heure_depart = $heureDepart ? $heureDepart->format('H:i:s') : null;

                            // Journaliser les données avant de les enregistrer
                            Log::info('Insertion des données : ', [
                                'name' => $etape->name,
                                'longueur' => $etape->longueur,
                                'coureurs_per_equipe' => $etape->coureurs_per_equipe,
                                'rang' => $etape->rang,
                                'datedepart' => $etape->datedepart,
                                'heure_depart' => $etape->heure_depart,
                            ]);

                            $etape->save();
                        } catch (\Exception $e) {
                            Log::error('Erreur lors de l\'importation de la ligne : ' . $line . ' - ' . $e->getMessage());
                        }
                    } else {
                        Log::warning('Ligne invalide : ' . $line);
                    }
                }

                return redirect()->back()->with('success', 'Importation CSV réussie.');
            } else {
                return redirect()->back()->with('error', 'Le fichier doit être au format CSV.');
            }
        }

        return redirect()->back()->with('error', 'Aucun fichier CSV n\'a été envoyé.');
    }

    // mande fa 2 premier ligne iany no insereny de tsy insereny daoly
    // public function importResultat(Request $request)
    // {
    //     // Vérifier si un fichier CSV a été envoyé
    //     if ($request->hasFile('csv_file2')) {
    //         $file = $request->file('csv_file2');

    //         // Vérifier si le fichier est un CSV
    //         if ($file->getClientOriginalExtension() === 'csv') {
    //             // Ouvrir le fichier en mode lecture
    //             $handle = fopen($file->path(), 'r');

    //             // Ignorer la première ligne (en-tête)
    //             fgetcsv($handle, 0, ';');

    //             // Lire les données restantes
    //             while (($data = fgetcsv($handle, 0, ';')) !== false) {
    //                 if (count($data) < 7) {
    //                     Log::warning('Ligne invalide : ' . implode(';', $data));
    //                     continue;
    //                 }

    //                 // Vérifier ou créer l'équipe avec des valeurs par défaut pour username et password
    //                 $equipe = Equipes::firstOrCreate(
    //                     ['name' => $data[5]],
    //                     [
    //                         'username' => Str::slug($data[5]) . '_user', // Nom d'utilisateur par défaut
    //                         'password' => bcrypt('default_password') // Mot de passe par défaut chiffré
    //                     ]
    //                 );

    //                 // Vérifier ou créer le genre
    //                 $genre = Genres::firstOrCreate(['name' => $data[3]]);

    //                 // Vérifier ou créer l'étape
    //                 $etape = Etapes::firstOrCreate(
    //                     ['rang' => $data[0]],
    //                     [
    //                         'name' => 'Etape ' . $data[0], // Nom par défaut
    //                         'longueur' => 0, // Valeur par défaut
    //                         'coureurs_per_equipe' => 0, // Valeur par défaut
    //                         'datedepart' => null, // Valeur par défaut
    //                         'heure_depart' => null // Valeur par défaut
    //                     ]
    //                 );

    //                 // Créer ou mettre à jour le coureur
    //                 $coureur = Coureurs::updateOrCreate(
    //                     ['dossard_number' => $data[1]],
    //                     [
    //                         'name' => $data[2],
    //                         'idgenre' => $genre->id,
    //                         'birth_date' => \DateTime::createFromFormat('d/m/Y', $data[4])->format('Y-m-d'),
    //                         'idequipe' => $equipe->id
    //                     ]
    //                 );

    //                 // Insérer le temps d'arrivée dans la table Chronos
    //                 Chronos::create([
    //                     'idetape' => $etape->id,
    //                     'idcoureur' => $coureur->id,
    //                     'heure_arrivee' => \DateTime::createFromFormat('d/m/Y H:i:s', $data[6])->format('Y-m-d H:i:s')
    //                 ]);

    //                 // Log the inserted data
    //                 Log::info('Données insérées : ', [
    //                     'etape_rang' => $data[0],
    //                     'dossard_number' => $data[1],
    //                     'nom' => $data[2],
    //                     'genre' => $data[3],
    //                     'birth_date' => $data[4],
    //                     'equipe' => $data[5],
    //                     'arrival_time' => $data[6],
    //                 ]);
    //             }

    //             // Fermer le fichier
    //             fclose($handle);

    //             return redirect()->back()->with('success', 'Importation du deuxième fichier CSV réussie.');
    //         } else {
    //             return redirect()->back()->with('error', 'Le fichier 2 doit être au format CSV.');
    //         }
    //     }

    //     return redirect()->back()->with('error', 'Aucun fichier 2 CSV n\'a été envoyé.');
    // }

    public function importResultat(Request $request)
    {
        // Vérifier si un fichier CSV a été envoyé
        if ($request->hasFile('csv_file2')) {
            $filePath = $request->file('csv_file2')->path();

            // Lire le contenu du fichier CSV
            $contents = file_get_contents($filePath);
            $lines = explode("\n", $contents);

            // Parcourir chaque ligne du CSV à partir de la deuxième ligne
            for ($i = 1; $i < count($lines); $i++) {
                $line = $lines[$i];
                $data = explode(';', $line);

                // Vérifier si la ligne est valide pour chaque table
                if (count($data) === 7) {
                    // Vérifier et créer l'équipe si elle n'existe pas
                    $equipe = Equipes::firstOrCreate(['name' => $data[5]]);

                    // Vérifier et créer le genre si celui-ci n'existe pas
                    $genre = Genres::firstOrCreate(['name' => $data[3]]);

                    // Vérifier si le rang de l'étape existe dans la table Etapes
                    $etape = Etapes::firstOrCreate(['rang' => $data[0]]);
                    if ($etape) {
                        // Créer un nouvel enregistrement dans la table Coureurs
                        $coureur = new Coureurs();
                        $coureur->name = $data[2];
                        $coureur->dossard_number = $data[1];
                        $coureur->birth_date = $data[4];
                        $coureur->idgender = $genre->id;
                        $coureur->idequipe = $equipe->id;
                        $coureur->idetape = $etape->id;
                        $coureur->heure_arrive = $data[6];
                        $coureur->save();
                    }
                }
            }

            return redirect()->back()->with('success', 'Importation du fichier CSV réussie.');
        }

        return redirect()->back()->with('error', 'Aucun fichier CSV n\'a été envoyé.');
    }
}
