<?php

namespace Softadastra\Controllers;

use Exception;
use Softadastra\Config\Database;
use Softadastra\Domain\Auth\Adapters\UserRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\ArticleRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\EndCategoryRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\MidCategoryRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\TopCategoryRepositoryPDO;

class ArticleController extends Controller
{
    private $path = 'shop.';
    private $error = 'errors.';

    private function getUserEntities(array $articleEntities): array
    {
        $userRepository = new UserRepositoryPDO();
        $userEntities = [];

        foreach ($articleEntities as $article) {
            $userEntities[$article->getIdUser()] = $userRepository->findById($article->getIdUser());
        }

        return $userEntities;
    }

    private function getSimilarArticles(int $id, ArticleRepositoryPDO $articleRepository, int $limit = 20): array
    {
        $articleEntity = $articleRepository->findById($id);

        if (!$articleEntity) {
            throw new Exception("Article non trouvé");
        }

        $similarArticles = $articleRepository->findSimilarArticles($articleEntity, $limit, ['ecat_id' => $articleEntity->getEcatId()]);
        if (empty($similarArticles)) {
            $similarArticles = $articleRepository->findSimilarArticles($articleEntity, $limit, ['marque' => $articleEntity->getMarque()]);
        }

        return $similarArticles;
    }

    public function home()
    {
        try {
            $articleRepository = new ArticleRepositoryPDO();
            $articleEntities = $articleRepository->findAll();
            if (isset($_GET['page'])) {
                if ($_GET['page'] === '1') {
                    $page = 1;
                } elseif (preg_match('/^[1-9][0-9]*$/', $_GET['page'])) {
                    $page = intval($_GET['page']);
                } else {
                    $page = 1;
                }
            } else {
                $page = 1;
            }
            $articlesPagination = $articleRepository->pagination($page, 6);
            $totalArticlesCount = count($articleEntities);
            $perPage = 6;
            $totalPages = ceil($totalArticlesCount / $perPage);
            $userEntities = $this->getUserEntities($articleEntities);
            $topCategoryRepository = new TopCategoryRepositoryPDO();
            $topCategoryEntity = $topCategoryRepository->findAll();
            $endCategoryRepository = new EndCategoryRepositoryPDO();
            $endCategoryEntity = $endCategoryRepository->findAllWithArticle("ecat_id DESC");

            return $this->view($this->path . 'home', compact('articleEntities', 'userEntities', 'topCategoryEntity', 'endCategoryEntity', 'articlesPagination', 'totalPages', 'page'));
        } catch (Exception $e) {
            return $this->view($this->error . 'shop');
        }
    }

    public function show(int $id)
    {
        try {
            $articleRepository = new ArticleRepositoryPDO();
            $articleEntity = $articleRepository->findById($id);

            $similarArticles = $this->getSimilarArticles($id, $articleRepository);

            $total_view = $articleEntity->getTotalView() + 1;
            $articleRepository->update_view($total_view, $id);

            $userRepository = new UserRepositoryPDO();
            $userEntities = $this->getUserEntities($similarArticles);
            $userEntities[$articleEntity->getIdUser()] = $userRepository->findById($articleEntity->getIdUser());

            return $this->view($this->path . 'show', compact('id', 'articleEntity', 'similarArticles', 'userEntities'));
        } catch (Exception $e) {
            return $this->view($this->error . 'shop');
        }
    }

