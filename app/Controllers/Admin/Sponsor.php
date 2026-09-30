<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactModel;
use App\Models\MediaModel;
use App\Models\SeasonModel;
use App\Models\SeasonSponsorModel;
use App\Models\SponsorModel;
use CodeIgniter\HTTP\ResponseInterface;

class Sponsor extends AdminController
{
    protected $sponsorModel;
    protected $mediaModel;
    protected $contactModel;
    protected $seasonModel;
    protected $seasonSponsorModel;

    public function __construct() {
        $this->sponsorModel = new SponsorModel();
        $this->mediaModel = new MediaModel();
        $this->contactModel = new ContactModel();
        $this->seasonModel = new SeasonModel();
        $this->seasonSponsorModel = new SeasonSponsorModel();
    }
    public function index()
    {
        $title = 'Sponsors';
        $this->addBreadCrumb('Liste des sponsors');
        $data = [
            'title' => $title,
        ];
        return $this->render('admin/sponsor/index', $data);
    }

    public function form($id=null) {
        $this->addBreadcrumb('Liste des sponsors', 'admin/sponsor');
        $seasons = $this->seasonModel->OrderBy('start_date','desc')->findAll();
        if($id != null) {
            $title = 'Modifier un sponsor';
            $this->addBreadcrumb('Modifier un sponsor');
            $sponsor = $this->sponsorModel->getFullSponsor($id);
            $sponsor->contacts = $this->contactModel->getContactsById($id,'sponsor');
            $sponsor->seasons = $this->seasonSponsorModel->getSeasonsBySponsor($id);
        } else {
            $title = 'Ajouter un club';
            $this->addBreadcrumb('Ajouter un club');
        }
        $data = [
            'title' => $title,
            'sponsor' => $sponsor ?? null,
            'seasons' => $seasons ?? null,
        ];
        return $this->render('admin/sponsor/form', $data);
    }

