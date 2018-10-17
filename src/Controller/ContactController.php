<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 12/10/18
 * Time: 09:36
 */

namespace Controller;

class ContactController extends AbstractController
{
    public function send()
    {
        $errors=array();
        $cleanPost=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            foreach ($_POST as $key => $value) {
                $cleanPost[$key]=trim($value);
            }

            if($_POST){

                if(empty($cleanPost['lastName'])) {
                    $errors['lastName'] = 'Veuillez remplir le champ "Nom';
                }
                if(empty($cleanPost['firstName'])) {
                    $errors['firstName'] = 'Veuillez remplir le champ "Prénom"';
                }
                if(empty($cleanPost['email'])) {
                    $errors['email'] = 'Veuillez remplir le champ "E-mail"';
                }
                if(empty($cleanPost['message'])) {
                    $errors['message'] = 'Veuillez remplir le champ "Message"';
                }

                if (!preg_match("/^[a-zA-Z ]+$/", $cleanPost['lastName'])){
                    $errors['lastName'] = 'Veuillez remplir le champ "Nom" uniquement avec des caractères alphabétiques';
                }

                if (!preg_match("/^[a-zA-Z ]+$/", $cleanPost['firstName'])){
                    $errors['firstName'] = 'Veuillez remplir le champ "Prénom" uniquement avec des caractères alphabétiques';
                }

                if (!preg_match("/^[a-zA-Z0-9]+\@[a-zA-Z0-9]+\.[a-zA-Z]+/", $cleanPost['email'])){
                    $errors['email'] = 'Veuillez remplir le champ "E-mail" avec une adresse électronique valide';
                }

                if (strlen($cleanPost['lastName'])>50) {
                    $errors['lastName'] = 'Veuillez remplir le champ "Nom" avec 50 caractères maximum';
                }
                if (strlen($cleanPost['firstName'])>50) {
                    $errors['firstName'] = 'Veuillez remplir le champ "Prénom" avec 50 caractères maximum';
                }

                if (strlen($cleanPost['email'])>50) {
                    $errors['email'] = 'Veuillez remplir le champ "E-mail" avec 50 caractères maximum';
                }

                if (strlen($cleanPost['message'])>5000) {
                    $errors['message'] = 'Veuillez remplir le champ "Description" avec 5000 caractères maximum';
                }

                if(0==count($errors)) {
                    header('Location:/contact');
                    exit();
                }
            }
        }
        return $this->twig->render('contact.html.twig', ['errors' => $errors, 'values' => $cleanPost
        ]);
    }
}