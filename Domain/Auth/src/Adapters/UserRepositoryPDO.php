<?php

namespace Softadastra\Domain\Auth\Adapters;

use Softadastra\Domain\Auth\Entity\UserEntity;
use PDO;
use PDOException;
use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Config\Database;
use Softadastra\Domain\Auth\Port\UserRepositoryInterface;

class UserRepositoryPDO implements UserRepositoryInterface
{
    public $pdo;
    protected $table = 'tbl_user';
    public $id = 'id';

    public function __construct()
    {
        $this->pdo = new Database(DB_NAME, DB_HOST, DB_USER, DB_PWD);
    }

    public function save(UserEntity $user)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?");
        $query->execute([$user->getEmail()]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION['existing_email'] = $user->getEmail();
        $_SESSION['existing_full_name'] = $user->getFullName();
        $_SESSION['existing_phone'] = $user->getPhone();
        if ($result['count'] > 0) {
            $_SESSION['errors_user_email'] = "Il existe déjà un compte associé à cette adresse e-mail. <a href='/?url=auth/login'>S’identifier</a> ou <a href='/?url=a-propos'>en savoir plus.</a>";
            RedirectionHelper::redirect("auth/register");
        }
        $query = $this->pdo->getPdo()->prepare("INSERT INTO {$this->table} (full_name, email, phone, password, photo, role, status, country, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $success = $query->execute([$user->getFullName(), $user->getEmail(), $user->getPhone(), $user->getPassword(), $user->getPhoto(), $user->getRole(), $user->getStatus(), $user->getCountry()]);
        if ($success) {
            $user->setId($this->pdo->getPdo()->lastInsertId());
            return true;
        }
        return false;
    }

    public function findById($id)
    {
        $query = $this->pdo->getPdo()->prepare("
            SELECT u.*, c.country_name 
            FROM {$this->table} u
            LEFT JOIN tbl_country c ON u.country = c.country_id
            WHERE {$this->id} = :id
        ");
        $query->execute(['id' => $id]);
        $userData = $query->fetch(PDO::FETCH_ASSOC);
        if (!$userData) {
            return null;
        }
        $user = new UserEntity(
            $userData['full_name'],
            $userData['email'],
            $userData['phone'],
            $userData['password'],
            $userData['photo'],
            $userData['role'],
            $userData['status'],
            $userData['country_name']
        );

        $user->setId($userData['id']);

        return $user;
    }

    public function allUsers()
    {
        $query = $this->pdo->getPdo()->prepare("
        SELECT * FROM {$this->table}
    ");
        $query->execute();
        $userData = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach ($userData as $data) {
            $user = new UserEntity(
                $data['full_name'],
                $data['email'],
                $data['phone'],
                $data['password'],
                $data['photo'],
                $data['role'],
                $data['status'],
                $data['country']
            );
            $user->setId($data['id']);
            $users[] = $user;
        }

        return $users;
    }



    public function findAll()
    {
        $query = $this->pdo->getPdo()->query("SELECT * FROM {$this->table}");
        $usersData = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        foreach ($usersData as $userData) {
            $user = new UserEntity($userData['full_name'], $userData['email'], $userData['phone'], $userData['password'], $userData['role'], $userData['status'], $userData['country']);
            $user->setId($userData['id']);
            $users[] = $user;
        }
        return $users;
    }

    public function findOne(array $conditions)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $params = [];
        foreach ($conditions as $column => $value) {
            $sql .= "$column = ? AND ";
            $params[] = $value;
        }
        $sql = rtrim($sql, "AND ");
        $query = $this->pdo->getPdo()->prepare($sql);
        $query->execute($params);
        $userData = $query->fetch(PDO::FETCH_ASSOC);
        if (!$userData) {
            return null;
        }
        $user = new UserEntity($userData['full_name'], $userData['email'], $userData['phone'], $userData['password'], $userData['role'], $userData['status'], $userData['country']);
        $user->setId($userData['id']);
        return $user;
    }

    public function findByFullName(string $full_name)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE full_name = :full_name");
        $query->execute(['full_name' => $full_name]);
        $userData = $query->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        $user = new UserEntity($userData['full_name'], $userData['email'], $userData['phone'], $userData['password'], $userData['photo'], $userData['role'], $userData['status'], $userData['country']);
        $user->setId($userData['id']);

        return $user;
    }

    public function delete($id)
    {
        $query = $this->pdo->getPdo()->prepare("DELETE FROM {$this->table} WHERE {$this->id} = :id");
        $query->execute(['id' => $id]);
        return $query->rowCount() > 0;
    }

    public function update(UserEntity $user)
    {
        $query = $this->pdo->getPdo()->prepare("UPDATE {$this->table} SET full_name = ?, email = ?, phone = ?, password = ?,status = ?, country = ?, update_date = NOW() WHERE {$this->id} = ?");
        return $query->execute([$user->getFullName(), $user->getEmail(), $user->getPhone(), $user->getPassword(), $user->getStatus(), $user->getCountry(), $user->getId()]);
    }

    public function findByEmail($email)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $query->execute(['email' => $email]);
        $userData = $query->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        $user = new UserEntity($userData['full_name'], $userData['email'], $userData['phone'], $userData['password'], $userData['photo'], $userData['role'], $userData['status'], $userData['country']);
        $user->setId($userData['id']);

        return $user;
    }

    public function updateUser($id, $fullName, $email, $phone, $country)
    {
        $query = $this->pdo->getPdo()->prepare("
        UPDATE {$this->table} 
        SET full_name = :fullName, 
            email = :email, 
            phone = :phone, 
            country = :country 
        WHERE {$this->id} = :id
    ");

        $result = $query->execute([
            'id' => $id,
            'fullName' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'country' => $country
        ]);

        return $result;
    }


    public function updatePassword($id, $newPassword)
    {
        $query = $this->pdo->getPdo()->prepare("
            UPDATE {$this->table} 
            SET password = :password
            WHERE {$this->id} = :id
        ");

        $result = $query->execute([
            'id' => $id,
            'password' => $newPassword
        ]);

        return $result;
    }

    public function updatePhoto($filename, $id)
    {
        $statement = $this->pdo->getPdo()->prepare("UPDATE tbl_user SET photo=? WHERE id=?");
        $statement->execute([$filename, $id]);
    }
}
