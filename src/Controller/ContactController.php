<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 12/10/18
 * Time: 09:36
 */

namespace Controller;

use \Swift_SmtpTransport;
use \Swift_Mailer;
use \Swift_Message;

class ContactController extends AbstractController
{
    public function send()
    {
        session_start();
        $errors=array();
        $cleanPost=[];
        $mailSent="";
        $mailNotSent="";

        if (isset($_SESSION['mailSent']) && !empty($_SESSION['mailSent'])) {
            $mailSent=$_SESSION['mailSent'];
            unset($_SESSION['mailSent']);
        }

        if (isset($_SESSION['mailNotSent']) && !empty($_SESSION['mailNotSent'])) {
            $mailNotSent=$_SESSION['mailNotSent'];
            unset($_SESSION['mailNotSent']);
        }

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

                if (!preg_match("/^[a-zA-Z0-9.]+\@[a-zA-Z0-9]+\.[a-zA-Z]+/", $cleanPost['email'])){
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

                if(empty($errors)) {

                    try {
                        $transport = (new Swift_SmtpTransport(MAIL_TRANSPORT, MAIL_PORT))
                            ->setUsername(MAIL_USER)
                            ->setPassword(MAIL_PASSWORD)
                            ->setEncryption(MAIL_ENCRYPTION);
                        $mailer = new Swift_Mailer($transport);
                        $message = new Swift_Message();
                        $message->setSubject('Message du formulaire de contact du site shinjuku');
                        $message->setFrom([$cleanPost['email'] => 'sender name']);
                        $message->addTo('shinjuku.projet@gmail.com','recipient name');
                        $message->setBody("Nouveau message de ".$cleanPost['lastName']." ".$cleanPost['firstName']." : ".$cleanPost['message']);
                        $result = $mailer->send($message);
                        $_SESSION['mailSent'] = 'Message envoyé';
                        } catch (Exception $e) {
                            $_SESSION['mailNotSent'] = $e->getMessage();
                        }

                    header('Location:/contact');
                    exit();
                }
            }
        }
        return $this->twig->render('contact.html.twig', ['errors' => $errors, 'values' => $cleanPost, 'mailSent' => $mailSent, 'mailNotSent' => $mailNotSent
        ]);
    }
}