<?php

namespace Softadastra\Controllers;

use Softadastra\Application\Http\RedirectionHelper;
use Softadastra\Application\Image\ImageGenerator;
use Softadastra\Domain\Auth\Adapters\UserRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\ArticleRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\CountryRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\EndCategoryRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\MidCategoryRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\SizeRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\TagsRepositoryPDO;
use Softadastra\Domain\Shop\Adapters\TopCategoryRepositoryPDO;
use Softadastra\Domain\Shop\UseCase\ArticleManager;

class UsersController extends Controller
{
    private $path = 'admin.users.';

    public function dashboard(int $id)
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);

            $articleRepository = new ArticleRepositoryPDO();
            $orderedArticles = $articleRepository->findOrderedArticlesByUserId2($user_id);
            $commandesClient = $articleRepository->findOrderedArticlesBySellerId($id);
            $articlesEntity = $articleRepository->findByUserId($id);
            $totalCommande = 0; // Initialisation de la variable pour stocker le total

            // Vérifier si $commandesClient est un tableau avant d'itérer dessus
            if (is_array($commandesClient)) {
                foreach ($commandesClient as $article) {
                    // Récupérer les détails de la commande pour cet article
                    $orderDetails = $article->getOrderUserId();
                    // Vérifier si les détails de la commande existent
                    if ($orderDetails) {
                        // Récupérer le montant total de la commande et l'ajouter au total général
                        $totalCommande += $orderDetails['total_amount'];
                    }
                }
            }

            if ($userEntity) {
                return $this->view($this->path . 'dashboard', compact('id', 'userEntity', 'orderedArticles', 'commandesClient', 'totalCommande', 'articlesEntity'));
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }


    public function showTopCategory()
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);

            if ($userEntity) {
                $top_category_pdo = new TopCategoryRepositoryPDO();
                $result = $top_category_pdo->findAll();
                return $this->view($this->path . 'show-top-category', compact('result', 'userEntity'));
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }

    public function showMidCategory(int $id)
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);

            if ($userEntity) {
                $mid_category_pdo = new MidCategoryRepositoryPDO();
                $result = $mid_category_pdo->findOne(['tcat_id' => $id]);
                $top_category = new TopCategoryRepositoryPDO();
                $top_category_entity = $top_category->findById($id);

                return $this->view($this->path . 'show-mid-category', compact('id', 'result', 'top_category_entity', 'userEntity'));
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }

    public function showEndCategory(int $id)
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);

            if ($userEntity) {
                $end_category_pdo = new EndCategoryRepositoryPDO();
                $result = $end_category_pdo->findOne(['mcat_id' => $id]);

                $mid_category_pdo = new MidCategoryRepositoryPDO();
                $mid_category_entity = $mid_category_pdo->findById($id);

                return $this->view($this->path . 'show-end-category', compact('id', 'result', 'mid_category_entity', 'userEntity'));
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }

    public function showFormulaire(int $id)
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);

            if ($userEntity) {
                return $this->view($this->path . 'show-formulaire', compact('id', 'userEntity'));
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }

    public function listingArticles(int $id)
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);



            if ($userEntity) {
                $articleRepository = new ArticleRepositoryPDO();
                $articlesEntity = $articleRepository->findByUserId($id);
                $userRepository = new UserRepositoryPDO();

                $userEntity = [];
                foreach ($articlesEntity as $article) {
                    $userEntity[$article->getIdUser()] = $userRepository->findById($article->getIdUser());
                }


                return $this->view($this->path . 'listingArticle', compact('articlesEntity', 'userEntity'));
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }

    public function showArticle(int $id)
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);
            if ($userEntity) {
                $articleRepository = new ArticleRepositoryPDO();
                $articleEntity = $articleRepository->findById($id);
                return $this->view($this->path . 'show-article', compact('articleEntity', 'userEntity'));
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }
    // Méthode pour publier un nouvel article
    public function publishedArticle()
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);

            if ($userEntity) {
                $article_pdo = new ArticleRepositoryPDO();
                $save_article = new ArticleManager($article_pdo);
                $save_article->execute($_POST, $_FILES);

                $_SESSION['message'] = "votre offre a bien ete publier avec succes";
                header('Location: /?url=form/330');
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }
    // Méthode pour supprimer un article
    public function deleteArticle(int $articleId)
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);

            if ($userEntity) {
                $articleRepository = new ArticleRepositoryPDO();
                $articleRepository->delete($articleId);
                $_SESSION['delete_article_success'] = "Votre article a bien ete supprimer";
                header('Location: /?url=admin/users/listing/' . $user_id);
                exit;
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }

    public function myOrder(int $id)
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);

            if ($userEntity) {
                $articleRepository = new ArticleRepositoryPDO();
                $orderedArticles = $articleRepository->findOrderedArticlesWithOrderDetailsByUserId($id);
                return $this->view($this->path . 'my-order', compact('orderedArticles', 'userEntity'));
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }

    public function clientOrder(int $id)
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];
            $userRepository = new UserRepositoryPDO();
            $userEntity = $userRepository->findById($user_id);

            if ($userEntity) {
                $articleRepository = new ArticleRepositoryPDO();
                $commandesClient = $articleRepository->findOrderedArticlesBySellerId($id);

                return $this->view($this->path . 'customer-order', compact('commandesClient', 'userEntity'));
            } else {
                RedirectionHelper::redirect("auth/login");
            }
        } else {
            RedirectionHelper::redirect("auth/login");
        }
    }
}