    public function addToCart()
    {
        $id_article = $_POST['id_article'] ?? null;
        $titre = $_POST['title'] ?? null;
        $prix = $_POST['price_unit'] ?? null;
        $image = $_POST['image'] ?? null;
        $quantity = $_POST['quantity'] ?? null;
        $color_id = $_POST['color_id'];
        $size_id = $_POST['size_id'];

        if ($id_article && $titre && $prix && $image && $quantity) {
            $article = array(
                'id_article' => $id_article,
                'title' => $titre,
                'price_unit' => $prix,
                'image' => $image,
                'quantity' => $quantity,
                'color_id' => $color_id,
                'size_id' => $size_id
            );
            $panier = isset($_COOKIE['panier']) ? json_decode($_COOKIE['panier'], true) : array();
            $panier[$id_article] = $article;
            setcookie('panier', json_encode($panier), time() + (86400 * 30), "/");
            header('Location: /?url=cart');
            exit();
        } else {
            echo "Erreur: Données d'article manquantes";
        }
    }

    public function cart()
    {
        return $this->view($this->path . 'cart');
    }

    public function updateCart()
    {
        if (isset($_POST['index']) && isset($_POST['quantity'])) {
            $index = $_POST['index'];
            $quantity = $_POST['quantity'];
            if (isset($_COOKIE['panier']) && !empty($_COOKIE['panier'])) {
                $panier = json_decode($_COOKIE['panier'], true);
                if (array_key_exists($index, $panier)) {
                    $panier[$index]['quantity'] = $quantity;
                    setcookie('panier', json_encode($panier), time() + (86400 * 30), '/');
                }
            }
        }
        header('Location: /?url=cart');
        exit();
    }


    public function emptycart()
    {
        setcookie('panier', '', time() - 3600, "/");
        header('Location: /?url=cart');
        exit();
    }

    public function order()
    {
        if (isset($_COOKIE['panier']) && $_COOKIE['user_id']) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);
            if ($userEntity) {
                try {
                    $panier = json_decode($_COOKIE['panier'], true);
                    foreach ($panier as $p) {
                        $article_id = $p["id_article"];
                    }
                    $articleRepository = new ArticleRepositoryPDO();
                    $articlesEntity = $articleRepository->findById($article_id);
                    return $this->view($this->path . 'order', compact('articlesEntity'));
                } catch (Exception $e) {
                    die("erreur");
                }
            } else {
                header('Location: /?url=auth/login');
                exit;
            }
        } else {
            header('Location: /?url=auth/login');
            exit;
        }
    }

    public function processOrder()
    {
        $shipping_address = $_POST['shipping_address'] ?? null;
        if ($shipping_address) {
            if (isset($_COOKIE['panier']) && !empty($_COOKIE['panier'])) {
                $panier = json_decode($_COOKIE['panier'], true);
                $total_amount = 0;
                foreach ($panier as $article) {
                    $total_amount += $article['price_unit'] * $article['quantity'];
                }
                try {
                    $pdo = new Database(DB_NAME, DB_HOST, DB_USER, DB_PWD);
                    $pdo->getPdo()->beginTransaction();
                    if (isset($_COOKIE['user_id'])) {
                        $user_id = $_COOKIE['user_id'];
                    }
                    $stmt = $pdo->getPdo()->prepare("INSERT INTO tbl_order (user_id,total_amount, shipping_address) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $total_amount, $shipping_address]);
                    $order_id = $pdo->getPdo()->lastInsertId();
                    $stmt = $pdo->getPdo()->prepare("INSERT INTO tbl_order_details (order_id, article_id, quantity) VALUES (?, ?, ?)");
                    foreach ($panier as $article) {
                        $article_id = $article['id_article'];
                        $quantity = $article['quantity'];
                        $stmt->execute([$order_id, $article_id, $quantity]);
                    }
                    $pdo->getPdo()->commit();
                    setcookie('panier', '', time() - 3600, "/");
                    $_SESSION['success_commande'] = "Votre commande a bien été envoyée.";
                    header('Location: /?url=admin/users/dashboard/' . $user_id);
                    exit;
                } catch (Exception $e) {
                    $pdo->getPdo()->rollBack();
                    echo "Erreur lors du traitement de la commande: " . $e->getMessage();
                }
                $pdo = null;
            }
        } else {
            echo "Erreur: Données de commande manquantes";
        }
    }
}
