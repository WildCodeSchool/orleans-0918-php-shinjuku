<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 12/10/18
 * Time: 09:36
 */

namespace Controller;

use Model\Contact;
use Model\ContactManager;

class ContactController extends AbstractController
{
    public function send()
    {
        return $this->twig->render('contact.html.twig');
    }
}