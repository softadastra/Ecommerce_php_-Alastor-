<?php

use Softadastra\Application\Meta\ManagerHeadPage;

ManagerHeadPage::setTitle("Se connecter à Softadastra");
ManagerHeadPage::setContent("Connectez-vous à votre compte Softadastra pour accéder à toutes les fonctionnalités et services.");
?>
<div class="card login-page" style="width:90%;">
    <div class="card-body">

        <?php if (isset($_SESSION['success_user_register'])) : ?>
            <div class="alert alert-success"><?= $_SESSION['success_user_register'] ?></div>
        <?php endif ?>

        <?php if (isset($_SESSION['errors_login_users'])) : ?>
            <div class="alert alert-danger"><?= $_SESSION['errors_login_users'] ?></div>
        <?php endif ?>

        <?php session_destroy() ?>

        <form action="/?url=auth/postLogin" method="post" style="margin-bottom: 1rem;">
            <div class="form-group mb-3">
                <label for="email">e-mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= isset($_SESSION['existing_email']) ? htmlspecialchars($_SESSION['existing_email']) : ''; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="password">password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="custom-button-container-login">
                <button id="custom-login-login" class="custom-button-login" style="background: #ff9900;color:#fff;">Continue</button>
            </div>
        </form>
    </div>
</div>