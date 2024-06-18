<?php

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Meta\ManagerHeadPage;
use Softadastra\Config\Database;
use Softadastra\Domain\Auth\Adapters\UserRepositoryPDO;

ManagerHeadPage::setTitle("profil Softadastra");
$user = null;
if (isset($_COOKIE['user_id'])) {
    $userRepository = new UserRepositoryPDO();
    $user = $userRepository->findById($_COOKIE['user_id']);
} else {
    header('Location: /');
    exit;
}
$id_user = $user->getId() ?? null;
$size_entity = $params['size_entity'] ?? [];
$tags_entity = $params['tags_entity'] ?? [];
$pdo = new Database(DB_NAME, DB_HOST, DB_USER, DB_PWD);
$ecat_id = isset($params['id']) ? $params['id'] : '';
$query = $pdo->getPdo()->prepare('SELECT ecat_name FROM tbl_end_category WHERE ecat_id = ?');
$query->execute([$ecat_id]);
$endCategory = $query->fetch();
if (!$endCategory) {
    $_SESSION['hacker'] = "N'ose plus jamais";
    RedirectionHelper::redirect("admin/users/show-top-category");
}
?>

<div class="container mt-3 mb-3">
    <div class="login-form" style="position: relative;">
        <h1 class="mt-3">Ajouter un Article</h1>

        <div class="container mt-3">
            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <h5><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Erreurs :</h5>
                    <ul>
                        <?php
                        $i = 0;
                        foreach ($_SESSION['errors'] as $error) :
                            ++$i;
                        ?>
                            <li> <?= $i ?>. <?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <div class="container mt-3">
            <?php if (isset($_SESSION['success'])) : ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <h5><i class="fa fa-check text-success" aria-hidden="true"></i> <?= $_SESSION['success'] ?></h5>
                </div>
            <?php endif ?>
        </div>
        <?php session_destroy() ?>
        <form action="<?= RedirectionHelper::getUrl('admin/users/publishedArticle') ?>" method="post" enctype="multipart/form-data" class="formulaire" id="uploadForm">
            <div class="form-group">
                <label for="title">Titre de l'article :</label>
                <input type="text" class="form-control <?php echo isset($_SESSION['errors']['title']) ? 'is-invalid' : ''; ?>" id="title" name="title" required>
                <?php if (isset($_SESSION['errors']['title'])) : ?>
                    <div class="invalid-feedback"><?php echo $_SESSION['errors']['title']; ?></div>
                <?php endif; ?>
            </div>


            <div class="form-group">
                <label for="end_category">Categorie :</label>
                <input type="text" class="form-control" id="end_category" name="end_category" value="<?php echo $endCategory['ecat_name']; ?>" disabled readonly>
            </div>

            <div class="image_product" style="margin: 10px 0px;">
                <h5 style="font-weight: 700;">Images :</h5>
                <input type="file" name="p_featured_photo[]" class="form-control" multiple>
                <div id="imagePreview"></div>
            </div>

            <div class="form-group">
                <label for="price_unit">Prix unitaire</label>
                <input type="text" class="form-control <?php echo isset($_SESSION['errors']['price_unit']) ? 'is-invalid' : ''; ?>" id="price_unit" name="price_unit" required>
                <?php if (isset($_SESSION['errors']['price_unit'])) : ?>
                    <div class="invalid-feedback"><?php echo $_SESSION['errors']['price_unit']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="wholesale_price">Prix >= 5 piece(s)</label>
                <input type="text" class="form-control" id="wholesale_price" name="wholesale_price" required>
            </div>

            <div class="form-group">
                <label for="marque">Prix >= 50 piece(s)</label>
                <input type="text" class="form-control" id="marque" name="marque" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantité minimum pour la commande</label>
                <input type="text" class="form-control <?php echo isset($_SESSION['errors']['quantity']) ? 'is-invalid' : ''; ?>" id="quantity" name="quantity" required>
                <?php if (isset($_SESSION['errors']['quantity'])) : ?>
                    <div class="invalid-feedback"><?php echo $_SESSION['errors']['quantity']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="description">Descris votre article :</label>
                <textarea name="description" id="description" class="form-control <?php echo isset($_SESSION['errors']['description']) ? 'is-invalid' : ''; ?>" cols="30" rows="10" placeholder=""></textarea>
                <?php if (isset($_SESSION['errors']['description'])) : ?>
                    <div class="invalid-feedback"><?php echo $_SESSION['errors']['description']; ?></div>
                <?php endif; ?>
            </div>

            <input type="hidden" name="ecat_id" value="<?php echo $ecat_id; ?>">
            <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
            <button type="submit" class="btn btn-primary">Publier l'article</button>
        </form>

    </div>
</div>