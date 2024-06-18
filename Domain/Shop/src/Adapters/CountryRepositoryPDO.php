<?php

namespace Softadastra\Domain\Shop\Adapters;

use Softadastra\Domain\Shop\Entity\CountryEntity;
use Softadastra\Domain\Shop\Model\ModelRepositoryPDO;

class CountryRepositoryPDO extends ModelRepositoryPDO
{
    protected $table = 'tbl_country';

    public function save(CountryEntity $country)
    {
        $query = $this->pdo->getPdo()->prepare(
            "INSERT INTO {$this->table}(country_name, country_image) VALUES(:country_name,country_image)"
        );
        $query->execute([
            'country_name' => $country->getCountryName(),
            'country_image' => $country->getCountryImage()
        ]);
    }
}
