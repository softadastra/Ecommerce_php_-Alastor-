<?php

namespace Softadastra\Domain\Shop\Entity;

class CountryEntity
{
    private $country_id;
    private $country_name;
    private $country_image;

    public function __construct(string $country_name, string $country_image)
    {
        $this->setCountryName($country_name);
        $this->setCountryImage($country_image);
    }
    public function getCountryId()
    {
        return $this->country_id;
    }
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }
    public function getCountryName()
    {
        return $this->country_name;
    }
    public function setCountryName($country_name)
    {
        $this->country_name = $country_name;
    }
    public function getCountryImage()
    {
        return $this->country_image;
    }
    public function setCountryImage($country_image)
    {
        $this->country_image = $country_image;
    }
}
