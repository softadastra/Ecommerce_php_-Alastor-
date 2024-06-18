<?php

namespace Softadastra\Domain\Shop\Entity;

class ArticleEntity extends OrderArticles
{
    private ?int $id;
    private string $title;
    private float $price_unit;
    private float $wholesale_price;
    private string $marque;
    private int $quantity;
    private string $images;
    private string $description;
    private int $ecat_id;
    private int $id_user;
    private int $total_view;

    private array $errors = [];

    public function __construct(string $title, float $price_unit, float $wholesale_price, string $marque, int $quantity, string $images, string $description, int $ecat_id, int $id_user, int $total_view = 0)
    {
        $this->setTitle($title);
        $this->setPriceUnit($price_unit);
        $this->setWholesalePrice($wholesale_price);
        $this->setMarque($marque);
        $this->setQuantity($quantity);
        $this->setImages($images);
        $this->setDescription($description);
        $this->setEcatId($ecat_id);
        $this->setIdUser($id_user);
        $this->setTotalView($total_view);
    }

    public static function createFromArray(array $data)
    {
        return new self(
            $data['id'] ?? null,
            $data['title'] ?? null,
            isset($data['price_unit']) ? (float)$data['price_unit'] : null,
            isset($data['wholesale_price']) ? (float)$data['wholesale_price'] : null,
            $data['marque'] ?? null,
            $data['quantity'] ?? null,
            $data['size_id'] ?? null,
            $data['color_id'] ?? null,
            $data['state_id'] ?? null,
            $data['transaction_id'] ?? null,
            $data['delivery_id'] ?? null,
            $data['payment_id'] ?? null,
            $data['localisation'] ?? null,
            $data['images'] ?? null,
            $data['description'] ?? null,
            $data['is_occasion'] ?? null,
            $data['ecat_id'] ?? null,
            $data['id_user'] ?? null
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPriceUnit(): float
    {
        return $this->price_unit;
    }

    public function getWholesalePrice(): float
    {
        return $this->wholesale_price;
    }

    public function getMarque(): string
    {
        return $this->marque;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getImages(): string
    {
        return $this->images;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEcatId(): int
    {
        return $this->ecat_id;
    }

    public function getTotalView(): int
    {
        return $this->total_view;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function isValid()
    {
        return empty($this->getErrors());
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        if (empty($title)) {
            $this->errors['title'] = "Vous devez compléter un titre";
            return;
        }
        if (!preg_match('/[a-zA-Z0-9]/', $title)) {
            $this->errors['title'] = "Le titre doit contenir au moins une lettre ou un chiffre";
            return;
        }

        $this->title = $title;
    }

    public function setPriceUnit(float $price_unit): void
    {
        if ($price_unit <= 0) {
            $this->errors['price_unit'] = "Le prix unitaire doit être supérieur à zéro";
            return;
        }
        if (!preg_match('/^\d+(\.\d+)?$/', $price_unit)) {
            $this->errors['price_unit'] = "Le prix unitaire doit être un nombre positif";
            return;
        }

        $this->price_unit = $price_unit;
    }

    public function setWholesalePrice(?float $wholesale_price): void
    {
        if ($wholesale_price !== null && !preg_match('/^\d+(\.\d+)?$/', $wholesale_price)) {
            $this->errors['wholesale_price'] = "Le prix de gros doit être un nombre";
            return;
        }

        $this->wholesale_price = $wholesale_price;
    }


    public function setMarque(?float $marque): void
    {
        if ($marque <= 0) {
            $this->errors['marque'] = "Le prix doit être supérieur à zéro";
            return;
        }
        if (!preg_match('/^\d+(\.\d+)?$/', $marque)) {
            $this->errors['marque'] = "Le prix doit être un nombre positif";
            return;
        }

        $this->marque = $marque;
    }

    public function setQuantity(int $quantity): void
    {
        if (!preg_match('/^[1-9]\d*$/', $quantity)) {
            $this->errors['quantity'] = "La quantité doit être un nombre entier supérieur à zéro";
            return;
        }

        $this->quantity = $quantity;
    }

    public function setImages(string $images): void
    {
        if (empty($images)) {
            $this->errors['images'] = "Veillez ajouter des images de l'article";
            return;
        }
        $this->images = $images;
    }
    public function setDescription(?string $description): void
    {
        if ($description === null || strlen($description) <= 15) {
            $this->errors['description'] = "Veuillez entrer une description contenant plus de 15 caractères";
            return;
        }
        if (empty(trim($description))) {
            $this->errors['description'] = "La description ne peut pas être vide";
            return;
        }

        $this->description = $description;
    }

    public function setEcatId(int $ecat_id): void
    {
        if (empty($ecat_id)) {
            $this->errors['ecat_id'] = "Veillez choisir les categories";
            return;
        }
        $this->ecat_id = $ecat_id;
    }

    public function setIdUser(int $id)
    {
        $this->id_user = $id;
    }

    public function setTotalView(int $id)
    {
        $this->total_view = $id;
    }

    public function timeElapsed($date)
    {
        $now = time();
        $date = strtotime($date);
        $diff = $now - $date;

        if ($diff < 60) {
            return $diff . " secondes";
        } elseif ($diff < 3600) {
            return round($diff / 60) . " minutes";
        } elseif ($diff < 86400) {
            return round($diff / 3600) . " heures";
        } elseif ($diff < 2592000) {
            return round($diff / 86400) . " jours";
        } elseif ($diff < 31536000) {
            return round($diff / 2592000) . " mois";
        } else {
            return round($diff / 31536000) . " années";
        }
    }
}
