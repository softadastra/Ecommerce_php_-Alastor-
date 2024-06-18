<?php

namespace Softadastra\Domain\Shop\Port;

use Softadastra\Domain\Shop\Entity\ArticleEntity;

interface ArticleRepositoryInterface
{
    public function save(ArticleEntity $article);
    public function findAll();
    public function findById($id);
    public function findOne(array $conditions);
    public function delete($id);
    public function update(ArticleEntity $article);
    public function findSimilarArticles(ArticleEntity $article, $limit = 5);
}
