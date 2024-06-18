<?php

namespace Softadastra\Domain\Shop\Entity;

class TopCategoryEntity
{
    private int $tcat_id;
    private string $tcat_name;
    private string $image;

    private array $errors = [];

    public function __construct(string $tcat_name, string $image)
    {
        $this->setName($tcat_name);
        $this->setImages($image);
    }

    public function getId()
    {
        return $this->tcat_id;
    }

    public function setId(int $id)
    {
        $this->tcat_id = $id;
    }

    public function getName()
    {
        return $this->tcat_name;
    }

    public function setName(string $tcat_name)
    {
        if (empty($tcat_name)) {
            $this->errors['tcat_name'] = "Completez le nom de la categorie";
            return;
        }
        $this->tcat_name = $tcat_name;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImages(string $image)
    {
        if (empty($image)) {
            $this->errors['image'] = "Veillez completer une image";
            return;
        }
        $this->image = $image;
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
