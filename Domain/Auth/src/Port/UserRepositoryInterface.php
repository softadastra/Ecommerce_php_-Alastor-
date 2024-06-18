<?php

namespace Softadastra\Domain\Auth\Port;

use Softadastra\Domain\Auth\Entity\UserEntity;

interface UserRepositoryInterface
{
    public function save(UserEntity $user);
    public function findById($id);
    public function findAll();
    public function findOne(array $conditions);
    public function findByFullName(string $full_name);
    public function delete($id);
    public function update(UserEntity $user);
}
