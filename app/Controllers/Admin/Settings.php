<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\ResponseInterface;

class Settings extends AdminController
{
    protected $title = 'Réglages';
    protected $menu = 'settings';
    protected $breadcrumb = [['text' => 'Réglages']];

    public function index()
    {
        // Charger les paramètres actuels
        $settings = service('settings');
        $mediaModel = model('MediaModel');

        // Récupérer l'ID du logo depuis les settings
        $logoId = $settings->get('App.siteLogoId', null);
        $hasLogo = false;
        $defaultUrl = 'assets/img/logo.png';
        $logoUrl = base_url($defaultUrl);

        // Charger le media si un ID est défini
        if ($logoId) {
            $url = get_media_url($logoId, 'medium', $defaultUrl);
            if ($url) {
                $hasLogo = true;
                $logoUrl = $url;
            }
        }

        $data = [
            'site_title' => $settings->get('App.siteName', 'Mon Site'),
            'contact_email' => $settings->get('App.contactEmail', ''),
            'site_logo_url' => $logoUrl,
            'has_logo' => $hasLogo,
        ];

        return $this->render('admin/settings', $data);
    }

    public function save()
    {
        // Validation des données
        $validation = \Config\Services::validation();

        $validation->setRules([
            'site_title' => 'required|min_length[3]|max_length[255]',
            'contact_email' => 'required|valid_email|max_length[255]',
            'site_logo' => 'permit_empty|max_size[site_logo,2048]|is_image[site_logo]',
        ], [
            'site_title' => [
                'required' => 'Le titre du site est obligatoire.',
                'min_length' => 'Le titre du site doit contenir au moins 3 caractères.',
                'max_length' => 'Le titre du site ne peut pas dépasser 255 caractères.',
            ],
            'contact_email' => [
                'required' => 'L\'email de contact est obligatoire.',
                'valid_email' => 'L\'email de contact doit être valide.',
                'max_length' => 'L\'email de contact ne peut pas dépasser 255 caractères.',
            ],
            'site_logo' => [
                'max_size' => 'Le logo ne peut pas dépasser 2MB.',
                'is_image' => 'Le fichier doit être une image valide.',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            foreach ($validation->getErrors() as $error) {
                $this->error($error);
            }
            return $this->redirect('admin/reglages');
        }

        // Sauvegarder les paramètres
        $settings = service('settings');

        try {
            $settings->set('App.siteName', $this->request->getPost('site_title'));
            $settings->set('App.contactEmail', $this->request->getPost('contact_email'));

            // Gérer l'upload du logo si un fichier est fourni
            $logoFile = $this->request->getFile('site_logo');
            if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
                helper('utils');
                
                // Utiliser upload_file() avec le système de médias
                $media = upload_file(
                    file: $logoFile,
                    subfolder: 'logos',
                    customName: 'site-logo',
                    mediaData: [
                        'entity_id' => 1,
                        'entity_type' => 'settings',
                        'title' => 'Logo du site',
                        'alt' => $this->request->getPost('site_title'),
                    ],
                    isMultiple: false, // Remplace l'ancien logo
                    acceptedMimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
                    maxSize: 2048
                );

                // Vérifier si l'upload a réussi
                if (is_array($media) && isset($media['status']) && $media['status'] === 'error') {
                    $this->error($media['message']);
                } else {
                    // Sauvegarder l'ID du media dans les settings
                    $settings->set('App.siteLogoId', $media->id);
                }
            }

            $this->success('Les réglages ont été enregistrés avec succès.');
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de la sauvegarde des réglages: ' . $e->getMessage());
            $this->error('Une erreur est survenue lors de la sauvegarde des réglages.');
        }

        return $this->redirect('admin/reglages');
    }

    /**
     * Supprime le logo du site (appelé en AJAX)
     */
    public function deleteLogo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Requête non autorisée'
            ]);
        }

        $settings = service('settings');
        $mediaModel = model('MediaModel');
        
        $logoId = $settings->get('App.siteLogoId', null);

        if (empty($logoId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucun logo à supprimer'
            ]);
        }

        try {
            // Supprimer le média (fichier + enregistrement)
            $result = $mediaModel->deleteMedia($logoId);
            
            if ($result) {
                // Supprimer l'entrée dans les settings
                $settings->forget('App.siteLogoId');

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le logo a été supprimé avec succès',
                    'defaultLogoUrl' => base_url('assets/img/logo.png')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Impossible de supprimer le logo'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de la suppression du logo: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression du logo'
            ]);
        }
    }
}
