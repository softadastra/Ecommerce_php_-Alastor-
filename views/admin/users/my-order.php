<?php

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Meta\ManagerHeadPage;

$orderedArticles = $params['orderedArticles'] ?? [];
$userEntity = $params['userEntity'] ?? [];
ManagerHeadPage::setTitle($userEntity->getFullName() . " - profil Softadastra");
?>
<?php if (!empty($orderedArticles)) : ?>
    <div class="container mt-3">
        <h2 class="section-title">Mes Commandes</h2>
        <div class="row">
            <?php foreach ($orderedArticles as $article) : ?>
                <?php
                if (!$article->getTitle()) {
                    continue;
                }
                ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $article->getTitle() ?></h5>
                            <div class="article-details">
                                <p class="card-text">
                                    <?php $images = explode(',', $article->getImages()); ?>
                                    <a href="<?= RedirectionHelper::getUrl("/article", $article->getId(), $article->getTitle()) ?>"><img src="<?= ImageRenderer::ShowImage(end($images)) ?>" alt="<?= $article->getTitle() ?>" style="width:80px;"></a>
                                </p>
                                <p class="card-text" style="font-weight: 700;">Total de la commande: <?= $article->getOrderUserId()['total_amount'] ?>$</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php else : ?>
    <p>Aucun article commandé n'a été trouvé.</p>
<?php endif; ?>