<?php

namespace Softadastra\Domain\Auth\Manager;

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Domain\Auth\Entity\UserEntity;
use Softadastra\Domain\Auth\Port\UserRepositoryInterface;

class CreateUser
{
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(array $data)
    {
        $role = 'user';
        $status = 'active';
        $country = 1;
        $photo =  "softadastra_images/photo-profil/avatar.jpg";
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $user = new UserEntity($data['full_name'], $data['email'], $data['phone'], $password, $photo, $role, $status, $country);

        if ($user->isValid()) {
            $this->repository->save($user);
            $_SESSION['success_user_register'] = "Compte creer avec succes";
            RedirectionHelper::redirect("auth/login");
        } else {
            $_SESSION['errors_user_register'] = $user->getErrors();
            $_SESSION['existing_email'] = $user->getEmail();
            $_SESSION['existing_full_name'] = $user->getFullName();
            $_SESSION['existing_phone'] = $user->getPhone();
            RedirectionHelper::redirect("auth/register");
        }
    }
}