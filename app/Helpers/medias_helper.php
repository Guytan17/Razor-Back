<?php
if (!function_exists('upload_file')) {
    /**
     * Upload d'un fichier média avec gestion de l'Entity Media
     * Génère automatiquement des thumbnails pour les images (medium: 500px, thumb: 200px)
     *
     * @param \CodeIgniter\Files\File $file - Fichier à uploader
     * @param string $subfolder - Sous-dossier (ex: avatars, recipes)
     * @param string|null $customName - Nom personnalisé du fichier
     * @param array|null $mediaData - Données associées (entity_id, entity_type, title, alt)
     * @param bool $isMultiple - Si false, remplace l'ancien média lié
     * @param array $acceptedMimeTypes - Types MIME autorisés
     * @param int $maxSize - Taille max en Ko
     * @return \App\Entities\Media|array - L'Entity Media ou un tableau d'erreur
     */
    function upload_file(
        \CodeIgniter\Files\File $file,
        string $subfolder = '',
        string $customName = null,
        ?array $mediaData = null,
        bool $isMultiple = false,
        array $acceptedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        int $maxSize = 2048
    ) {
        // 1️⃣ Vérification du fichier
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return ['status' => 'error', 'message' => getUploadErrorMessage($file->getError())];
        }

        if ($file->hasMoved()) {
            return ['status' => 'error', 'message' => 'Le fichier a déjà été déplacé.'];
        }

        if (!in_array($file->getMimeType(), $acceptedMimeTypes)) {
            return ['status' => 'error', 'message' => 'Type de fichier non accepté.'];
        }

        if ($file->getSizeByUnit('kb') > $maxSize) {
            return ['status' => 'error', 'message' => 'Fichier trop volumineux.'];
        }

        // 2️⃣ Définir le dossier de destination
        $year  = date('Y');
        $month = date('m');
        $uploadPath = FCPATH . 'uploads/' . trim($subfolder, '/') . '/' . $year . '/' . $month;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        // 3️⃣ Générer un nom propre et vérifier si c'est une image AVANT de déplacer
        helper('text');
        $baseName = $customName ? url_title($customName, '-', true) : pathinfo($file->getClientName(), PATHINFO_FILENAME);
        $ext = $file->getExtension();
        $newName = $baseName . '-' . uniqid() . '.' . $ext;

        // Récupérer le MIME type AVANT de déplacer le fichier
        $mimeType = $file->getMimeType();
        $isImage = in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        // 4️⃣ Déplacer le fichier
        $file->move($uploadPath, $newName);
        $relativePath = 'uploads/' . trim($subfolder, '/') . '/' . $year . '/' . $month . '/' . $newName;
        $absolutePath = $uploadPath . '/' . $newName;

        // 5️⃣ Générer les thumbnails si c'est une image
        if ($isImage) {
            try {
                generate_thumbnails($absolutePath, $baseName, $ext);
            } catch (\Exception $e) {
                log_message('error', 'Erreur génération thumbnails: ' . $e->getMessage());
                // Continue même si la génération de thumbnails échoue
            }
        }

        // 6️⃣ Enregistrer ou mettre à jour le média
        $mediaModel = model('MediaModel');

        if (!$isMultiple && isset($mediaData['entity_id'], $mediaData['entity_type'])) {
            $existing = $mediaModel
                ->where('entity_id', $mediaData['entity_id'])
                ->where('entity_type', $mediaData['entity_type'])
                ->first();

            if ($existing) {
                // Supprimer l'ancien fichier et ses thumbnails
                if ($existing->fileExists()) {
                    delete_media_files($existing->getAbsolutePath());
                }

                // Mettre à jour l'existant
                $mediaModel->update($existing->id, ['file_path' => $relativePath] + $mediaData);
                return $mediaModel->find($existing->id);
            }
        }

        // 7️⃣ Insertion d'un nouveau média
        $data = array_merge(['file_path' => $relativePath], $mediaData ?? []);
        $mediaId = $mediaModel->insert($data, true);

        return $mediaModel->find($mediaId);
    }
}

if (!function_exists('getUploadErrorMessage')) {
    /**
     * Convertit le code d'erreur d'upload en message explicite.
     *
     * @param int $errorCode - Le code d'erreur
     * @return string - Le message d'erreur correspondant
     */
    function getUploadErrorMessage(int $errorCode): string
    {
        switch ($errorCode) {
            case UPLOAD_ERR_OK:
                return 'Aucune erreur, le fichier est valide.';
            case UPLOAD_ERR_INI_SIZE:
                return 'Le fichier dépasse la taille maximale autorisée par la configuration PHP.';
            case UPLOAD_ERR_FORM_SIZE:
                return 'Le fichier dépasse la taille maximale autorisée par le formulaire HTML.';
            case UPLOAD_ERR_PARTIAL:
                return 'Le fichier n\'a été que partiellement téléchargé.';
            case UPLOAD_ERR_NO_FILE:
                return 'Aucun fichier n\'a été téléchargé.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Le dossier temporaire est manquant.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Échec de l\'écriture du fichier sur le disque.';
            case UPLOAD_ERR_EXTENSION:
                return 'Une extension PHP a arrêté l\'upload du fichier.';
            default:
                return 'Une erreur inconnue est survenue lors de l\'upload.';
        }
    }

}

