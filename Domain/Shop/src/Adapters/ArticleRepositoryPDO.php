<?php

namespace Softadastra\Domain\Shop\Adapters;

use Softadastra\Domain\Shop\Entity\ArticleEntity;
use Softadastra\Domain\Shop\Model\ModelRepositoryPDO;
use Softadastra\Domain\Shop\Port\ArticleRepositoryInterface;
use PDO;

class ArticleRepositoryPDO extends ModelRepositoryPDO implements ArticleRepositoryInterface
{
    protected $table = 'tbl_articles';
    protected $id = 'id';

    public function save(ArticleEntity $article)
    {
        $query = $this->pdo->getPdo()->prepare(
            "INSERT INTO {$this->table} (title, price_unit, wholesale_price, marque, quantity,images,description, ecat_id, id_user) 
            VALUES (:title, :price_unit, :wholesale_price, :marque, :quantity, :images, :description,:ecat_id, :id_user)"
        );

        $query->execute([
            'title' => $article->getTitle(),
            'price_unit' => $article->getPriceUnit(),
            'wholesale_price' => $article->getWholesalePrice(),
            'marque' => $article->getMarque(),
            'quantity' => $article->getQuantity(),
            'images' => $article->getImages(),
            'description' => $article->getDescription(),
            'ecat_id' => $article->getEcatId(),
            'id_user' => $article->getIdUser()
        ]);
    }

