<?php
// Récupération de l'ID de la sous-catégorie depuis l'URL

use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Meta\ManagerHeadPage;

$mid_category_entity = $params['mid_category_entity'] ?? [];
$result = $params['result'] ?? [];
$userEntity = $params['userEntity'] ?? [];
ManagerHeadPage::setTitle($userEntity->getFullName() . " - profil Softadastra");
?>

<!-- Liste des "End Categories" -->
<div class="container mt-2 mb-3">
    <div class="parametres-container" style="position: relative;">
        <?php if (isset($mid_category_entity['mcat_name'])) : ?>
            <h4 class="mt-4 mb-4"><strong><?= $mid_category_entity['mcat_name'] ?></strong></h4>
        <?php else : ?>
            <h4 class="mt-4 mb-4">Erreur: pas de sous-catégorie défini</h4>
        <?php endif; ?>

        <ul class="parametres-list">
            <?php foreach ($result as $endCategory) : ?>
                <li class="box profil" onclick="navigateToPage(<?= $endCategory->getEcatId(); ?>)" style="cursor:pointer;">
                    <div>
                        <span><?= $endCategory->getName() ?></span>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    </div>

</div>
<script>
    function navigateToPage(endCategoryId) {
        window.location.href = '/?url=admin/users/show-formulaire/' + endCategoryId;
    }
</script>