<?php

namespace Softadastra\Domain\Shop\UseCase;

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Domain\Shop\Entity\ArticleEntity;
use Softadastra\Domain\Shop\Port\ArticleRepositoryInterface;
use Softadastra\Application\Image\ImageGenerator;
use Softadastra\Domain\Shop\Adapters\ArticleRepositoryPDO;

class ArticleManager
{
    protected ArticleRepositoryInterface $repository;

    public function __construct(ArticleRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(array $data, array $file)
    {
        $image = new ImageGenerator();
        $photo = $image->create($file, IMAGE_PATH . 'articles/');

        $article = new ArticleEntity($data['title'], (float)$data['price_unit'], (float)$data['wholesale_price'], (float)$data['marque'], (int)$data['quantity'], $photo, $data['description'], (int)$data['ecat_id'], $data['id_user']);
        $ecat_id = $data['ecat_id'];
        if ($article->isValid()) {
            $this->repository->save($article);
            $_SESSION['success'] = "Votre article a été ajouté avec succès.";
            RedirectionHelper::redirect("admin/users/show-formulaire/$ecat_id");
        } else {
            $_SESSION['errors'] = $article->getErrors();
            RedirectionHelper::redirect("admin/users/show-formulaire/$ecat_id");
        }
    }

    public function update(array $data, array $file, $id)
    {
        $image = new ImageGenerator();
        $articlesRepository = new ArticleRepositoryPDO();
        $articlesEntity = $articlesRepository->findById($id);
        if (!empty($file['p_featured_photo']['name'][0])) {
            $photo = $image->create($file,  IMAGE_PATH . 'articles/');
        } else {
            $photo = $articlesEntity->getImages();
        }
        $article = new ArticleEntity(
            $data['title'],
            (float)$data['price_unit'],
            (float)$data['wholesale_price'],
            (float)$data['marque'],
            (int)$data['quantity'],
            $photo,
            $data['description'],
            (int)$data['ecat_id'],
            $data['id_user']
        );
        $article->setId($id);
        $article_id = $article->getId();
        if ($article->isValid()) {
            $this->repository->update($article);
            $_SESSION['success'] = "Votre article a été modifier avec succès.";
            RedirectionHelper::redirect("admin/users/update-article/$article_id");
        } else {
            $_SESSION['errors'] = $article->getErrors();
            RedirectionHelper::redirect("admin/users/update-article/$article_id");
        }
    }
}