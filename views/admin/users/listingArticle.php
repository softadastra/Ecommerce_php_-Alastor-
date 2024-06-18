<?php

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Meta\ManagerHeadPage;
use Softadastra\Application\Utils\StringHelper;

$userEntities = $params['userEntity'] ?? [];
ManagerHeadPage::setTitle("Profil Softadastra");
?>
<div class="container mt-3 mb-5" style="position: relative;">
    <a href="<?= RedirectionHelper::getUrl('admin/users/show-top-category') ?>" class="btn btn-sm" style="position: absolute;top:20;right:10px;color:#fff;background:#2c3e50;">Publier un article</a>
    <h2>Mon stock</h2>
    <?php
    $articlesEntity = $params['articlesEntity'];
    if (isset($_SESSION['delete_article_success'])) : ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <h5><i class="fa fa-trash" aria-hidden="true"></i> <?= $_SESSION['delete_article_success'] ?></h5>
        </div>
    <?php endif ?>
</div>

<?php session_destroy() ?>


<div class="card-container">
    <section>
        <?php if (!empty($articlesEntity)) : ?>
            <?php foreach ($articlesEntity as $article) : ?>
                <div class="content">
                    <div class="box">
                        <div class="imgBx">
                            <?php $images = explode(',', $article->getImages()); ?>
                            <a href="<?= RedirectionHelper::getUrl("admin/users/show-article", $article->getId()) ?>"><img src="<?= ImageRenderer::ShowImage(end($images)) ?>" alt="<?= $article->getTitle() ?>" width="200px"></a>
                        </div>
                        <div class="text-product" style="padding-left: 10px;">
                            <a href="<?= RedirectionHelper::getUrl("admin/users/show-article", $article->getId()) ?>" style="color: green;"><?= $article->getTitle() ?? ''; ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </section>
</div>