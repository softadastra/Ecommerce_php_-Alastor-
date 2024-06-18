<?php

namespace Softadastra\Domain\Shop\Port;

use Softadastra\Domain\Shop\Entity\EndCategoryEntity;

interface EndCategoryRepositoryInterface
{
    public function save(EndCategoryEntity $end_category);
    public function findAll();
    public function findById($id);
    public function delete($id);
    public function update(EndCategoryEntity $end_category);
}
