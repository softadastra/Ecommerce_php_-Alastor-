<?php

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageRenderer;
use Softadastra\Application\Meta\ManagerHeadPage;
use Softadastra\Domain\Auth\Adapters\UserRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\TopCategoryRepositoryPDO;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre_articles_panier = 0;
try {
    $topCategoryRepository = new TopCategoryRepositoryPDO();
    $topCategoryEntity = $topCategoryRepository->findAll();
    if (!empty($_COOKIE['panier'])) {
        $panier = json_decode($_COOKIE['panier'], true);
        foreach ($panier as $article) {
            $nombre_articles_panier += $article['quantity'];
        }
    }
} catch (Exception $e) {
    echo "Une erreur s'est produite lors de la récupération des catégories principales ou lors de la vérification du panier.";
    exit;
}
if (isset($_COOKIE['user_id'])) {
    $_SESSION['unique_id'] = $_COOKIE['user_id'];
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>font-awesome.min.css">
    <title><?= ManagerHeadPage::getTitle() ?></title>
    <style>
        .navbar a {
            text-align: left;
            padding: 10px;
        }
    </style>
</head>

<body>
    <main>
        <?= $content ?>
    </main>

    <script src="<?= JS_PATH ?>bootstrap.min.js"></script>
</body>

</html>