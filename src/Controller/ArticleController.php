<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace Controller;

use Model\Article;
use Model\ArticleManager;

/** Class ArticleController
 *
 */
class ArticleController extends AbstractController
{

    const ALLOWED_CATEGORY = ['manga', 'goodies', 'dvd'];
    const ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg'];
    const ARTICLE_BY_PAGE = 16;

    public function listByCategory($category)
    {
        $errors = [];
        $articleManager = new ArticleManager($this->getPdo());
        $articles = $articleManager->searchArticle($category, $_GET['search'] ?? '');
        if (!in_array($category, self::ALLOWED_CATEGORY)) {
            $errors['category'] = "Catégorie inexistante!";
        }
        if (strlen($_GET['search'] ?? '') > 45) {
            $errors['toomuch'] = "La recherche doit contenir 45 caractères maximum!";
        }
        return $this->twig->render('Product/article.html.twig', ['article' => $articles, 'category' => $category, 'error' => $errors]);
    }

    private function validate(array $cleanPost): array
    {
        $errors = [];

        if (empty($cleanPost['name'])) {
            $errors['name'] = 'Veuillez remplir le champ "Nom';
        }
        if (empty($cleanPost['category'])) {
            $errors['category'] = 'Veuillez remplir le champ "Catégorie"';
        }
        if (empty($cleanPost['price'])) {
            $errors['price'] = 'Veuillez remplir le champ "Prix"';
        }
        if (empty($cleanPost['description'])) {
            $errors['description'] = 'Veuillez remplir le champ "Description"';
        }
        if (!preg_match("/^[a-zA-Z0-9 ]+$/", $cleanPost['name'])) {
            $errors['name'] = 'Veuillez remplir le champ "Nom" uniquement avec des caractères alphanumériques';
        }
        if (!preg_match("/^[a-z]+$/", $cleanPost['category'])) {
            $errors['category'] = 'Veuillez remplir le champ "Catégorie" uniquement avec des caractères alphabétiques';
        }
        if (!preg_match("/^[0-9]+$/", $cleanPost['price'])) {
            $errors['price'] = 'Veuillez remplir le champ "Prix" uniquement avec des caractères numériques';
        }
        if ($cleanPost['price'] <= 0) {
            $errors['price'] = 'Veuillez remplir le champ "Prix" avec une valeur supérieur à 0';
        }
        if (strlen($cleanPost['name']) > 255) {
            $errors['name'] = 'Veuillez remplir le champ "Nom" avec 255 caractères maximum';
        }
        if (strlen($cleanPost['category']) > 255) {
            $errors['category'] = 'Veuillez remplir le champ "Catégorie" avec 255 caractères maximum';
        }
        if (strlen(strval($cleanPost['price'])) > 11) {
            $errors['price'] = 'Veuillez remplir le champ "Prix" avec 11 caractères maximum';
        }
        if (strlen($cleanPost['description']) > 5000) {
            $errors['description'] = 'Veuillez remplir le champ "Description" avec 5000 caractères maximum';
        }
        if ((strlen($cleanPost['review']) > 5000) && (!empty($cleanPost['review']))) {
            $errors['review'] = 'Veuillez remplir le champ "Avis de la boutique" avec 5000 caractères maximum';
        }
        if (!empty($_FILES['picture']['name'])) {
            $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
                $errors['picture'] = 'Veuillez télécharger une image au format ' . implode(', ', self::ALLOWED_EXTENSIONS) . ' uniquement';
            }
        }
        return $errors;
    }

    public function searchArticle()
    {
        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;
        $errors = [];
        $articles = [];
        $nbPages = 1;

        $currentPage = $_GET['currentPage'] ?? 1;


        if (!empty($category) && !in_array($category, self::ALLOWED_CATEGORY)) {
            $errors['category'] = "Catégorie inexistante!";
        }
        if (!empty($search) && mb_strlen($search) > 255) {
            $errors['tooMuch'] = "La recherche doit contenir 255 caractères maximum !";
        }
        if (!empty($search) && mb_strlen($search) < 3) {
            $errors['notEnough'] = "La recherche doit contenir 3 caractères minimum !";
        }

        if (empty($errors)) {
            $articleManager = new ArticleManager($this->getPdo());
            $count = $articleManager->countArticle($category, $search);
            $articles = $articleManager->searchArticle($currentPage, $category, $search);
            $nbPages = ceil($count / self::ARTICLE_BY_PAGE);
        }

        return $this->twig->render('Article/article.html.twig', [
            'article' => $articles,
            'category' => $category,
            'errors' => $errors,
            'nbPages' => $nbPages,
            'currentPage' => $currentPage,
            'search' => $search,
        ]);
    }

    public function add()
    {
        $errors = [];
        $article = new Article();
        $cleanPost = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                $cleanPost[$key] = trim($value);
            }
            if (!empty($_POST)) {
                $errors = $this->validate($cleanPost);

                if (empty($errors)) {

                    $articleManager = new ArticleManager($this->getPdo());
                    $article->setName($cleanPost['name']);
                    $article->setCategory($cleanPost['category']);
                    $article->setPrice($cleanPost['price']);
                    if (!empty($_FILES['picture']['name'])) {
                        $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $extension;
                        $uploadDir = __DIR__ . '/../../public/assets/images/upload/';
                        $uploadFile = $uploadDir . $filename;
                        move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile);
                        $article->setPicture($filename);
                    }
                    $article->setDescription($cleanPost['description']);
                    $article->setReview($cleanPost['review']);
                    if (!empty($cleanPost['highlight'])) {
                        $article->setHighlight($cleanPost['highlight']);
                    }
                    $id = $articleManager->insert($article);
                    header('Location:/article/' . $id);
                    exit();
                }
            }
        }

        return $this->twig->render('Article/add.html.twig', ['errors' => $errors, 'article' => $cleanPost,
        ]);
    }

    public function edit(int $id)
    {
        $errors = [];
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cleanPost = [];
            foreach ($_POST as $key => $value) {
                $cleanPost[$key] = trim($value);
            }
            $errors = $this->validate($cleanPost);
            if (empty($errors)) {
                $article->setId($id);
                $article->setName($cleanPost['name']);
                $article->setCategory($cleanPost['category']);
                $article->setPrice($cleanPost['price']);
                if (!empty($_FILES['picture']['name'])) {
                    $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $extension;
                    $uploadDir = __DIR__ . '/../../public/assets/images/upload/';
                    $uploadFile = $uploadDir . $filename;
                    move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile);
                    $article->setPicture($filename);
                }
                $article->setDescription($cleanPost['description']);
                $article->setReview($cleanPost['review']);
                if (!empty($cleanPost['highlight'])) {
                    $article->setHighlight($cleanPost['highlight']);
                }
                $articleManager->edit($article);
                header('Location:/article/' . $id);
                exit();
            } else {
                $article = $cleanPost;
            }
        }
        return $this->twig->render('Article/edit.html.twig', ['errors' => $errors, 'article' => $article
        ]);
    }

    public function show(int $id)
    {
        $articleManager = new ArticleManager($this->pdo);
        $article = $articleManager->selectOneById($id);

        return $this->twig->render('Article/article_details.html.twig', ['article' => $article]);
    }


    public function showAll()
    {
        $articleManager = new ArticleManager($this->pdo);
        $articles = $articleManager->selectAll();

        return $this->twig->render('Article/list.html.twig', ['articles' => $articles]);
    }

    public function deleteArticle(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $articleManager->delete($id);
        header('Location:/admin/list');
        exit();
    }
}