if (!function_exists('generate_thumbnails')) {
    /**
     * Génère les versions medium (500px) et thumb (200px) d'une image
     *
     * @param string $absolutePath Chemin absolu de l'image originale
     * @param string $baseName Nom de base du fichier (sans extension ni uniqid)
     * @param string $ext Extension du fichier
     * @return void
     */
    function generate_thumbnails(string $absolutePath, string $baseName, string $ext): void
    {
        // Vérifier que le fichier source existe
        if (!file_exists($absolutePath)) {
            log_message('error', 'generate_thumbnails: fichier source introuvable: ' . $absolutePath);
            return;
        }

        $directory = dirname($absolutePath);

        // Extraire l'uniqid du nom de fichier
        $filename = pathinfo($absolutePath, PATHINFO_FILENAME);
        $uniqid = substr($filename, strrpos($filename, '-'));

        log_message('debug', 'generate_thumbnails: début pour ' . $absolutePath);
        log_message('debug', 'generate_thumbnails: baseName=' . $baseName . ', uniqid=' . $uniqid . ', ext=' . $ext);

        // Définir les tailles
        $sizes = [
            'medium' => 500,
            'thumb' => 200
        ];

        foreach ($sizes as $suffix => $maxSize) {
            try {
                // Créer une nouvelle instance pour chaque thumbnail
                $image = \Config\Services::image('gd', null, false);

                $newName = $baseName . $uniqid . '-' . $suffix . '.' . $ext;
                $newPath = $directory . '/' . $newName;

                log_message('debug', 'generate_thumbnails: génération ' . $suffix . ' vers ' . $newPath);

                $image->withFile($absolutePath)
                    ->fit($maxSize, $maxSize, 'center')
                    ->save($newPath);

                log_message('debug', 'generate_thumbnails: ' . $suffix . ' créé avec succès');
            } catch (\Exception $e) {
                log_message('error', 'Erreur génération thumbnail ' . $suffix . ': ' . $e->getMessage());
                log_message('error', 'Trace: ' . $e->getTraceAsString());
            }
        }
    }
}

if (!function_exists('delete_media_files')) {
    /**
     * Supprime un fichier média et toutes ses versions (medium, thumb)
     *
     * @param string $absolutePath Chemin absolu du fichier original
     * @return void
     */
    function delete_media_files(string $absolutePath): void
    {
        if (!file_exists($absolutePath)) {
            return;
        }

        $directory = dirname($absolutePath);
        $filename = pathinfo($absolutePath, PATHINFO_FILENAME);
        $ext = pathinfo($absolutePath, PATHINFO_EXTENSION);

        // Supprimer l'original
        @unlink($absolutePath);

        // Supprimer les thumbnails
        $suffixes = ['medium', 'thumb'];
        foreach ($suffixes as $suffix) {
            $thumbPath = $directory . '/' . $filename . '-' . $suffix . '.' . $ext;
            if (file_exists($thumbPath)) {
                @unlink($thumbPath);
            }
        }
    }
}

if (!function_exists('get_media_url')) {
    /**
     * Récupère l'URL d'un média selon la taille demandée
     *
     * @param int $mediaId ID du média dans la base de données
     * @param string $size Taille demandée : 'full', 'medium', 'thumb'
     * @param string|null $default URL par défaut si le média n'existe pas
     * @return string|null URL du média ou URL par défaut
     */
    function get_media_url(int $mediaId, string $size = 'full', ?string $default = null): ?string
    {
        $mediaModel = model('MediaModel');
        $media = $mediaModel->find($mediaId);

        if (!$media) {
            return $default ? base_url($default) : null;
        }

        // Si 'full', retourner l'URL normale
        if ($size === 'full') {
            return $media->fileExists() ? $media->getUrl() : ($default ? base_url($default) : null);
        }

        // Pour medium ou thumb, construire le chemin avec le suffixe
        $filePath = $media->file_path;
        $pathInfo = pathinfo($filePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];

        $thumbPath = $directory . '/' . $filename . '-' . $size . '.' . $extension;
        $absolutePath = FCPATH . $thumbPath;

        if (file_exists($absolutePath)) {
            return base_url($thumbPath);
        }

        // Fallback sur l'image full si le thumbnail n'existe pas
        return $media->fileExists() ? $media->getUrl() : ($default ? base_url($default) : null);
    }
}

if (!function_exists('get_site_logo')) {
    /**
     * Récupère l'URL du logo du site
     *
     * @param string $size Taille demandée : 'full', 'medium', 'thumb'
     * @param string $default URL du logo par défaut si aucun logo n'est défini
     * @return string URL du logo
     */
    function get_site_logo(string $size = 'thumb', string $default = 'assets/img/logo.png'): string
    {
        $logoId = setting('App.siteLogoId') ?? null;

        if (!$logoId) {
            return base_url($default);
        }

        $url = get_media_url($logoId, $size, $default);
        return $url ?? base_url($default);
    }
}