    public function saveSponsor($id = null){
        try {
            //RÉCUPÉRATION DES DONNÉES
            //FORMULAIRE
            //sponsor
            $sponsor = [
                'id' => $id,
                'name' => $this->request->getPost('name'),
                'slogan' => $this->request->getPost('slogan'),
                'comments' => $this->request->getPost('comments'),
            ];
            $sponsors_seasons = $this->request->getPost('season[]');

            //logo
            $logo = $this->request->getFile('logo');

            // contact
            $contacts = $this->request->getPost('contacts');
            $removedContacts = $this->request->getPost('removed-contacts') ?? [];

            //Images
            $sponsorImages = $this->request->getFiles()['sponsor_images'];
            $deletedImg = $this->request->getPost('deleted-img');

            //RÉCUPÉRATION DES DONNÉES EXISTANTES (BDD)
            //Saisons du sponsor - création de la variable pour savoir si la saison existe déjà pour ce sponsor
            $existingSeasonsSponsor = array_column($this->seasonSponsorModel->getSeasonsBySponsor($id),'id_season');

            //préparation de la variable pour savoir si c'est une création
            $newSponsor = empty($sponsor['id']);

            //Si je n'ai pas de sponsor et que je ne suis pas en mode création
            if(!$sponsor && !$newSponsor) {
                $this->error('Sponsor introuvable');
                return $this->redirect('/admin/sponsor');
            }

            //Enregistrement en BDD
            if(!$this->sponsorModel->save($sponsor)){
                return redirect()->back()->withInput()->with('error',implode('<br>',$this->sponsorModel->errors()));

            }

            //on récupère l'ID créé si c'est un nouveau sponsor
            if ($newSponsor) {
                $id = $this->sponsorModel->getInsertID();
            }

            //GESTION DU LOGO
            //Si logo supprimé mais pas remplacé, on le supprime
            $deleteLogo = $this->request->getPost('delete-logo');

            if(!empty($deleteLogo && $logo != null)){
                $this->mediaModel->delete($deleteLogo);
            }

            //Ajout/modification du logo
            if($logo->isvalid()){
                $dataLogo = [
                    'entity_id' => $id,
                    'entity_type' => 'sponsor_logo',
                    'title' => 'Logo de ' . $sponsor['name'],
                    'alt' => 'Logo de ' . $sponsor['name'],
                ];
                $uploadResultLogo = upload_file($logo,'logos/sponsor/'.$id, $logo->getName(),$dataLogo,false);
                if(is_array($uploadResultLogo) && isset($uploadResultLogo['status']) && $uploadResultLogo['status'] == 'error'){
                    $this->error("Erreur lors de l'upload du logo :".$uploadResultLogo['message']);
                }
            }

            //GESTION DES SAISONS DE SPONSORING
            if(isset($sponsors_seasons)){
                //Création clé des saisons du formulaire pour gérer la suppression
                $keySponsorsSeasons = array_column($sponsors_seasons,'id_season');
                //Suppression des saisons qui ne sont plus dans le formulaire
                $seasonsToDelete = array_diff($existingSeasonsSponsor,$keySponsorsSeasons);
                foreach($seasonsToDelete as $seasonToDelete){
                    $this->seasonSponsorModel->delete($seasonToDelete);
                }

                //Ajout/modification des saisons présentes dans le formulaire
                foreach($sponsors_seasons as $sponsor_season){
                    $dataSponsorSeason = [
                        'id_sponsor' => $id,
                        'id_season' => $sponsor_season['id_season'],
                        'id_rank' => $sponsor_season['rank'],
                        'id_dotation_type' => $sponsor_season['dotation_type'],
                        'dotation_amount' => $sponsor_season['dotation_amount'],
                        'specifications' => $sponsor_season['specifications'],
                    ];

                    //Ajout des nouvelles saisons
                    if(!in_array($dataSponsorSeason['id_season'],$existingSeasonsSponsor)){
                        if(!$this->seasonSponsorModel->insert($dataSponsorSeason)){
                            return redirect()->back()->withInput()->with('error',implode('<br>',$this->seasonSponsorModel->errors()));
                        }
                    }
                    //Modification des saisons existantes
                    else {
                        if(!$this->seasonSponsorModel->where('id_sponsor',$dataSponsorSeason['id_sponsor'])->where('id_season',$dataSponsorSeason['id_season'])->update(null,$dataSponsorSeason)){
                            return redirect()->back()->withInput()->with('error',implode('<br>',$this->seasonSponsorModel->errors()));
                        }
                    }
                }
            }

            //GESTION DES CONTACTS
            //Gestion suppression des contacts
            if(isset($removedContacts)) {
                foreach($removedContacts as $removedContact) {
                    $this->contactModel->where('id',$removedContact)->delete();
                }
            }

            //Gestion ajout et mise à jour des contacts
            if(isset($contacts)) {
                foreach($contacts as $contact) {
                    $dataContact = [
                        'id' => $contact['id'] ?? null,
                        'entity_type' => 'sponsor',
                        'entity_id' => $sponsor['id'],
                        'phone_number' => $contact['phone_number'],
                        'mail' => $contact['mail'],
                        'details' => $contact['details']
                    ];
                    if(!$this->contactModel->save($dataContact)){
                        return redirect()->back()->withInput()->with('error',implode('<br>',$this->contactModel->errors()));
                    }
                }
            }

            //Suppression éventuelle des images
            if(isset($deletedImg)) {
                foreach ($deletedImg as $img) {
                    $this->mediaModel->deleteMedia($img);
                }
            }

            // Upload des images si présentes
            if($sponsorImages != null) {
                foreach($sponsorImages as $sponsorImg)
                {
                    //Permet de tester si ce sont biens de nouvelles images et qu'elles sont valides, que ce ne sont pas les images déjà existantes
                    if($sponsorImg->isvalid()){
                        $sponsorImgName = $sponsorImg->getName();
                        $result = upload_file(
                            $sponsorImg,
                            'sponsor/images/'.$sponsor['id'],
                            $sponsorImgName,
                            [
                                'entity_id' => $id,
                                'entity_type' => 'sponsor_image',
                                'title' => 'Image de '.$sponsor['name'],
                                'alt' => 'Image de '.$sponsor['name'],
                            ],
                            true,
                        );

                        if (is_array($result) && isset($result['status']) && $result['status'] === 'error') {
                            $error = "Erreur lors de l'upload de l'image ".$sponsorImgName . " : " . $result['message'];
                        }
                    }
                }
                if(isset($error) && $error != null){
                    return redirect()->back()->withInput()->with('error',$error);
                }
            }

            // Gestion des messages de validation
            if($newSponsor){
                $this->success('Sponsor créé avec succès');
            } else {
                $this->success('Sponsor modifié avec succès');
            }

            return $this->redirect('admin/sponsor');

        } catch(\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function switchActiveSponsor($idSponsor){

        $sponsor = $this->sponsorModel->withDeleted()->find($idSponsor);

        //Test pour savoir si le sponsor existe
        if(!$sponsor) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sponsor introuvable'
            ]);
        }

        // Si le sponsor est actif, on le désactive
        if(empty($sponsor->deleted_at)) {
            $this->sponsorModel->delete($idSponsor);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sponsor désactivé',
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->sponsorModel->reactiveSponsor($idSponsor)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Sponsor activé',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation',
                ]);
            }
        }
    }
}
