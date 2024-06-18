<?php
// Récupération de la catégorie sélectionnée depuis l'URL

use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Meta\ManagerHeadPage;

$top_category_entity = $params['top_category_entity'] ?? [];
$result = $params['result'] ?? [];
$userEntity = $params['userEntity'] ?? [];
ManagerHeadPage::setTitle($userEntity->getFullName() . " - profil Softadastra");
?>
<!-- Liste des Sous-Catégories -->
<div class="container mt-2 mb-3">

    <div class="parametres-container" style="position: relative;">
        <?php if ($top_category_entity) : ?>
            <h4 class="mt-4 mb-4"><strong><?= $top_category_entity->getName() ?></strong></h4>
        <?php else : ?>
            <h4 class="mt-4 mb-4">Erreur: Catégorie non trouvée</h4>
        <?php endif; ?>

        <ul class="parametres-list">
            <?php if (empty($result)) : ?>
                <li>Aucune sous-catégorie disponible.</li>
            <?php else : ?>
                <?php foreach ($result as $midCategory) : ?>
                    <li class="box profil" onclick="navigateToPage(<?= $midCategory->getMcatId(); ?>)" style="cursor:pointer;">
                        <div>
                            <span><?= $midCategory->getMcatName() ?></span>
                        </div>
                    </li>
                <?php endforeach ?>
            <?php endif; ?>
        </ul>
    </div>


</div>

<!-- Script JavaScript -->
<script>
    function navigateToPage(endCategoryId) {
        window.location.href = '/?url=admin/users/show-end-category/' + endCategoryId;
    }
</script>