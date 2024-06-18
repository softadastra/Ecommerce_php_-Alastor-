<?php

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Meta\ManagerHeadPage;

$userId = $params['id'] ?? null;
$orderedArticles = $params['orderedArticles'] ?? [];
$commandesClient = $params['commandesClient'] ?? [];
$articlesEntity = $params['articlesEntity'] ?? [];
$totalCommande = $params['totalCommande'] ?? 0;
$orderedArticlesCount = is_array($orderedArticles) ? count($orderedArticles) : 0;
$commandesClientCount = is_array($commandesClient) ? count($commandesClient) : 0;
$articlesCount = is_array($articlesEntity) ? count($articlesEntity) : 0;

$userCount = $params['userCount'] ?? null;
$userEntity = $params['userEntity'] ?? [];
ManagerHeadPage::setTitle($userEntity->getFullName() . " - profil Softadastra");
?>
<div class="parametres-container settings">
    <ul class="parametres-list">

        <li class="box">
            <a href="<?= RedirectionHelper::getUrl('admin/users/show-top-category') ?>" style="color:inherit;" class="btn btn-warning">
                <i class="fas fa-plus-circle"></i> Publier un article <i class="fas fa-chevron-right" style="position: absolute;right:0;color:#AAAAAA;"></i>
            </a>
        </li>

        <li class="box">
            <a href="<?= RedirectionHelper::getUrl('admin/users/listing', $params['id']) ?>" style="color:inherit;" class="btn btn-warning">
                <i class="fas fa-list"></i> Mes Articles <i class="fas fa-chevron-right" style="position: absolute;right:0;color:#AAAAAA;"></i>
                <span class="article-badge"><?= $articlesCount ?></span>
            </a>
        </li>

        <!-- Vos commandes -->
        <li class="box">
            <a href="<?= RedirectionHelper::getUrl('admin/users/my-order', $params['id']) ?>" style="color:inherit; position: relative; display: inline-block;" class="btn btn-warning">
                <i class="fas fa-shopping-bag" style="position: relative;"></i> Mes commandes
                <span class="orders-badge"><?= $orderedArticlesCount ?></span>
            </a>
        </li>

        <!-- Commandes des clients -->
        <li class="box">
            <a href="<?= RedirectionHelper::getUrl('admin/users/client-order', $params['id']) ?>" style="color:inherit; position: relative; display: inline-block;" class="btn btn-warning">
                <i class="fas fa-users" style="position: relative;"></i> Commandes des clients
                <span class="client-orders-badge"><?= $commandesClientCount ?></span>
            </a>
        </li>

        <a href="<?= RedirectionHelper::getUrl('auth/logout', $params['id']) ?>" style="color:inherit;" class="btn btn-warning">
            <li class="box">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </li>
        </a>

    </ul>
</div>

<style>
    a {
        margin: 10px;
    }

    li {
        list-style: none;
    }
</style>