<?php

namespace Softadastra\Controllers;

use DateTime;
use PDOException;
use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Domain\Auth\Adapters\UserRepositoryPDO;
use Softadastra\Domain\Auth\Manager\CreateUser;

class AuthController extends Controller
{
    private $path = 'auth.';

    public function login()
    {
        if (isset($_COOKIE['user_id'])) {
            return RedirectionHelper::redirect("/");
        }
        return $this->view($this->path . 'login');
    }

    public function postLogin()
    {
        try {
            $userRepo = new UserRepositoryPDO();
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['errors_login_users'] = "Veuillez remplir tous les champs";
                return RedirectionHelper::redirect("auth/login");
            }

            $user = $userRepo->findByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $user->setStatus("Active now");
                $user->setUpdateDate(new DateTime());
                $userRepo->update($user);
                $_SESSION['unique_id'] = $user->getId();
                setcookie('user_id', $user->getId(), time() + 60 * 60 * 24 * 30);
                $redirectUrl = ($user->getRole() === 'admin') ? "admin/users/dashboard/{$user->getId()}" : "admin/users/dashboard/{$user->getId()}";
                return RedirectionHelper::redirect($redirectUrl);
            } else {
                $_SESSION['errors_login_users'] = "Adresse e-mail ou mot de passe incorrect !";
                $_SESSION['existing_email'] = $email;
                return RedirectionHelper::redirect("auth/login");
            }
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
    }


    public function register()
    {
        return $this->view($this->path . 'register');
    }

    public function postRegister()
    {
        $user_pdo = new UserRepositoryPDO();
        $user = new CreateUser($user_pdo);
        $user->execute($_POST);
    }

    public function logout(int $id)
    {
        if (isset($_COOKIE['user_id'])) {
            if (isset($_SESSION['user_id'])) {
                $id = $_SESSION['unique_id'];
            }
            $status = "Offline now";

            $user_pdo = new UserRepositoryPDO();
            $user = $user_pdo->findById($id);

            if ($user) {
                $user->setStatus($status);
                $user->setUpdateDate(new DateTime());
                $user_pdo->update($user);
            }
            session_unset();
            session_destroy();
            setcookie('PHPSESSID', '', time() - 3600, '/');
            setcookie('user_id', '', time() - 3600, '/');
        }
        return RedirectionHelper::redirect("auth/login");
    }
}
