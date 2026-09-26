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
            $sponsor['contacts'] = $this->contactModel->getContactsById($id,'club');
            $sponsor['seasons'] = $this->seasonSponsorModel->getSeasonsBySponsor($id);
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
            //Si logo supprimé mais pas remplacé
            $deleteLogo = $this->request->getPost('delete-logo');
            if(!empty($deleteLogo && !isset($logo))){
                $this->mediaModel->delete($deleteLogo);
            }
            if($logo->isvalid()){
                $dataLogo = [
                    'entity_id' => $id,
                    'entity_type' => 'sponsor',
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
                foreach($sponsors_seasons as $sponsor_season){
                    $dataSponsorSeason = [
                        'id_sponsor' => $id,
                        'id_season' => $sponsor_season['id_season'],
                        'id_rank' => $sponsor_season['rank'],
                        'id_dotation_type' => $sponsor_season['dotation_type'],
                        'dotation_amount' => $sponsor_season['dotation_amount'],
                        'specifications' => $sponsor_season['specifications'],
                    ];

                    if(!$this->seasonSponsorModel->insert($dataSponsorSeason)){
                        return redirect()->back()->withInput()->with('error',implode('<br>',$this->seasonSponsorModel->errors()));
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
                        'entity_type' => 'member',
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

    public function deleteSponsor($id) {
        try {
            if($this->sponsorModel->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le sponsor a bien été supprimé'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->sponsorModel->errors(),
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
