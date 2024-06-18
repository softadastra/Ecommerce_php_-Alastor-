<?php

namespace Softadastra\Domain\Shop\Adapters;

use Softadastra\Domain\Shop\Entity\TopCategoryEntity;
use Softadastra\Domain\Shop\Model\ModelRepositoryPDO;
use Softadastra\Domain\Shop\Port\TopCategoryRepositoryInterface;
use PDO;
use Softadastra\Domain\Shop\Entity\MidCategoryEntity;

class TopCategoryRepositoryPDO extends ModelRepositoryPDO implements TopCategoryRepositoryInterface
{
    protected $table = 'tbl_top_category';
    protected $id = 'tcat_id';

    public function save(TopCategoryEntity $top_category)
    {
        $query = $this->pdo->getPdo()->prepare(
            "INSERT INTO {$this->table}(tcat_name,image) VALUES(:tcat_name, :image)"
        );
        $query->execute([
            'tcat_name' => $top_category->getName(),
            'image' => $top_category->getImage()
        ]);
    }

    public function findAll()
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} ORDER BY {$this->id} ASC");
        $query->execute();
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);
        $topCategories = [];
        foreach ($articles as $topCategoryData) {
            $topCategory = new TopCategoryEntity(
                $topCategoryData['tcat_name'],
                $topCategoryData['image']
            );
            $topCategory->setId($topCategoryData['tcat_id']);
            $topCategories[] = $topCategory;
        }
        return $topCategories;
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
        $result = $query->fetchObject(TopCategoryEntity::class);
        return $result ? $result : null;
    }

    public function findById($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE {$this->id} = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $topCategoryData = $query->fetch(PDO::FETCH_ASSOC);
        if (!$topCategoryData) {
            return null;
        }
        $topCategory = new TopCategoryEntity(
            $topCategoryData['tcat_name'],
            $topCategoryData['image']
        );
        $topCategory->setId($topCategoryData['tcat_id']);
        $topCategory->setName($topCategoryData['tcat_name']);
        $topCategory->setImages($topCategoryData['image']);
        return $topCategory;
    }

    public function delete($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT image FROM {$this->table} WHERE {$this->id} = :id");
        $query->execute(['id' => $id]);
        $articleData = $query->fetch(PDO::FETCH_ASSOC);
        if ($articleData && !empty($articleData['image'])) {
            $photoPaths = explode(',', $articleData['image']);
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

    public function update(TopCategoryEntity $top_category)
    {
        $query = $this->pdo->getPdo()->prepare(
            "UPDATE {$this->table} 
        SET tcat_name = :tcat_name, image = :image 
        WHERE tcat_id = :tcat_id"
        );
        return $query->execute([
            'tcat_id' => $top_category->getId(),
            'tcat_name' => $top_category->getName(),
            'image' => $top_category->getImage()
        ]);
    }

    public function findMidCategoriesByTopCategory($topCategoryName)
    {
        $query = $this->pdo->getPdo()->prepare("
        SELECT m.mcat_id, m.mcat_name, m.tcat_id, m.mid_image
        FROM tbl_mid_category m
        JOIN tbl_top_category t ON m.tcat_id = t.tcat_id
        WHERE t.tcat_name = :topCategoryName
        ORDER BY m.mcat_id ASC
    ");
        $query->bindParam(':topCategoryName', $topCategoryName);
        $query->execute();
        $midCategories = $query->fetchAll(PDO::FETCH_ASSOC);

        $midCategoryEntities = [];
        foreach ($midCategories as $midCategoryData) {
            $midCategory = new MidCategoryEntity(
                $midCategoryData['mcat_name'],
                $midCategoryData['tcat_id'],
                $midCategoryData['mid_image']
            );
            $midCategory->setMcatId($midCategoryData['mcat_id']);
            $midCategoryEntities[] = $midCategory;
        }
        return $midCategoryEntities;
    }
}
