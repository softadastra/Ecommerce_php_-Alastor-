<?php

namespace Softadastra\Domain\Shop\Entity;

class MidCategoryEntity
{
    private int $mcat_id;
    private string $mcat_name;
    private int $tcat_id;
    private string $mid_image;

    private array $errors = [];

    public function __construct(string $mcat_name, int $tcat_id, string $mid_image)
    {
        $this->setMcatName($mcat_name);
        $this->setTcatId($tcat_id);
        $this->setMidImage($mid_image);
    }

    private $endCategories = [];

    public function setEndCategories($endCategories)
    {
        $this->endCategories = $endCategories;
    }

    public function getEndCategories()
    {
        return $this->endCategories;
    }
    public function getMcatId(): int
    {
        return $this->mcat_id;
    }

    public function setMcatId(int $id)
    {
        $this->mcat_id = $id;
    }
    public function setMcatName(string $mcat_name): void
    {
        if (empty($mcat_name)) {
            $this->errors['mcat_name'] = "Indiquez le nom de la categorie";
            return;
        }
        $this->mcat_name = $mcat_name;
    }
    public function getMcatName(): string
    {
        return $this->mcat_name;
    }
    public function setTcatId(int $tcat_id): void
    {
        if (empty($tcat_id)) {
            $this->errors['tcat_id'] = "Veillez selectionner la categorie principale";
            return;
        }
        $this->tcat_id = $tcat_id;
    }
    public function getTcatId(): int
    {
        return $this->tcat_id;
    }
    public function setMidImage(string $mid_image): void
    {
        if (empty($mid_image)) {
            $this->errors['image'] = "Veillez completer l'image";
            return;
        }
        $this->mid_image = $mid_image;
    }
    public function getMidImage(): string
    {
        return $this->mid_image;
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
