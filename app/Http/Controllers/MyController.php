<?php

namespace App\Http\Controllers;

use App\Models\Admins;
// use App\Models\Chronos;
use App\Models\Coureurs;
use App\Models\Equipes;
use App\Models\Etape_assignments;
use App\Models\Etapes;
use App\Models\Points;
use App\Models\Genres;
use App\Models\Penalities;
use App\Models\Categories;
// use App\Models\Chronos;
use Carbon\Carbon;
// use illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Spatie\Browsershot\Browsershot;
use Mpdf\Mpdf;

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

    // public function logUtil(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);

    //     $equipe = Equipes::where('username', '=', $request->username)->first();
    //     if ($equipe) {
    //         if ($request->password == $equipe->password) {
    //             // Mettre l'ID de l'équipe dans la session pour identifier l'équipe connectée
    //             Session::put('loginId', $equipe->id);
    //             // Rediriger vers la page spécifique de l'équipe
    //             return view('pages.equipe', ['id' => $equipe->id]);
    //         } else {
    //             // Mot de passe incorrect
    //             return back()->with('fail', 'Mot de passe incorrect');
    //         }
    //     } else {
    //         // Nom d'utilisateur non trouvé
    //         return back()->with('fail', 'Nom d\'utilisateur non trouvé');
    //     }
    // }

    // public function logUtil(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);

    //     $equipe = Equipes::where('username', $request->username)->first();

    //     if ($equipe) {
    //         // dd($request->password, $equipe->password);
    //         Session::put('loginId', $equipe->id);
    //         return view('pages/equipe', ['id' => $equipe->id]);
    //     } else {
    //         return redirect()->back()->with('fail', 'Nom d\'utilisateur non trouvé');
    //     }
    // }

    public function logUtil(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $user = Equipes::where('username', '=', $request->username)->first();
        if ($user) {
            if ($request->password == $user->password) {
                # code...
                Session::put('loginId', $user->id);
                $detail_etape = DB::table('course_detail')->where('id_equipe','=',$user->id)->get();
                $data = [
                    'equipe'=>$user,
                    'etape'=>$detail_etape
                ];
                return view('pages/equipe',compact('data'));
            } else {
                return back()->with('fail', 'Mot de passe incorrect');
            }
        } else {
            return back()->with('fail', 'Cette Email n`est pas encore enregistrer');
        }
    }

    public function show($id)
    {
        $equipe = Equipes::find($id);
        if ($equipe) {
            return view('pages.equipe', ['equipe' => $equipe]);
        } else {
            return redirect()->back()->with('fail', 'Équipe non trouvée');
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

    function delete(Request $request)
    {
        $modelName = 'App\Models\\' . $request['table'];
        echo $modelName;
        $instance = app()->make($modelName);
        echo $instance;
        $instance = $instance->find($request['id']);
        echo $instance;
        $instance->delete();
        return redirect()->route('/admins');
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

    public function classement(Request $request)
    {
        $classement_generale = DB::table('classement_generale')->where('idetape', $request['idetape'])->get();
        $data = [
            'classement_generale' => $classement_generale
        ];
        return view('pages/DashEtape', compact('data'));
    }

    public function classementCategorie(Request $request)
    {
        if ($request['id'] == 1) {
            $classement = DB::table('classement_homme')->get();
        }
        if ($request['id'] == 2) {
            $classement = DB::table('classement_femme')->get();
        }
        if ($request['id'] == 3) {
            $classement = DB::table('classement_junior')->get();
        }
        $data = [
            'classement_generale' => $classement
        ];
        return view('pages/DashCategorie', compact('data'));
    }

    public function generatePdf(Request $request)
    {
        $idetape = $request->input('idetape');
        $classement_generale = DB::table('classement_generale')->where('idetape', $idetape)->get();

        $html = view('pages/pdf/classement_generale', ['classement_generale' => $classement_generale])->render();

        $pdfPath = storage_path('classement_generale.pdf');

        // Utiliser Browsershot pour générer le PDF
        Browsershot::html($html)
            ->showBackground()
            ->format('A4')
            ->save($pdfPath);
        return back();
    }

    public function point()
    {
        $data = [
            'point' => Points::all()
        ];
        return view('pages/point', compact('data'));
    }

    public function penalty()
    {
        $data = [
            'penalty' => Penalities::all(),
            'etape' => Etapes::all(),
            'equipe' => Equipes::all()
        ];
        return view('pages/penalite', compact('data'));
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

    public function listeCategorie()
    {
        $data = [
            'categorie' => Categories::all()
        ];
        return view('pages/listeCategorie', compact('data'));
    }

    public function classement_generale()
    {
        $data = [
            'classement_generale' => DB::table('classement_generale')->get()
        ];
        return view('pages/listeGenerale', compact('data'));
    }
    public function etape_assignment()
    {
        $id = Session::get('loginId');
        $data = [
            'etape_assignments' => Etape_assignments::all(),
            'etape' => Etapes::all(),
            'coureur' => Coureurs::where('idequipe','=',$id)->get()
        ];
        return view('pages.etape_assignment', compact('data'));
    }

    public function stat(Request $request)
    {

        return view('pages.admin');
    }

    public function classement_equipe()
    {
        $data = [];

        $donnees = DB::table('classement_equipe')->get();
        // print($donnees);
        if (!empty($donnees)) {
            foreach ($donnees as $item) {
                $a = [
                    "equipe" => $item->equipe_nom,
                    "points" => $item->total_points
                ];
                array_push($data, $a);
            }
            print_r($data);
        }
        return response()->json($data);
    }

    public function lesCoureurs(Request $request)
    {
        $id_etape = $request['idetape'];
        $id = Session::get('loginId');
        $data = [
            'coureurs'=>DB::table('course_detail')->where('id_equipe','=',$id)->where('idetape','=',$id_etape)->get()
        ];
        // print($data['coureurs']);
        return view('pages/listeEtapeCoureurs',compact('data'));
    }

    public function resetDatabase(Request $request)
    {
        // Vérifiez si l'utilisateur est bien un admin
        // if (!auth()->user()->isAdmin()) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        // Désactiver les triggers
        DB::statement('ALTER TABLE results DISABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE etapes DISABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE coureur_categories DISABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE coureurs DISABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE categories DISABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE equipes DISABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE points DISABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE genres DISABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE chronos DISABLE TRIGGER ALL;');

        // Truncature des tables
        DB::table('results')->truncate();
        DB::table('etapes')->truncate();
        DB::table('coureur_categories')->truncate();
        DB::table('coureurs')->truncate();
        DB::table('categories')->truncate();
        DB::table('equipes')->truncate();
        DB::table('points')->truncate();
        DB::table('genres')->truncate();
        DB::table('chronos')->truncate();

        // Réactiver les triggers
        DB::statement('ALTER TABLE results ENABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE etapes ENABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE coureur_categories ENABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE coureurs ENABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE categories ENABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE equipes ENABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE points ENABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE genres ENABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE chronos ENABLE TRIGGER ALL;');

        return redirect()->route('login')->with('success', 'Réinitialisation base de donnée réussie.');
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

    public function importCSV1(Request $request)
    {
        // Vérifier si un fichier CSV a été envoyé
        if ($request->hasFile('csv_file1')) {
            $file = $request->file('csv_file1');

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
                    if (count($data) === 6) {

                        // Remplacer les virgules par des points dans la longueur
                        $longueur = (float)str_replace(',', '.', $data[1]);
                        // Créer un nouvel enregistrement dans la table Etape
                        $etape = new Etapes();
                        $etape->name = $data[0];
                        $etape->longueur = $longueur;
                        $etape->coureurs_per_equipe = $data[2];
                        $etape->rang = $data[3];
                        $etape->datedepart = $data[4];
                        $etape->heure_depart = $data[5];
                        $etape->save();
                    }
                }

                return redirect()->back()->with('success', 'Importation du premier fichier CSV réussie.');
            } else {
                return redirect()->back()->with('error', 'Le fichier 1 doit être au format CSV.');
            }
        }

        return redirect()->back()->with('error', 'Aucun fichier 1 CSV n\'a été envoyé.');
    }

    public function importCSV2(Request $request)
    {
        if ($request->hasFile('csv_file2')) {
            $filePath = $request->file('csv_file2')->path();

            $contents = file_get_contents($filePath);
            $lines = explode("\n", $contents);

            for ($i = 1; $i < count($lines); $i++) {
                $line = $lines[$i];
                $data = explode(
                    ';',
                    $line
                );

                if (count($data) === 7) {
                    $equipe = Equipes::firstOrCreate([
                        'name' => $data[5]
                    ], [
                        'username' => $data[5],
                        'password' => $data[5]
                    ]);

                    $genre = Genres::firstOrCreate(['name' => $data[3]]);

                    $etape = Etapes::where('rang', $data[0])->first();

                    if ($etape) {
                        // Check for duplicate coureurs based on name or numerodrossard
                        $coureur = Coureurs::firstOrCreate([
                            'name' => $data[2],
                            'dossard_number' => $data[1]
                        ], [
                            'birth_date' => $data[4],
                            'idgender' => $genre->id,
                            'idequipe' => $equipe->id
                        ]);

                        // Insert data into pivot table coureur_etape
                        DB::table('results')->updateOrInsert([
                            'idcoureur' => $coureur->id,
                            'idetape' => $etape->id
                        ], [
                            'heure_arrive' => $data[6]
                        ]);

                        // $date_depart = DB
                    }
                }
            }

            return redirect()->back()->with('success', 'Importation du fichier CSV réussie.');
        }

        return redirect()->back()->with('error', 'Aucun fichier CSV n\'a été envoyé.');
    }


    public function doubleImport(Request $request)
    {
        try {
            DB::beginTransaction();

            // Importer les données du premier fichier CSV
            $importCSV1 = $this->importCSV1($request);

            // Importer les données du deuxième fichier CSV
            $importCSV2 = $this->importCSV2($request);

            DB::commit();

            return redirect()->back()->with('success', 'Importation des deux fichiers CSV réussie.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'L\'importation des fichiers CSV a échoué: ' . $e->getMessage());
        }
    }
}
