<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CityModel;
use App\Models\GameModel;
use CodeIgniter\Model;
use App\Models\AddressModel;
use App\Models\GymClubModel;
use App\Models\GymModel;
use CodeIgniter\HTTP\ResponseInterface;

class Gym extends AdminController
{
    protected $addressModel;
    protected $gymModel;
    protected $gymClubModel;
    protected $gameModel;
    protected $cityModel;
    public function __construct(){
        $this->addressModel = new AddressModel();
        $this->gymModel = new GymModel();
        $this->gymClubModel = new GymClubModel();
        $this->gameModel = new GameModel();
        $this->cityModel = new CityModel();
    }
    public function index()
    {
        $this->addBreadcrumb('Liste des gymnases');
        $data = [
            'title' => 'Gymnases',
        ];
        return $this->render('admin/gym/index',$data);
    }

    public function form($id = null) {
        $this->addBreadcrumb('Listes des gymnases', '/admin/gym');

        if ($id != null) {
            $title = 'Ajout d\'un gymnase';
            $this->addBreadcrumb('Ajouter un gymnase');
            $gym = $this->gymModel->getGymById($id);
            $gym['clubs']= $this->gymClubModel->getClubsByIdGym($id);
            $gym['games']= $this->gameModel->getGamesByGym($id);
        } else {
            $title = 'Modification d\'un gymnase';
            $this->addBreadcrumb('Modifier un gymnase');
        }
        $data = [
            'title' => $title,
            'gym' => $gym ?? null,
        ];

        return $this->render('admin/gym/form', $data);
    }

    public function saveGym($id=null){
        try {
            //Récupération des données
            //Données concernant le gymnase
            $dataGym = [
                'id' => $id,
                'fbi_code' => $this->request->getPost('fbi_code'),
                'name' => $this->request->getPost('name'),
                'id_address' => intval($this->request->getPost('id_address'))
            ];

            //Données concernant l'adresse
            $dataAddress = [
                'id' => $dataGym['id_address'] != null ? $dataGym['id_address'] : '',
                'address_1' => $this->request->getPost('address_1') ?? '',
                'address_2' => $this->request->getPost('address_2') ?? '',
                'id_city' => $this->request->getPost('city') ?? '',
                'gps_location' => $this->request->getPost('gps_location') ?? '',
            ];

            //Données concernant le club
            $clubs = $this->request->getPost('clubs') ?? [];
            //Variable pour savoir si c'est un nouveau gymnase
            $newGym = empty($dataGym['id']);

            //Enregistrement de l'adresse et récupération de l'ID de la nouvelle adresse
            if(!$this->addressModel->save($dataAddress)){
                $this->error(implode('<br>',$this->addressModel->errors()));
                return $this->redirect('/admin/gym');
            } elseif ($newGym){
                $dataGym['id_address'] = $this->addressModel->getInsertID();
            }

            //Enregistrement du gymnase
            if(!$this->gymModel->saveGym($dataGym)){
                $this->error(implode('<br>',$this->gymModel->errors()));
                return $this->redirect('/admin/gym');
            } elseif ($newGym){
                $dataGym['id'] = $this->gymModel->getInsertID();
            }

            //GESTION DES CLUBS
            //Création des variables
            //Clubs existants
            $existingClubs = $this->gymClubModel->where('id_gym', $dataGym['id'])->findAll();
            $existingClubsIndexed = array_column($existingClubs, null,'id_club');
            //clubs
            $clubsIds = array_column($clubs,'id');

            //Création de la transaction
            $db = $this->gymClubModel->db;
            $db->transStart();

            //On supprime les clubs qui ont été retirés
            foreach ($existingClubs as $existingClub){
                if(!in_array($existingClub['id_club'], $clubsIds)){
                    $this->gymClubModel
                        ->where([
                            'id_gym'=>$dataGym['id'],
                            'id_club'=>$existingClub['id_club']
                        ])
                    ->delete();
                }
            }

            //On boucle sur les clubs envoyés dans le formulaire
            foreach ($clubs as $club) {
                //Variable du club envoyé dans le formulaire
                $dataGymClub = [
                    'id_club' => $club['id'],
                    'id_gym' => $dataGym['id'],
                    'main_gym' => isset($club['main_gym']) ? 1 : 0,
                ];

                //Enregistrement du club s'il n'existe pas déjà
                if(!isset($existingClubsIndexed[$club['id']])){
                    $this->gymClubModel->insert($dataGymClub);
                }
                //sinon, s'il existe, on compare le champ main_gym et le met à jour si besoin
                else {
                    if($existingClubsIndexed[$club['id']]['main_gym'] != $dataGymClub['main_gym']){
                        $this->gymClubModel->where('id_club',$dataGymClub['id_club'])->where('id_gym',$dataGymClub['id_gym'])->update(null, $dataGymClub);
                    }
                }
            }

            //Fermeture de la transaction
            $db->transComplete();

//            if (isset ($existingClubs)) {
//                foreach ($existingClubs as $existingClub) {
//                    if (!in_array($existingClub, $clubs)) {
//                        $this->gymClubModel->delete($existingClub);
//                    }
//                }
//            }

            //Gestion des messages de validation
            if ($newGym) {
                $this->success('Gymnase créé avec succès');
            } else {
                $this->success('Gymnase modifié avec succès');
            }

            return $this->redirect('admin/gym');

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }

    }

    //fonction à faire sur chaque itération du tableau
    private function InsertOrDeleteClub ($existingClubs,$data) {
        foreach ($existingClubs as $club) {

        }
        return ;
    }

    public function deleteGym($id) {
        try {
            $idAddress = $this->gymModel->getAddressByGym($id);
            if($this->gymModel->delete($id)){
                $this->addressModel->delete($idAddress);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le gymnase a bien été supprimé',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->gymModel->errors(),
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function searchGym(){
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
        $result = $this->gymModel->searchWithClubName($search, $page, $limit);

        //Réponse JSON
        return $this->response->setJSON($result);
    }

    public function importGyms(){
        try {
            $CSVFile = $this->request->getFile('import_csv');
            $gyms = [];
            $handle = fopen($CSVFile, 'r');
            if($handle !== false){
                //extraction de la première ligne avec les libellés de colonnes
                $dataKey= fgetcsv($handle, 1000, ',','"');

                //suppression des caractères invisibles en début de fichier
                $dataKey = preg_replace('/^\xEF\xBB\xBF/', '', $dataKey);

                $cptKeys = count($dataKey);

                //lecture des lignes de données tant qu'il y en a
                while (($line = fgets($handle)) !== false) {

                    //on enlève les espaces devant et derrière chaque ligne
                    $line = trim($line);

                    //on retire les doubles guillemets s'il y en a
                    $line = str_replace('""', '"', $line);

                    //on transforme la ligne string en array
                    $dataValue=str_getcsv($line,',','"');

                    if (count($dataValue) === $cptKeys) {
                        $gyms[] = array_combine($dataKey, $dataValue);
                    }
                }

                fclose($handle);

            }

        } catch(\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();

        }
    }
}
