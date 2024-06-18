<?php

use Softadastra\Application\Meta\ManagerHeadPage;

ManagerHeadPage::setTitle("S'inscription à Softadastra");
ManagerHeadPage::setContent("Inscrivez-vous dès maintenant sur Softadastra pour commencer à vendre vos produits ou explorer notre catalogue en tant qu'acheteur.");
?>
<div class="card login-page" style="width:90%;">
    <div class="card-body">
        <div class="back-icon">

            <?php

            use Softadastra\Application\Http\RedirectionHelper;
            ?>

            <div class="container mt-3">
                <?php if (isset($_SESSION['errors_user_register']) && !empty($_SESSION['errors_user_register'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <h5><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Erreurs :</h5>
                        <ul>
                            <?php
                            $i = 0;
                            foreach ($_SESSION['errors_user_register'] as $error) :
                                ++$i;
                            ?>
                                <li> <?= $i ?>. <?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <?php session_destroy() ?>

            <form action="<?= RedirectionHelper::getUrl('auth/postRegister') ?>" method="post" enctype="multipart/form-data" style="margin-bottom: 1rem;">
                <div class="form-group mb-3">
                    <label for="full_name">Name:</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= isset($_SESSION['existing_full_name']) ? htmlspecialchars($_SESSION['existing_full_name']) : ''; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email">e-mail:</label>
                    <input type="email" class="form-control <?= isset($_SESSION['errors_user_email']) ? 'is-invalid' : null; ?>" id="email" name="email" value="<?= isset($_SESSION['existing_email']) ? htmlspecialchars($_SESSION['existing_email']) : ''; ?>" required>

                    <p class="invalid-feedback"><i class="fa fa-info fa-italic" style="color: blue;font-style:italic;" aria-hidden="true"></i> <?= isset($_SESSION['errors_user_email']) ? $_SESSION['errors_user_email'] : '' ?></p>

                </div>
                <div class="form-group mb-3">
                    <label for="phone">phone</label>
                    <input type="tel" class="form-control <?= isset($_SESSION['errors_user_register']['phone']) ? 'is-invalid' : null; ?>" id="phone" name="phone" value="<?= isset($_SESSION['existing_phone']) ? $_SESSION['existing_phone'] : ''; ?>" required>

                    <?php if (isset($_SESSION['errors_user_register'])) : ?>
                        <p class="invalid-feedback"><i class="fa fa-info fa-italic" style="color: blue;font-style:italic;" aria-hidden="true"></i> <?= $_SESSION['errors_user_register']['phone'] ?></p>
                    <?php endif ?>
                </div>
                <div class="form-group mb-3">
                    <label for="password">password:</label>
                    <div class="input-group">
                        <input type="password" class="form-control <?= isset($_SESSION['errors_user_register']['password']) ? 'is-invalid' : null ?>" id="password" name="password" required>
                    </div>

                    <?php if (isset($_SESSION['errors_user_register']['password'])) : ?>
                        <p class="invalid-feedback"><i class="fa fa-info fa-italic" style="color: blue;font-style:italic;" aria-hidden="true"></i> <?= $_SESSION['errors_user_register']['password'] ?></p>
                    <?php endif ?>
                </div>

                <div class="custom-button-container-login">
                    <button id="custom-register" class="custom-button-login" style="background: #f19000;color:#fff;">Continue</button>
                </div>
            </form>
        </div>
    </div>