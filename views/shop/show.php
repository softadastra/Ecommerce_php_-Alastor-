<?php

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Utils\StringHelper;

$id = $params['id'] ?? null;
$articleEntities = $params['articleEntity'] ?? null;
$userEntities = $params['userEntities'] ?? null;
$similarArticles = $params['similarArticles'] ?? null;
$img = null;
if ($articleEntities) {
    $images = explode(',', $articleEntities->getImages());
    $img = end($images);
}
?>
<div class="page" style="margin-top:10px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product">
                    <div class="col-md-7">
                        <div class="p-title" style="width: 100%;">
                            <h2><?= $articleEntities->getTitle() ?></h2>
                        </div>
                        <div class="p-short-des">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Prix <= 5 piece(s):</th>
                                        <th>Prix > 5 piece(s):</th>
                                        <th>Prix >= 50 piece(s):</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="color:#f19000;font-size:1.5em;font-weight:700;"><?= $articleEntities->getPriceUnit() ?>$</td>
                                        <td style="color:#f19000;font-size:1.5em;font-weight:700;"><?= $articleEntities->getWholesalePrice() ?>$</td>
                                        <td style="color:#f19000;font-size:1.5em;font-weight:700;"><?= $articleEntities->getMarque() ?>$</td>
                                    </tr>
                                </tbody>
                            </table>
                            <form action="<?= RedirectionHelper::getUrl('add-to-cart', $articleEntities->getId()) ?>" method="post">
                                <input type="hidden" name="id_article" value="<?= $articleEntities->getId() ?>">
                                <input type="hidden" name="title" value="<?= $articleEntities->getTitle() ?>">
                                <input type="hidden" name="price_unit" value="<?= $articleEntities->getPriceUnit() ?>">
                                <input type="hidden" name="image" value="<?= $img ?>">
                                <input type="hidden" name="quantity" id="quantity" class="form-control" value="1" min="1">
                                <h5> <button type="submit" class="btn" style="background: #f19000;color:#fff;">Commander dès maintenant.</button></h5>
                            </form>
                        </div>
                        <?php if ($userEntities && isset($userEntities[$articleEntities->getIdUser()])) : ?>
                            <?php $user = $userEntities[$articleEntities->getIdUser()]; ?>
                        <?php endif ?>
                        <hr>
                        <h2>Description</h2>
                        <?= StringHelper::getExtrait(ucfirst($articleEntities->getDescription()), 500) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>