<?php

namespace Softadastra\Domain\Shop\UseCase;

use Softadastra\Domain\Shop\Entity\TopCategoryEntity;
use Softadastra\Domain\Shop\Port\TopCategoryRepositoryInterface;
use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageGenerator;
use Softadastra\Domain\Shop\Adapters\TopCategoryRepositoryPDO;

class TopCategoryManager
{
    protected TopCategoryRepositoryInterface $repository;

    public function __construct(TopCategoryRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(array $data, array $file)
    {
        $images = new ImageGenerator();
        $photo = $images->create($file,  IMAGE_PATH . 'topCategory/');

        $top_category = new TopCategoryEntity($data['tcat_name'], $photo);
        if ($top_category->isValid()) {
            $this->repository->save($top_category);
            $_SESSION['success_top_category'] = "Categorie ajouter avec succes";
            RedirectionHelper::redirect("admin/top-category");
        } else {
            $_SESSION['errors_top_category'] = $top_category->getErrors();
            RedirectionHelper::redirect("admin/top-category");
        }
    }

    public function update(array $data, array $file, $id)
    {
        $image = new ImageGenerator();
        $topCategoryRepository = new TopCategoryRepositoryPDO();
        $topCategoryEntity = $topCategoryRepository->findById($id);
        if (!empty($file['p_featured_photo']['name'][0])) {
            $photo = $image->create($file,  IMAGE_PATH . 'topCategory/');
        } else {
            $photo = $topCategoryEntity->getImage();
        }
        $category = new TopCategoryEntity(
            $data['tcat_name'],
            $photo
        );
        $category->setId($id);
        $category_id = $category->getId();
        if ($category->isValid()) {
            $this->repository->update($category);
            $_SESSION['success'] = "Votre category a été modifier avec succès.";
            RedirectionHelper::redirect("admin/edit-top-category/$category_id");
        } else {
            $_SESSION['errors'] = $category->getErrors();
            RedirectionHelper::redirect("admin/edit-top-category/$category_id");
        }
    }
}
