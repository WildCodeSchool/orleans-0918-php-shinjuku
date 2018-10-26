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

    const ALLOWED_CATEGORY=['manga','goodies','dvd'];
    const ALLOWED_EXTENSIONS=['png', 'jpg', 'jpeg'];
    const ARTICLE_BY_PAGE=16;

  public function listByCategory($category)
    {
        $nbPages=1;
        $currentPage=1;
        $errors = [];
        if (isset($_GET['currentPage'])) {
            $currentPage = $_GET['currentPage'];
        }
        $articleManager = new ArticleManager($this->getPdo());
        $count=$articleManager->countArticle($category,$_GET['search'] ?? '');
        $articles = $articleManager->searchArticle($currentPage, $category, $_GET['search'] ?? '');
        if (!in_array($category, self::ALLOWED_CATEGORY)) {
            $errors['category'] = "Catégorie inexistante!";
        }
        if (strlen($_GET['search'] ?? '') > 45) {
            $errors['toomuch'] = "La recherche doit contenir 45 caractères maximum!";
        }
        $nbPages=ceil($count/self::ARTICLE_BY_PAGE);
        return $this->twig->render('Product/article.html.twig', ['article' => $articles, 'category'=> $category, 'error'=>$errors, 'nbPages' => $nbPages, 'currentPage' => $currentPage, 'get' => $_GET]);
    }

    public function searchArticleGeneral()
    {
        $nbPages=1;
        $currentPage=1;
        $articles = [];
        if (strlen($_GET['search']) < 3) {
            $errors['notenough'] = "La recherche doit contenir 3 caractère minimum!";
            return $this->twig->render('Article/article.html.twig', ['article' => $articles, 'error' => $errors]);
        }
        $articleManager = new ArticleManager($this->getPdo());
        $count=$articleManager->countArticle($category,$_GET['search'] ?? '');
        $articles = $articleManager->searchArticle($currentPage,"", $_GET['search'] ?? '');
        $nbPages=ceil($count/self::ARTICLE_BY_PAGE);
        return $this->twig->render('Article/article_page_search.html.twig', ['article' => $articles, 'nbPages' => $nbPages, 'currentPage' => $currentPage, 'get' => $_GET]);
    }



    public function add()
    {
        $errors=array();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cleanPost=[];
            foreach ($_POST as $key => $value) {
                $cleanPost[$key]=trim($value);
            }

            if($_POST){

                if(empty($cleanPost['name'])) {
                    $errors['name'] = 'Veuillez remplir le champ "Nom';
                }
                if(empty($cleanPost['category'])) {
                    $errors['category'] = 'Veuillez remplir le champ "Catégorie"';
                }
                if(empty($cleanPost['price'])) {
                    $errors['price'] = 'Veuillez remplir le champ "Prix"';
                }
                if(empty($cleanPost['description'])) {
                    $errors['description'] = 'Veuillez remplir le champ "Description"';
                }

                if (!preg_match("/^[a-zA-Z0-9 ]+$/", $cleanPost['name'])){
                    $errors['name'] = 'Veuillez remplir le champ "Nom" uniquement avec des caractères alphanumériques';
                }

                if (!preg_match("/^[a-z]+$/", $cleanPost['category'])){
                    $errors['category'] = 'Veuillez remplir le champ "Catégorie" uniquement avec des caractères alphabétiques';
                }

                if (!preg_match("/^[0-9]+$/", $cleanPost['price'])){
                    $errors['price'] = 'Veuillez remplir le champ "Prix" uniquement avec des caractères numériques';
                }

                if ($cleanPost['price']<=0) {
                    $errors['price'] = 'Veuillez remplir le champ "Prix" avec une valeur supérieur à 0';
                }

                if (strlen($cleanPost['name'])>255) {
                    $errors['name'] = 'Veuillez remplir le champ "Nom" avec 255 caractères maximum';
                }
                if (strlen($cleanPost['category'])>255) {
                    $errors['category'] = 'Veuillez remplir le champ "Catégorie" avec 255 caractères maximum';
                }
                if (strlen(strval($cleanPost['price']))>11) {
                    $errors['price'] = 'Veuillez remplir le champ "Prix" avec 11 caractères maximum';
                }
                if (strlen($cleanPost['description'])>5000) {
                    $errors['description'] = 'Veuillez remplir le champ "Description" avec 5000 caractères maximum';
                }
                if ((strlen($cleanPost['review'])>5000) && (!empty($cleanPost['review']))) {
                    $errors['review'] = 'Veuillez remplir le champ "Avis de la boutique" avec 5000 caractères maximum';
                }

                if (!empty($_FILES['picture']['name'])) {
                    $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                    if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
                        $errors['picture'] = 'Veuillez télécharger une image au format ' . implode (', ', self::ALLOWED_EXTENSIONS). ' uniquement';
                    }
                }

                if(0==count($errors)) {
                    $articleManager = new ArticleManager($this->getPdo());

                    $article = new Article();
                    $article->setName($cleanPost['name']);
                    $article->setCategory($cleanPost['category']);
                    $article->setPrice($cleanPost['price']);

                    if (!empty($_FILES['picture']['name'])) {
                        $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' .$extension;
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

        return $this->twig->render('Article/add.html.twig', ['errors' => $errors, 'values' => $_POST
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
}
