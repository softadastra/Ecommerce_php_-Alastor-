<?php

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Utils\StringHelper;

$articleEntities = $params['articleEntities'] ?? [];
$userEntities = $params['userEntities'] ?? [];
$topCategoryEntity = $params['topCategoryEntity'] ?? [];
$endCategoryEntity = $params['endCategoryEntity'] ?? [];
$articlesPagination = $params['articlesPagination'] ?? [];
$currentPage = $params['page'];
$totalPages = $params['totalPages'];
function displayArticle($article, $userEntities)
{
    $images = explode(',', $article->getImages());
    $user = $userEntities[$article->getIdUser()];
?>
    <div>
        <div class="card">
            <div>
                <a href="<?= RedirectionHelper::getUrl("/article", $article->getId()) ?>" style="text-decoration:none;color:blue;">
                    <h5><?= StringHelper::getExtrait(ucfirst($article->getDescription()), 130) ?></h5>
                </a>
            </div>
        </div>
    </div>
<?php } ?>
<div class="card-container">
    <p><a href="/?url=auth/login" class="btn btn-primary">se connecter</a></p>
    <p><a href="/?url=auth/register" class="btn btn-primary">Register</a></p>
    <section>
        <?php if ($articlesPagination !== null) : ?>
            <?php foreach ($articlesPagination as $article) : ?>
                <?php displayArticle($article, $userEntities); ?>
            <?php endforeach ?>
        <?php endif ?>
    </section>
</div>