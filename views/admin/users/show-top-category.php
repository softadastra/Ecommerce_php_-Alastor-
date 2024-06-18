<!-- Liste des Catégories -->
<?php

use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Meta\ManagerHeadPage;

$result = $params['result'] ?? [];
$userEntity = $params['userEntity'] ?? [];
ManagerHeadPage::setTitle($userEntity->getFullName() . " - profil Softadastra");
?>
<div class="container mt-2 mb-3">
    <div class="parametres-container" style="position: relative;">

        <?php session_destroy() ?>

        <?php if (!empty($result)) : ?>
            <ul class="parametres-list">
                <?php foreach ($result as $category) : ?>
                    <li class="box profil" onclick="navigateToPage(<?php echo $category->getId(); ?>)" style="cursor:pointer;">
                        <div>
                            <span><?= $category->getName() ?></span>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php else : ?>
            <p>Aucune catégorie principale disponible.</p>
        <?php endif; ?>
    </div>

</div>
<script>
    function navigateToPage(categoryId) {
        window.location.href = '/?url=admin/users/show-mid-category/' + categoryId;
    }
</script>