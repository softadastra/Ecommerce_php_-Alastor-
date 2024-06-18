<?php

namespace Softadastra\Domain\Shop\Entity;

class EndCategoryEntity
{
    private int $ecat_id;
    private string $ecat_name;
    private int $mcat_id;
    private string $end_image;

    private array $errors = [];

    public function __construct(string $ecat_name, int $mcat_id, string $end_image)
    {
        $this->setName($ecat_name);
        $this->setMidId($mcat_id);
        $this->setImage($end_image);
    }
    public function getEcatId(): int
    {
        return $this->ecat_id;
    }

    public function setEcatId(int $id)
    {
        $this->ecat_id = $id;
    }
    public function setName(string $ecat_name): void
    {
        if (empty($ecat_name)) {
            $this->errors['ecat_name'] = "Indiquez le nom de la categorie";
            return;
        }
        $this->ecat_name = $ecat_name;
    }
    public function getName(): string
    {
        return $this->ecat_name;
    }
    public function setMidId(int $mcat_id): void
    {
        if (empty($mcat_id)) {
            $this->errors['mcat_id'] = "Veillez selectionner la categorie";
            return;
        }
        $this->mcat_id = $mcat_id;
    }
    public function getMidId(): int
    {
        return $this->mcat_id;
    }
    public function setImage(string $end_image): void
    {
        if (empty($end_image)) {
            $this->errors['image'] = "Veillez completer l'image";
            return;
        }
        $this->end_image = $end_image;
    }
    public function getImage(): string
    {
        if (empty($this->end_image)) {
            return "category-default.jpg";
        }
        return $this->end_image;
    }


    public function isValid()
    {
        return empty($this->getErrors());
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
