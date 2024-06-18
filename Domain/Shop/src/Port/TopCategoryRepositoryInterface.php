<?php

namespace Softadastra\Domain\Shop\Port;

use Softadastra\Domain\Shop\Entity\TopCategoryEntity;

interface TopCategoryRepositoryInterface
{
    public function save(TopCategoryEntity $top_category);
    public function findAll();
    public function findOne(array $conditions);
    public function findById($id);
    public function delete($id);
    public function update(TopCategoryEntity $top_category);
}
