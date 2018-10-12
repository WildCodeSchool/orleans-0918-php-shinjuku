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

/**
 * Class ArticleController
 *
 */
class ArticleController extends AbstractController
{

    const ALLOWED_EXTENSIONS=['png', 'jpg', 'jpeg'];

    /**
     * Display article creation page
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function add()
    {
        $errors=array();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            foreach ($_POST as $key => $value) {
                $_POST[$key]=trim($value);
            }

            if($_POST){

                if(empty($_POST['name'])) {
                    $errors['name'] = 'Veuillez remplir le champ "Nom';
                }
                if(empty($_POST['category'])) {
                    $errors['category'] = 'Veuillez remplir le champ "Catégorie"';
                }
                if(empty($_POST['price'])) {
                    $errors['price'] = 'Veuillez remplir le champ "Prix"';
                }
                if(empty($_POST['description'])) {
                    $errors['description'] = 'Veuillez remplir le champ "Description"';
                }

                if (!preg_match("/^[a-zA-Z0-9 ]+$/", $_POST['name'])){
                    $errors['name'] = 'Veuillez remplir le champ "Nom" uniquement avec des caractères alphanumériques';
                }

                if (!preg_match("/^[a-z]+$/", $_POST['category'])){
                    $errors['category'] = 'Veuillez remplir le champ "Catégorie" uniquement avec des caractères alphabétiques';
                }

                if (!preg_match("/^[0-9]+$/", $_POST['price'])){
                    $errors['price'] = 'Veuillez remplir le champ "Prix" uniquement avec des caractères numériques';
                }

                if (strlen($_POST['name'])>255){
                    $errors['name'] = 'Veuillez remplir le champ "Nom" avec 255 caractères maximum';
                }
                if (strlen($_POST['category'])>255){
                    $errors['category'] = 'Veuillez remplir le champ "Catégorie" avec 255 caractères maximum';
                }
                if (strlen(strval($_POST['price']))>11){
                    $errors['price'] = 'Veuillez remplir le champ "Prix" avec 11 caractères maximum';
                }
                if (strlen($_POST['description'])>5000){
                    $errors['description'] = 'Veuillez remplir le champ "Description" avec 5000 caractères maximum';
                }
                if ((strlen($_POST['review'])>5000) && (!empty($_POST['review']))){
                    $errors['review'] = 'Veuillez remplir le champ "Avis de la boutique" avec 5000 caractères maximum';
                }

                if (!empty($_FILES['picture']['name'])) {
                    $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                    if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
                        $errors['picture'] = 'Veuillez télécharger une image au format jpg ou png uniquement';
                    }
                }

                if(0==count($errors)) {
                    $articleManager = new ArticleManager($this->getPdo());

                    $article = new Article();
                    $article->setName($_POST['name']);
                    $article->setCategory($_POST['category']);
                    $article->setPrice($_POST['price']);

                    if (!empty($_FILES['picture']['name'])) {
                        $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' .$extension;
                        $uploadDir = __DIR__ . '/../../public/assets/images/upload/';
                        $uploadFile = $uploadDir . $filename;
                        move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile);
                        $article->setPicture($uploadFile);
                    }

                    $article->setDescription($_POST['description']);
                    $article->setReview($_POST['review']);

                    if (!empty($_POST['highlight'])) {
                        $article->setHighlight($_POST['highlight']);
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
}
