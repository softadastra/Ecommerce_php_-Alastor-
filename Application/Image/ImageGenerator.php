<?php

namespace Softadastra\Application\Image;

class ImageGenerator
{
    public function create(array $files, string $uploadDirectory = '')
    {
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }
        $photo['images'] = '';
        foreach ($files['p_featured_photo']['name'] as $key => $name) {
            if ($files['p_featured_photo']['error'][$key] !== UPLOAD_ERR_OK) {
                echo "Une erreur est survenue lors de l'envoi du fichier $name.";
                continue;
            }
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '.' . $extension;
            $uploadFilePath = $uploadDirectory . DIRECTORY_SEPARATOR . $uniqueName;
            if (!move_uploaded_file($files['p_featured_photo']['tmp_name'][$key], $uploadFilePath)) {
                echo "Une erreur est survenue lors de la sauvegarde du fichier $name.";
            } else {
                $photo['images'] .= $uploadFilePath . ',';
            }
        }
        $photo['images'] = rtrim($photo['images'], ',');

        return $photo['images'];
    }

    public function profil(array $file, string $uploadDirectory = '')
    {
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo "Une erreur est survenue lors de l'envoi du fichier.";
            return false;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueName = uniqid() . '.' . $extension;
        $uploadFilePath = $uploadDirectory . DIRECTORY_SEPARATOR . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
            echo "Une erreur est survenue lors de la sauvegarde du fichier.";
            return false;
        }

        return $uploadFilePath;
    }
}
