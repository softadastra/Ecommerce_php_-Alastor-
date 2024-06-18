<?php

use Softadastra\Application\Http\RedirectionHelper;

$articlesEntity = $params['articlesEntity'] ?? [];
?>
<div class="container mt-3 mb-3">
    <div class="login-form">
        <h1>Formulaire de Confirmation de Commande</h1>
        <form action="<?= RedirectionHelper::getUrl('process-order') ?>" method="post" enctype="multipart/form-data" class="formulaire" id="uploadForm">

            <div class="form-group">
                <label for="shipping_address">Adresse de livraison :</label>
                <textarea name="shipping_address" id="shipping_address" class="form-control" cols="30" rows="10" placeholder=""></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Passer la commande</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('input[type="file"]').change(function(e) {
            const files = e.target.files;
            $('#imagePreview').html('');
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').append('<img src="' + e.target.result + '" width="100" style="padding:10px;">');
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<style>
    .login-form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .login-form h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #666;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .btn-primary {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>
<script>
    new MultiSelectTag('payment') // id
</script>