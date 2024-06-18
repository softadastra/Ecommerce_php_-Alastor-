<div class="container mt-5" style="padding-right: calc(var(--bs-gutter-x)* .2);
    padding-left: calc(var(--bs-gutter-x)* .2);">
    <h1 class="mb-4">Panier</h1>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Photo</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Prix</th>
                </tr>
            </thead>
            <tbody>
                <?php

                use Softadastra\Application\Image\ImageRenderer;

                $total_panier = 0;
                if (!empty($_COOKIE['panier'])) {
                    $panier = json_decode($_COOKIE['panier'], true);
                    foreach ($panier as $index => $article) {
                        $sous_total = $article['price_unit'] * $article['quantity'];
                        $total_panier += $sous_total;
                ?>
                        <tr>
                            <td><img src="<?= ImageRenderer::ShowImage($article['image']) ?>" alt="Image de l'article" style="max-width: 50px; max-height: 80px;"></td>
                            <td><?= number_format($article['price_unit'], 2) ?>$</td>
                            <td>
                                <form action="/?url=update-cart" method="post" class="d-flex">
                                    <input type="hidden" name="index" value="<?= $index ?>">
                                    <input type="number" name="quantity" value="<?= $article['quantity'] ?>" min="1" class="form-control" style="margin-right: 5px;">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                </form>
                            </td>
                            <td><?= $sous_total ?>$</td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>Votre panier est vide</td></tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th><?= $total_panier ?>$</th>
                </tr>
            </tfoot>
        </table>
        <div class="text-center mb-3">
            <a href="/?url=empty-cart" class="btn btn-danger">Vider le panier</a>
            <a href="/?url=order" class="btn btn-success">Commander</a>
        </div>
    </div>
</div>