<?php

namespace Softadastra\Domain\Shop\Port;

use Softadastra\Domain\Shop\Entity\MidCategoryEntity;

interface MidCategoryRepositoryInterface
{
    public function save(MidCategoryEntity $mid_category);
    public function findAll();
    public function findById($id);
    public function delete($id);
    public function update(MidCategoryEntity $mid_category);
}