    public function findAll($order = 'DESC', $limit = 100)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} ORDER BY {$this->id} $order LIMIT $limit");
        $query->execute();
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);

        $articleEntities = [];
        foreach ($articles as $articleData) {
            $article = new ArticleEntity(
                $articleData['title'],
                (float)$articleData['price_unit'],
                (float)$articleData['wholesale_price'],
                $articleData['marque'],
                (int)$articleData['quantity'],
                $articleData['images'],
                $articleData['description'],
                (int)$articleData['ecat_id'],
                (int)$articleData['id_user'],
                $articleData['total_view']
            );
            $article->setId($articleData['id']);
            $article->setTitle($articleData['title']);
            $article->setPriceUnit($articleData['price_unit']);
            $article->setWholesalePrice($articleData['wholesale_price']);
            $article->setMarque($articleData['marque']);
            $article->setQuantity($articleData['quantity']);
            $article->setImages($articleData['images']);
            $article->setDescription($articleData['description']);
            $article->setEcatId($articleData['ecat_id']);
            $article->setIdUser($articleData['id_user']);
            $article->setTotalView($articleData['total_view']);
            $articleEntities[] = $article;
        }
        return $articleEntities;
    }

    public function pagination($page = 1, $perPage = 18, $order = 'DESC')
    {
        $offset = ($page - 1) * $perPage;
        $query = $this->pdo->getPdo()->prepare("
        SELECT * 
        FROM {$this->table} 
        ORDER BY {$this->id} $order 
        LIMIT :perPage OFFSET :offset
    ");
        $query->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->execute();
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);
        $articleEntities = [];
        foreach ($articles as $articleData) {
            $article = new ArticleEntity(
                $articleData['title'],
                (float)$articleData['price_unit'],
                (float)$articleData['wholesale_price'],
                $articleData['marque'],
                (int)$articleData['quantity'],
                $articleData['images'],
                $articleData['description'],
                (int)$articleData['ecat_id'],
                (int)$articleData['id_user'],
                $articleData['total_view']
            );
            $article->setId($articleData['id']);
            $articleEntities[] = $article;
        }
        return $articleEntities;
    }

    public function findById($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE {$this->id} = :id");
        $query->execute(['id' => $id]);
        $articleData = $query->fetch(PDO::FETCH_ASSOC);
        if (!$articleData) {
            return null;
        }
        $article = new ArticleEntity($articleData['title'], (float)$articleData['price_unit'], (float)$articleData['wholesale_price'], $articleData['marque'], (int)$articleData['quantity'], $articleData['images'], $articleData['description'], (int)$articleData['ecat_id'], (int)$articleData['id_user'], $articleData['total_view']);

        $article->setId($articleData['id']);
        $article->setTitle($articleData['title']);
        $article->setPriceUnit($articleData['price_unit']);
        $article->setWholesalePrice($articleData['wholesale_price']);
        $article->setMarque($articleData['marque']);
        $article->setQuantity($articleData['quantity']);
        $article->setImages($articleData['images']);
        $article->setDescription($articleData['description']);
        $article->setEcatId($articleData['ecat_id']);
        $article->setIdUser($articleData['id_user']);
        $article->setTotalView($articleData['total_view']);

        return $article;
    }

    public function findOne(array $conditions)
    {
        $whereClause = '';
        $params = [];
        foreach ($conditions as $key => $value) {
            $whereClause .= "$key = :$key AND ";
            $params[$key] = $value;
        }
        $whereClause = rtrim($whereClause, 'AND ');

        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE $whereClause");
        $query->execute($params);
        $articleData = $query->fetch(PDO::FETCH_ASSOC);

        if (!$articleData) {
            return null;
        }
        $article = new ArticleEntity($articleData['title'], (float)$articleData['price_unit'], (float)$articleData['wholesale_price'], $articleData['marque'], (int)$articleData['quantity'], $articleData['images'], $articleData['description'],  (int)$articleData['ecat_id'], (int)$articleData['id_user'], $articleData['total_view']);
        $article->setId($articleData['id']);
        $article->setTitle($articleData['title']);
        $article->setPriceUnit($articleData['price_unit']);
        $article->setWholesalePrice($articleData['wholesale_price']);
        $article->setMarque($articleData['marque']);
        $article->setQuantity($articleData['quantity']);
        $article->setImages($articleData['images']);
        $article->setDescription($articleData['description']);
        $article->setEcatId($articleData['ecat_id']);
        $article->setIdUser($articleData['id_user']);
        $article->setTotalView($articleData['total_view']);
        return $article;
    }

    public function delete($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT images FROM {$this->table} WHERE {$this->id} = :id");
        $query->execute(['id' => $id]);
        $articleData = $query->fetch(PDO::FETCH_ASSOC);

        if ($articleData && !empty($articleData['images'])) {
            $photoPaths = explode(',', $articleData['images']);
            foreach ($photoPaths as $photoPath) {
                $photoPath = trim($photoPath);
                if (!empty($photoPath) && file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
        }
        $query = $this->pdo->getPdo()->prepare("DELETE FROM {$this->table} WHERE {$this->id} = :id");
        $query->execute(['id' => $id]);
        return $query->rowCount() > 0;
    }

    public function findSimilarArticles(ArticleEntity $article, $limit = 5, $similarityCriteria = [])
    {
        $query = "SELECT * FROM {$this->table} 
                  WHERE {$this->id} != :id ";
        $params = ['id' => $article->getId()];
        foreach ($similarityCriteria as $key => $value) {
            $query .= " AND $key = :$key ";
            $params[$key] = $value;
        }
        $query .= " LIMIT $limit";
        $stmt = $this->pdo->getPdo()->prepare($query);
        $stmt->execute($params);
        $similarArticles = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $similarArticle = new ArticleEntity(
                $row['title'],
                $row['price_unit'],
                $row['wholesale_price'],
                $row['marque'],
                $row['quantity'],
                $row['images'],
                $row['description'],
                $row['ecat_id'],
                $row['id_user'],
                $row['total_view']
            );
            $similarArticle->setId($row['id']);
            $similarArticles[] = $similarArticle;
        }

        return $similarArticles;
    }


    public function update(ArticleEntity $article)
    {
        $query = $this->pdo->getPdo()->prepare(
            'UPDATE ' . $this->table . ' 
            SET title = :title, 
                price_unit = :price_unit, 
                wholesale_price = :wholesale_price, 
                marque = :marque, 
                quantity = :quantity, 
                images = :images, 
                description = :description, 
                ecat_id = :ecat_id 
            WHERE id = :id'
        );

        $query->execute([
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'price_unit' => $article->getPriceUnit(),
            'wholesale_price' => $article->getWholesalePrice(),
            'marque' => $article->getMarque(),
            'quantity' => $article->getQuantity(),
            'images' => $article->getImages(),
            'description' => $article->getDescription(),
            'ecat_id' => $article->getEcatId(),
        ]);
        return $query->rowCount() > 0;
    }

    public function update_view($totalViews, $id)
    {
        $statement = $this->pdo->getPdo()->prepare("UPDATE {$this->table} SET total_view = ? WHERE id = ?");
        $statement->execute(array($totalViews, $id));
    }

    public function findByEcatId($ecatId)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE ecat_id = :ecat_id");
        $query->execute(['ecat_id' => $ecatId]);
        $articlesData = $query->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($articlesData as $articleData) {
            $article = new ArticleEntity(
                $articleData['title'],
                (float)$articleData['price_unit'],
                (float)$articleData['wholesale_price'],
                $articleData['marque'],
                (int)$articleData['quantity'],
                $articleData['images'],
                $articleData['description'],
                (int)$articleData['ecat_id'],
                (int)$articleData['id_user'],
                $articleData['total_view']
            );
            $article->setId($articleData['id']);
            $articles[] = $article;
        }
        return $articles;
    }

    public function findArticlesByEndCategories($endCategories)
    {
        $ecatIds = [];
        foreach ($endCategories as $endCategory) {
            $ecatIds[] = $endCategory->getEcatId();
        }
        $ecatIdString = implode(',', $ecatIds);
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE ecat_id IN ($ecatIdString)");
        $query->execute();
        $articlesData = $query->fetchAll(PDO::FETCH_ASSOC);
        $articles = [];
        foreach ($articlesData as $articleData) {
            $article = new ArticleEntity(
                $articleData['title'],
                (float)$articleData['price_unit'],
                (float)$articleData['wholesale_price'],
                $articleData['marque'],
                (int)$articleData['quantity'],
                $articleData['images'],
                $articleData['description'],
                (int)$articleData['ecat_id'],
                (int)$articleData['id_user'],
                $articleData['total_view']
            );
            $article->setId($articleData['id']);
            $articles[] = $article;
        }
        return $articles;
    }

    public function findByUserId($userId)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE id_user = :id_user ORDER BY id DESC");
        $query->execute(['id_user' => $userId]);
        $articlesData = $query->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($articlesData as $articleData) {
            $article = new ArticleEntity(
                $articleData['title'],
                (float)$articleData['price_unit'],
                (float)$articleData['wholesale_price'],
                $articleData['marque'],
                (int)$articleData['quantity'],
                $articleData['images'],
                $articleData['description'],
                (int)$articleData['ecat_id'],
                (int)$articleData['id_user'],
                (int)$articleData['total_view']
            );
            $article->setId($articleData['id']);
            $articles[] = $article;
        }
        return $articles;
    }


    public function findOrderedArticlesBySellerId($sellerId)
    {
        $query = $this->pdo->getPdo()->prepare("
            SELECT a.*, o.*, od.quantity, u.full_name as client_name, u.phone as client_phone
            FROM tbl_articles a
            INNER JOIN tbl_order_details od ON a.id = od.article_id
            INNER JOIN tbl_order o ON od.order_id = o.order_id
            INNER JOIN tbl_user u ON o.user_id = u.id
            WHERE a.id_user = :seller_id
        ");
        $query->execute(['seller_id' => $sellerId]);
        $orderedArticlesData = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!$orderedArticlesData) {
            return null;
        }
        $orderedArticles = [];
        foreach ($orderedArticlesData as $articleData) {
            $orderedArticle = new ArticleEntity(
                $articleData['title'],
                (float)$articleData['price_unit'],
                (float)$articleData['wholesale_price'],
                $articleData['marque'],
                (int)$articleData['quantity'],
                $articleData['images'],
                $articleData['description'],
                (int)$articleData['ecat_id'],
                (int)$articleData['id_user'],
                $articleData['total_view']
            );
            $orderedArticle->setId($articleData['id']);
            $orderDetails = [
                'order_id' => $articleData['order_id'],
                'order_date' => $articleData['order_date'],
                'client_name' => $articleData['client_name'],
                'client_phone' => $articleData['client_phone'],
                'total_amount' => $articleData['total_amount'],
                'shipping_address' => $articleData['shipping_address'],
                'order_status' => $articleData['order_status']
            ];
            $orderedArticle->setOrderDetails($orderDetails);
            $orderedArticles[] = $orderedArticle;
        }
        return $orderedArticles;
    }

    public function findOrderedArticlesByUserId2($id)
    {
        $query = $this->pdo->getPdo()->prepare("
        SELECT a.*, u.full_name as client_name, u.phone as client_phone
        FROM tbl_articles a
        INNER JOIN tbl_order_details od ON a.id = od.article_id
        INNER JOIN tbl_order o ON od.order_id = o.order_id
        INNER JOIN tbl_user u ON o.user_id = u.id
        WHERE o.user_id = :id
    ");
        $query->execute(['id' => $id]);
        $orderedArticlesData = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!$orderedArticlesData) {
            return null;
        }
        $orderedArticles = [];
        foreach ($orderedArticlesData as $articleData) {
            $orderedArticle = new ArticleEntity(
                $articleData['title'],
                (float)$articleData['price_unit'],
                (float)$articleData['wholesale_price'],
                $articleData['marque'],
                (int)$articleData['quantity'],
                $articleData['images'],
                $articleData['description'],
                (int)$articleData['ecat_id'],
                (int)$articleData['id_user'],
                $articleData['total_view']
            );
            $orderedArticle->setId($articleData['id']);
            $orderedArticles[] = $orderedArticle;
        }
        return $orderedArticles;
    }

    public function findOrderedArticlesWithOrderDetailsByUserId($userId)
    {
        $query = $this->pdo->getPdo()->prepare("
        SELECT a.*, od.quantity as order_quantity, o.*, u.full_name as client_name, s.full_name as seller_name,s.phone as telephone
        FROM tbl_articles a
        INNER JOIN tbl_order_details od ON a.id = od.article_id
        INNER JOIN tbl_order o ON od.order_id = o.order_id
        INNER JOIN tbl_user u ON o.user_id = u.id
        INNER JOIN tbl_user s ON a.id_user = s.id
        WHERE o.user_id = :user_id
        ORDER BY o.order_date DESC
        ");

        $query->execute(['user_id' => $userId]);
        $orderedArticlesData = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!$orderedArticlesData) {
            return null;
        }
        $orderedArticles = [];
        foreach ($orderedArticlesData as $articleData) {
            $orderedArticle = new ArticleEntity(
                $articleData['title'],
                (float)$articleData['price_unit'],
                (float)$articleData['wholesale_price'],
                $articleData['marque'],
                (int)$articleData['order_quantity'],
                $articleData['images'],
                $articleData['description'],
                (int)$articleData['ecat_id'],
                (int)$articleData['id_user'],
                $articleData['total_view']
            );
            $orderedArticle->setId($articleData['id']);
            $orderDetails = [
                'order_id' => $articleData['order_id'],
                'order_date' => $articleData['order_date'],
                'user_id' => $articleData['user_id'],
                'full_name' => $articleData['client_name'],
                'seller_name' => $articleData['seller_name'],
                'telephone' => $articleData['telephone'],
                'total_amount' => $articleData['total_amount'],
                'shipping_address' => $articleData['shipping_address'],
                'order_status' => $articleData['order_status']
            ];
            $orderedArticle->setOrderDetails($orderDetails);
            $orderedArticle->setSellerName($articleData['seller_name']);
            $orderedArticle->setTelephone($articleData['telephone']);
            $orderedArticles[] = $orderedArticle;
        }
        return $orderedArticles;
    }

    public function searchArticles($keyword, $order = 'DESC', $limit = 100)
    {
        $query = $this->pdo->getPdo()->prepare("
            SELECT * 
            FROM {$this->table} 
            WHERE title LIKE :keyword 
                OR description LIKE :keyword 
            ORDER BY {$this->id} $order 
            LIMIT $limit
        ");
        $keyword = '%' . $keyword . '%';
        $query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $query->execute();
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);
        $articleEntities = [];
        foreach ($articles as $articleData) {
            $article = new ArticleEntity(
                $articleData['title'],
                (float)$articleData['price_unit'],
                (float)$articleData['wholesale_price'],
                $articleData['marque'],
                (int)$articleData['quantity'],
                $articleData['images'],
                $articleData['description'],
                (int)$articleData['ecat_id'],
                (int)$articleData['id_user'],
                $articleData['total_view']
            );
            $article->setId($articleData['id']);
            $articleEntities[] = $article;
        }
        return $articleEntities;
    }
}
