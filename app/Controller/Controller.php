<?php

namespace App\Controller;

use App\Model\User;
use Flight;

class Controller
{
    public $fallbackRedirect = FALLBACK_REDIRECT;

    public $callbackRedirect = CALLBACK_REDIRECT;

    /**
     * Allow guest and redirect to callback when logged in
     *
     * @return void
     */
    public function allowGuest() {
        $user = new User;
        $current = $user->current(); 

        if (!empty($current)) {
            return Flight::redirect($this->callbackRedirect);
        }

        return;
    }
}