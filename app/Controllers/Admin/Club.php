<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClubModel;
use App\Models\GymClubModel;
use App\Models\MediaModel;
use App\Models\TeamModel;
use CodeIgniter\HTTP\ResponseInterface;

class Club extends AdminController
{
    protected $cm;
    protected $mediaModel;
    protected $teamModel;
    protected $gymClubModel;

    public function __construct(){
        $this->cm = new ClubModel();
        $this->mediaModel = new MediaModel();
        $this->teamModel = new TeamModel();
        $this->gymClubModel = new GymClubModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Clubs',
        ];
        $this->addBreadcrumb('Liste des clubs', '');
        return $this->render('admin/club/index',$data);
    }

    public function form($id=null) {
        $this->addBreadcrumb('Liste des clubs', 'admin/club');
        if($id != null) {
            $title = 'Modifier un club';
            $this->addBreadcrumb('Modifier un club');
            $club = $this->cm->getFullClub($id);
            $club['teams'] = $this->teamModel->getTeamsByClub($id);
            $club['gyms'] = $this->gymClubModel->getGymsByIdClub($id);
        } else {
            $title = 'Ajouter un club';
            $this->addBreadcrumb('Ajouter un club');
        }
        $data = [
            'title' => $title,
            'club' => $club ?? null,
        ];
        return $this->render('admin/club/form', $data);
    }

    public function saveClub($id=null) {
        try {
            //Récupération des données
            $dataClub =[
                'id' => $id,
                'code' => $this->request->getPost('code'),
                'name' => $this->request->getPost('name'),
                'color_1' => $this->request->getPost('color_1'),
                'color_2' => $this->request->getPost('color_2'),
            ];

            $gyms = $this->request->getPost('gym');

            //Récupération du logo
            $logo = $this->request->getFile('logo');
            $deletedLogo = $this->request->getPost('delete-logo');

            //Préparation de la variable pour savoir si c'est une création
            $newClub = empty($dataClub['id']);

            //Enregistrement en BDD
            if(!$this->cm->save($dataClub)){
                return redirect()->back()->withInput()->with('error',implode('<br>',$this->cm->errors()));
            }

            //Récupération de l'ID
            if($newClub) {
                $id = $this->cm->getInsertID();
            }

            //GESTION DES GYMNASES
            //Création des variables
            //Gym existants pour le club
            $existingGyms = $this->gymClubModel->where('id_club',$id)->findAll();
            $existingGymsIndexed = array_column($existingGyms,'main_gym','id_gym');

            //Gyms du formulaire
            $gymsIds = array_column($gyms,'id_gym');

            //Création de la transaction
            $this->gymClubModel->db->transStart();

            //On supprime les gymnases ayant été retirés
            foreach ($existingGyms as $existingGym){
                if(!in_array($existingGym['id_gym'],$gymsIds)){
                    $this->gymClubModel->where([
                        'id_gym' => $existingGym['id_gym'],
                        'id_club' => $id
                    ])->delete();
                }
            }

            //On boucle sur les gymnases envoyés dans le formulaire
            foreach($gyms as $gym){
                $dataGym = [
                    'id_club' => $id,
                    'id_gym' =>  intval($gym['id_gym']),
                    'main_gym'=> isset($gym['main_gym'])?1:0,
                ];
                //Enregistrement du gymnase s'il n'existe pas déjà
                if(!isset($existingGymsIndexed[$dataGym['id_gym']])){
                    $this->gymClubModel->insert($dataGym);
                //sinon, s'il existe, on compare le champ main_gym et le met à jour si besoin
                } elseif($existingGymsIndexed[$dataGym['id_gym']] != $dataGym['main_gym']){
                    $this->gymClubModel->where('id_gym',$dataGym['id_gym'])->where('id_club',$dataGym['id_club'])->update(null,$dataGym);
                }
            }

            //Fermeture de la transaction
            $this->gymClubModel->db->transComplete();


            // Gestion du logo
            //si logo supprimé et pas remplacé
            if(!empty($deletedLogo) && !$logo->isValid()){
                $this->mediaModel->delete($deletedLogo);
            }
            //enregistrement du logo
            if($logo->isValid() && !$logo->hasMoved()){
                $dataLogo = [
                    'entity_id' => $id,
                    'entity_type' => 'club',
                    'title' => 'Logo de ' . $dataClub['name'],
                    'alt' => 'Logo de ' . $dataClub['name'],
                ];
                $uploadResultLogo = upload_file($logo,'logos/club/'.$id, $logo->getName(),$dataLogo,false);
                if(is_array($uploadResultLogo) && isset($uploadResultLogo['status']) && $uploadResultLogo['status'] == 'error'){
                    $this->error("Erreur lors de l'upload du logo :".$uploadResultLogo['message']);
                }
            }

            //Gestion messages de validation
            if($newClub){
                $this->success('Club créé avec succès');
            } else {
                $this->success('Club modifié avec succès');
            }

            return $this->redirect('/admin/club');

        } catch (\Exception $e){
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function switchActiveClub($idClub){

        $club = $this->cm->withDeleted()->find($idClub);

        //Test pour savoir si le club existe
        if(!$club) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Club introuvable'
            ]);
        }

        // Si le club est actif, on le désactive
        if(empty($club['deleted_at'])) {
            $this->cm->delete($idClub);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Club désactivé',
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->cm->reactiveClub($idClub)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Club activé',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation',
                ]);
            }
        }
    }
    public function searchClub(){
        $request = $this->request;

        //Vérification Ajax
        if(!$request->isAJAX()) {
            return $this->response->setJSON(['error'=> 'Requête non autorisée']);
        }

        //Paramètres de recherche
        $search = $request->getget('search') ?? '';
        $page = (int) $request->getget('page') ?? 1;
        $limit = 25;

        //Utilisation de la méthode du Model (via le trait)
        $result = $this->cm->quickSearchForSelect2($search, $page, $limit, 'name', 'ASC');

        //Réponse JSON
        return $this->response->setJSON($result);
    }

    public function importClubs(){
        try {
            $CSVFile = $this->request->getFile('import_csv');
            $clubs = [];
            $cptClubs = 0;

            //variable qui ouvre et lit le fichier
            $handle = fopen($CSVFile, 'r');
            if ($handle !== false) {

                //extraction de la première ligne avec les libellés de colonnes
                $dataKey= fgetcsv($handle, 1000, ',','"');

                //suppression des caractères invisibles en début de fichier
                $dataKey = preg_replace('/^\xEF\xBB\xBF/', '', $dataKey);

                $cptKeys = count($dataKey);

                //lecture des lignes de données tant qu'il y en a
                while (($line = fgets($handle)) !== false) {

                    //on enlève les espaces devant et derrière chaque ligne
                    $line = trim($line);

                    //On enlève les éventuels guillemets autour de la ligne
                    if($line[0] === '"' && substr($line, -1) === '"') {
                        $line = substr($line, 1, -1);
                    }

                    //on retire les doubles guillemets s'il y en a
                    $line = str_replace('""', '"', $line);

                    //on transforme la ligne string en array
                    $dataValue=str_getcsv($line,',','"');

                    if (count($dataValue) === $cptKeys) {
                        $clubs[] = array_combine($dataKey, $dataValue);
                    }
                }
                fclose($handle);

                //on récupère les codes fbi existants des clubs associés aux ID
                $existingClubs = array_column($this->cm->findAll(),'code','id');

                //création pour chaque ligne des données à enregistrer en BDD
                foreach ($clubs as $club) {
                    $dataClub = [
                        'code' => $club['cd_org'],
                        'name' => $club['lb_org'],
                        'color_1' => $club['couleur_locale'],
                        'color_2' => $club['couleur_exterieur'],
                    ];

                    //enregistrement en BDD si le code FBI n'existe pas déjà
                    if (!in_array($dataClub['code'], $existingClubs)) {
                        if($this->cm->insert($dataClub)){
                            $cptClubs++;
                        } else {
                            $this->error(implode('<br>',$this->cm->errors()));
                            return $this->redirect('/admin/club');
                        }
                   }
                }

                //Message de validation
                $this->success($cptClubs.' clubs importés avec succès');
            }

            return $this->redirect('/admin/club');

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
