<?php

namespace Softadastra\Domain\Shop\UseCase;

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageGenerator;
use Softadastra\Domain\Shop\Adapters\EndCategoryRepositoryPDO;
use Softadastra\Domain\Shop\Entity\EndCategoryEntity;
use Softadastra\Domain\Shop\Port\EndCategoryRepositoryInterface;

class EndCategoryManager
{
    protected EndCategoryRepositoryInterface $repository;

    public function __construct(EndCategoryRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(array $data, array $file)
    {
        $images = new ImageGenerator();
        $photo = $images->create($file,  IMAGE_PATH . 'endCategory/');

        $end_category = new EndCategoryEntity($data['ecat_name'], $data['mcat_id'], $photo);
        if ($end_category->isValid()) {
            $this->repository->save($end_category);
            $_SESSION['success_end_category'] = "Categorie ajouter avec succes";
            RedirectionHelper::redirect("admin/end-category");
        } else {
            $_SESSION['errors_end_category'] = $end_category->getErrors();
            RedirectionHelper::redirect("admin/end-category");
        }
    }

    public function update(array $data, array $file, $id)
    {
        $image = new ImageGenerator();
        $endCategoryRepository = new EndCategoryRepositoryPDO();
        $endCategoryEntity = $endCategoryRepository->findById($id);
        if (!empty($file['p_featured_photo']['name'][0])) {
            $photo = $image->create($file,  IMAGE_PATH . 'endCategory/');
        } else {
            $photo = $endCategoryEntity->getImage();
        }
        $category = new EndCategoryEntity(
            $data['ecat_name'],
            $data['mcat_id'],
            $photo
        );
        $category->setEcatId($id);
        $category_id = $category->getEcatId();
        if ($category->isValid()) {
            $this->repository->update($category);
            $_SESSION['success'] = "Votre category a été modifier avec succès.";
            RedirectionHelper::redirect("admin/edit-end-category/$category_id");
        } else {
            $_SESSION['errors'] = $category->getErrors();
            RedirectionHelper::redirect("admin/edit-end-category/$category_id");
        }
    }
}
