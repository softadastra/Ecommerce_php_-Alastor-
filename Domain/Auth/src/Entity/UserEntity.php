<?php

namespace Softadastra\Domain\Auth\Entity;

class UserEntity
{
    private ?int $id;
    private string $fullName = "";
    private string $email;
    private $phone = "";
    private string $password;
    private string $photo;
    private string $role;
    private string $status;
    private $country;
    private $date_creation;
    private $update_date;

    private array $errors = [];

    public function __construct(string $full_name, string $email, $phone, string $password, string $photo, string $role = 'user', string $status = 'active', $country = 1)
    {
        $this->setFullName($full_name);
        $this->setEmail($email);
        $this->setPhone($phone);
        $this->setPassword($password);
        $this->setPhoto($photo);
        $this->setRole($role);
        $this->setStatus($status);
        $this->setCountry($country);
        $this->setDateCreation(new \DateTime());
        $this->setUpdateDate(new \DateTime());
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): void
    {
        if ($fullName === null || empty($fullName)) {
            $this->errors['full_name'] = "Veuillez compléter votre nom complet";
            return;
        }
        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $fullName)) {
            $this->errors['full_name'] = "Le nom complet ne peut contenir que des lettres avec des chiffres ou des lettres uniquement";
            return;
        }

        $this->fullName = $fullName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            $this->errors['email'] = "L'adresse e-mail n'est pas valide.";
            return;
        }
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        if (preg_match('/^\+\d{1,3}\d{9}$/', $phone)) {
            $this->phone = $phone;
        } else {
            $this->errors['phone'] = "Le numéro de téléphone n'est pas valide. Veuillez inclure le code pays.";
            return;
        }
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        if (strlen($password) < 6) {
            $this->errors['password'] = "Le mot de passe doit contenir au moins 6 caractères.";
            return;
        }
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            $this->errors['password'] = "Le mot de passe doit contenir à la fois des lettres et des chiffres.";
            return;
        }
        $this->password = $password;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): void
    {
        if (empty($photo)) {
            $this->photo =  IMAGE_PATH . "/photo-profil/avatar.png";
            return;
        } else {
            $this->photo = $photo;
        }
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country): void
    {
        $this->country = $country;
    }

    public function getDateCreation()
    {
        return $this->date_creation;
    }

    public function setDateCreation($date_creation): void
    {
        $this->date_creation = $date_creation;
    }

    public function getUpdateDate(): ?\DateTime
    {
        return $this->update_date;
    }

    public function setUpdateDate(\DateTime $update_date): void
    {
        $this->update_date = $update_date;
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
