<?php

namespace Softadastra\Domain\Shop\Adapters;

use Softadastra\Domain\Shop\Entity\MidCategoryEntity;
use Softadastra\Domain\Shop\Model\ModelRepositoryPDO;
use Softadastra\Domain\Shop\Port\MidCategoryRepositoryInterface;
use PDO;
use Softadastra\Domain\Shop\Entity\ArticleEntity;

class MidCategoryRepositoryPDO extends ModelRepositoryPDO implements MidCategoryRepositoryInterface
{
    protected $table = 'tbl_mid_category';
    protected $id = 'mcat_id';

    public function save(MidCategoryEntity $mid_category)
    {
        $query = $this->pdo->getPdo()->prepare(
            "INSERT INTO {$this->table}(mcat_name, tcat_id, mid_image) VALUES(:mcat_name, :tcat_id, :mid_image)"
        );
        $query->execute([
            'mcat_name' => $mid_category->getMcatName(),
            'tcat_id' => $mid_category->getTcatId(),
            'mid_image' => $mid_category->getMidImage()
        ]);
    }

    public function findAll()
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} ORDER BY {$this->id} DESC");
        $query->execute();
        $mid_category_data = $query->fetchAll(PDO::FETCH_ASSOC);

        $midCategoryEntities = [];
        foreach ($mid_category_data as $midCategoryData) {
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

    public function findOne(array $conditions)
    {
        $whereClause = '';
        $params = [];
        foreach ($conditions as $key => $value) {
            $whereClause .= "$key = :$key AND ";
            $params[":$key"] = $value;
        }
        $whereClause = rtrim($whereClause, 'AND ');

        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE $whereClause");
        $query->execute($params);

        $rowsData = $query->fetchAll(PDO::FETCH_ASSOC);
        $entities = [];

        foreach ($rowsData as $rowData) {
            $entity = new MidCategoryEntity($rowData['mcat_name'], $rowData['tcat_id'], $rowData['mid_image']);
            $entity->setMcatId($rowData['mcat_id']);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function findById($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE mcat_id = :mcat_id ORDER BY {$this->id} DESC");
        $query->execute(['mcat_id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT mid_image FROM {$this->table} WHERE {$this->id} = :id");
        $query->execute(['id' => $id]);
        $articleData = $query->fetch(PDO::FETCH_ASSOC);

        if ($articleData && !empty($articleData['mid_image'])) {
            $photoPaths = explode(',', $articleData['mid_image']);
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

    public function update(MidCategoryEntity $mid_category)
    {
        $query = $this->pdo->getPdo()->prepare(
            "UPDATE {$this->table} SET mcat_name = :mcat_name, tcat_id = :tcat_id, mid_image = :mid_image WHERE mcat_id = :mcat_id"
        );
        $query->execute([
            'mcat_id' => $mid_category->getMcatId(),
            'mcat_name' => $mid_category->getMcatName(),
            'tcat_id' => $mid_category->getTcatId(),
            'mid_image' => $mid_category->getMidImage()
        ]);
    }

    public function findByTopCategoryId($topCategoryId)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE tcat_id = :tcat_id ORDER BY {$this->id} DESC");
        $query->execute(['tcat_id' => $topCategoryId]);
        $midCategoryData = $query->fetchAll(PDO::FETCH_ASSOC);
        $midCategories = [];
        foreach ($midCategoryData as $midCategoryDatum) {
            $midCategory = new MidCategoryEntity(
                $midCategoryDatum['mcat_name'],
                $midCategoryDatum['tcat_id'],
                $midCategoryDatum['mid_image']
            );
            $midCategory->setMcatId($midCategoryDatum['mcat_id']);
            $midCategories[] = $midCategory;
        }
        return $midCategories;
    }

    public function findArticlesByMidCategoryId($midCategoryId)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT a.* FROM tbl_articles a JOIN tbl_end_category e ON a.ecat_id = e.ecat_id WHERE e.mcat_id = :mcat_id");
        $query->execute(['mcat_id' => $midCategoryId]);
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
                (int)$articleData['id_user']
            );
            $article->setId($articleData['id']);
            $articles[] = $article;
        }
        return $articles;
    }
}
