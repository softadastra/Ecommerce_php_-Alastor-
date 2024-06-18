<?php

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Meta\ManagerHeadPage;
use Softadastra\Application\Utils\StringHelper;

$article = $params['articleEntity'] ?? [];
$userEntity = $params['userEntity'] ?? [];
ManagerHeadPage::setTitle($userEntity->getFullName() . " - profil Softadastra");
?>
<div class="container mt-3 mb-5" style="position: relative;">
    <a href="<?= RedirectionHelper::getUrl('admin/users/show-top-category') ?>" class="btn btn-sm" style="position: absolute;top:20;right:10px;color:#fff;background:#2c3e50;">Publier un article</a>
</div>

<?php session_destroy() ?>
<div class="container mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th width="60">Photo</th>
                <th class="d-none d-md-table-cell">Description</th>
                <th width="40">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($article === null) : ?>
                <tr>
                    <td colspan="4">Aucun article disponible.</td>
                </tr>
            <?php else : ?>
                <tr>
                    <td style="width:82px;">
                        <?php
                        $images = explode(',', $article->getImages());
                        ?>
                        <a href="<?= RedirectionHelper::getUrl("/article", $article->getId(), $article->getTitle()) ?>"><img src="<?= ImageRenderer::ShowImage(end($images)) ?>" alt="<?= $article->getTitle() ?>" style="width:80px;"></a>
                    </td>
                    <td class="d-none d-md-table-cell"><?= StringHelper::getExtrait($article->getDescription(), 200) ?></td>
                    <td class="d-flex">
                        <form action="/?url=admin/users/deleteArticle/<?= $article->getId() ?>" method="post">
                            <button class="btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </form> &nbsp;
                        <a href="/?url=admin/users/update-article/<?= $article->getId() ?>" class="btn btn-primary ml-2">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>