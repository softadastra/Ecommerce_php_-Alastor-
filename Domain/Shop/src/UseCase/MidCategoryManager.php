<?php

namespace Softadastra\Domain\Shop\UseCase;

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageGenerator;
use Softadastra\Domain\Shop\Adapters\MidCategoryRepositoryPDO;
use Softadastra\Domain\Shop\Entity\MidCategoryEntity;
use Softadastra\Domain\Shop\Port\MidCategoryRepositoryInterface;

class MidCategoryManager
{
    protected MidCategoryRepositoryInterface $repository;

    public function __construct(MidCategoryRepositoryInterface $repo)
    {
        $this->repository = $repo;
    }

    public function execute(array $data, array $file)
    {
        $images = new ImageGenerator();
        $photo = $images->create($file,  IMAGE_PATH . 'midCategory/');

        $mid_category = new MidCategoryEntity($data['mcat_name'], $data['tcat_id'], $photo);
        if ($mid_category->isValid()) {
            $this->repository->save($mid_category);
            $_SESSION['success_mid_category'] = "Categorie ajouter avec succes";
            RedirectionHelper::redirect("admin/mid-category");
        } else {
            $_SESSION['errors_mid_category'] = $mid_category->getErrors();
            RedirectionHelper::redirect("admin/mid-category");
        }
    }

    public function update(array $data, array $file, $id)
    {
        $image = new ImageGenerator();
        $midCategoryRepository = new MidCategoryRepositoryPDO();
        $midCategoryEntity = $midCategoryRepository->findById($id);
        if (!empty($file['p_featured_photo']['name'][0])) {
            $photo = $image->create($file,  IMAGE_PATH . 'midCategory/');
        } else {
            $photo = $midCategoryEntity['mid_image'];
        }
        $category = new MidCategoryEntity(
            $data['mcat_name'],
            $data['tcat_id'],
            $photo
        );
        $category->setMcatId($id);
        $category_id = $category->getMcatId();
        if ($category->isValid()) {
            $this->repository->update($category);
            $_SESSION['success'] = "Votre category a été modifier avec succès.";
            RedirectionHelper::redirect("admin/edit-mid-category/$category_id");
        } else {
            $_SESSION['errors'] = $category->getErrors();
            RedirectionHelper::redirect("admin/edit-mid-category/$category_id");
        }
    }
}
