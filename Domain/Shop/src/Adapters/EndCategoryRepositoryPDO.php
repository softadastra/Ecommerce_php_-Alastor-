<?php

namespace Softadastra\Domain\Shop\Adapters;

use Softadastra\Domain\Shop\Entity\EndCategoryEntity;
use Softadastra\Domain\Shop\Model\ModelRepositoryPDO;
use Softadastra\Domain\Shop\Port\EndCategoryRepositoryInterface;
use PDO;

class EndCategoryRepositoryPDO extends ModelRepositoryPDO implements EndCategoryRepositoryInterface
{
    protected $table = 'tbl_end_category';
    protected $id = 'ecat_id';

    public function save(EndCategoryEntity $end_category)
    {
        $query = $this->pdo->getPdo()->prepare(
            "INSERT INTO {$this->table}(ecat_name, mcat_id, end_image) VALUES(:ecat_name, :mcat_id, :end_image)"
        );
        $query->execute([
            'ecat_name' => $end_category->getName(),
            'mcat_id' => $end_category->getMidId(),
            'end_image' => $end_category->getImage()
        ]);
    }

    public function findAll()
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} ORDER BY {$this->id} DESC");
        $query->execute();
        $end_category_data = $query->fetchAll(PDO::FETCH_ASSOC);

        $endCategoryEntities = [];
        foreach ($end_category_data as $endCategoryData) {
            $endCategory = new EndCategoryEntity(
                $endCategoryData['ecat_name'],
                $endCategoryData['mcat_id'],
                $endCategoryData['end_image']
            );
            $endCategory->setEcatId($endCategoryData['ecat_id']);
            $endCategory->setMidId($endCategoryData['mcat_id']);
            $endCategory->setName($endCategoryData['ecat_name']);
            $endCategory->setImage($endCategoryData['end_image']);
            $endCategoryEntities[] = $endCategory;
        }

        return $endCategoryEntities;
    }

    public function findAllWithArticle(?string $order = "", ?int $limit = null)
    {
        $query = "SELECT * FROM {$this->table} WHERE ecat_id IN (SELECT DISTINCT ecat_id FROM tbl_articles)";
        if (!empty($order)) {
            $query .= " ORDER BY {$order}";
        } else {
            $query .= " ORDER BY {$this->id} DESC";
        }
        if (!is_null($limit)) {
            $query .= " LIMIT {$limit}";
        }
        $stmt = $this->pdo->getPdo()->prepare($query);
        $stmt->execute();
        $end_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $endCategoryEntities = [];
        foreach ($end_categories as $endCategoryData) {
            $endCategory = new EndCategoryEntity(
                $endCategoryData['ecat_name'],
                $endCategoryData['mcat_id'],
                $endCategoryData['end_image']
            );
            $endCategory->setEcatId($endCategoryData['ecat_id']);
            $endCategory->setMidId($endCategoryData['mcat_id']);
            $endCategory->setName($endCategoryData['ecat_name']);
            $endCategory->setImage($endCategoryData['end_image']);
            $endCategoryEntities[] = $endCategory;
        }
        return $endCategoryEntities;
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
            $entity = new EndCategoryEntity($rowData['ecat_name'], $rowData['mcat_id'], $rowData['end_image']);
            $entity->setEcatId($rowData['ecat_id']);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function findById($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE {$this->id} = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $endCategoryData = $query->fetch(PDO::FETCH_ASSOC);
        if (!$endCategoryData) {
            return null;
        }
        $endCategory = new EndCategoryEntity(
            $endCategoryData['ecat_name'],
            $endCategoryData['mcat_id'],
            $endCategoryData['end_image']
        );
        $endCategory->setEcatId($endCategoryData['ecat_id']);
        return $endCategory;
    }

    public function delete($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT end_image FROM {$this->table} WHERE {$this->id} = :id");
        $query->execute(['id' => $id]);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if ($data && !empty($data['end_image'])) {
            $photoPaths = explode(',', $data['end_image']);
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

    public function update(EndCategoryEntity $end_category)
    {
        $query = $this->pdo->getPdo()->prepare(
            "UPDATE {$this->table} SET ecat_name = :ecat_name, mcat_id = :mcat_id, end_image = :end_image WHERE ecat_id = :ecat_id"
        );
        $query->execute([
            'ecat_id' => $end_category->getEcatId(),
            'ecat_name' => $end_category->getName(),
            'mcat_id' => $end_category->getMidId(),
            'end_image' => $end_category->getImage()
        ]);
    }

    public function findByMidCategoryId($midCategoryId)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE mcat_id = :mcat_id ORDER BY {$this->id} ASC");
        $query->execute(['mcat_id' => $midCategoryId]);
        $endCategoryData = $query->fetchAll(PDO::FETCH_ASSOC);
        $endCategories = [];
        foreach ($endCategoryData as $endCategoryDatum) {
            $endCategory = new EndCategoryEntity(
                $endCategoryDatum['ecat_name'],
                $endCategoryDatum['mcat_id'],
                $endCategoryDatum['end_image']
            );
            $endCategory->setEcatId($endCategoryDatum['ecat_id']);
            $endCategories[] = $endCategory;
        }
        return $endCategories;
    }

    public function allEndCategory($midCategoryId, $excludedEcatId)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE mcat_id = :mcat_id AND ecat_id != :excluded_ecat_id");
        $query->execute(['mcat_id' => $midCategoryId, 'excluded_ecat_id' => $excludedEcatId]);
        $endCategoryData = $query->fetchAll(PDO::FETCH_ASSOC);
        $endCategories = [];
        foreach ($endCategoryData as $endCategoryDatum) {
            $endCategory = new EndCategoryEntity(
                $endCategoryDatum['ecat_name'],
                $endCategoryDatum['mcat_id'],
                $endCategoryDatum['end_image']
            );
            $endCategory->setEcatId($endCategoryDatum['ecat_id']);
            $endCategories[] = $endCategory;
        }
        return $endCategories;
    }
}
