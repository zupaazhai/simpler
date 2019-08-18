<?php

namespace App\Controller;

use App\Exception\UserEmailExistsException;
use App\Exception\UserExistsException;
use App\Exception\UserNotFoundException;
use App\Model\User;
use Flight;

class UserController extends Controller
{
    protected $user;

    protected $logedInTo;

    protected $loggedOutTo;

    public function __construct()
    {
        $this->user = new User;
        $this->logedInTo = CALLBACK_REDIRECT;
        $this->loggedOutTo = FALLBACK_REDIRECT;
    }

    /**
     * Login form
     *
     * @return \Flight
     */
    public function loginForm()
    {
        $this->allowGuest();
        $isNoBody = $this->user->noBodyHere();
        $view = $isNoBody ? 'auth.register' : 'auth.login';
        view($view, array('title' => $isNoBody ? 'Register' : 'Login'), 'content');

        return layout('auth', array(), '');
    }

    /**
     * Check login
     *
     * @return \Flight
     */
    public function login()
    {
        $req = post();
        $errors = array();

        if (empty($req['username'])) {
            $errors['username'] = 'Username is required';
        }

        if (empty($req['password'])) {
            $errors['password'] = 'Password is required';
        }

        flash('errors', $errors);

        try {
            $user = $this->user->checkUser($req['username'], $req['password']);
        } catch (UserNotFoundException $e) {

            flash('errors', array('username' => $e->getMessage()));
            return back();
        }
        
        unbind('errors');
        return $this->redirectAfterLogedIn($user);
    }

    /**
     * Create user
     *
     * @return \Flight
     */
    public function create()
    {
        $req = post();
        $errors = array();

        if (empty($req['username'])) {
            $errors['username'] = 'User is required';
        }

        if (empty($req['password'])) {
            $errors['password'] = 'Password is required';
        }

        if (empty($req['confirm_password'])) {
            $errors['confirm_password'] = 'Confirm password is required';
        }
        
        if (
            (!empty($req['password']) && !empty($req['confirm_password'])) && 
            ($req['password'] !== $req['confirm_password'])
        ) {
            $errors['confirm_password'] = 'Password and Confirm password not match';
        }

        if (empty($req['email'])) {
            $errors['email'] = 'Email is required';
        }

        if (!empty($errors)) {
            flash('errors', $errors);
            
            unset($req['password']);
            unset($req['confirm_password']);

            return back($req);
        }

        try {
            $this->user->create($req);
        
        } catch (UserExistsException $e) {
            
            flash('errors', array(
                'username' => $e->getMessage()
            ));

            return back($req);
        } catch (UserEmailExistsException $e) {

            flash('errors', array(
                'email' => $e->getMessage()
            ));

            return back($req);
        }

        unbind('errors');
        return $this->redirectAfterCreated();
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        $this->user->logout();

        return $this->redirectAfterLoggedOut();
    }

    /**
     * Redirect after created
     *
     * @return \Flight
     */
    public function redirectAfterCreated()
    {
        return Flight::redirect($this->logedInTo);
    }

    /**
     * Redirect after logged in
     *
     * @return \Flight
     */
    public function redirectAfterLogedIn($user)
    {
        $this->user->setUserSession($user);

        return Flight::redirect($this->logedInTo);
    }

    /**
     * Redirect after logged out
     *
     * @return \Flight
     */
    public function redirectAfterLoggedOut()
    {
        return Flight::redirect($this->loggedOutTo);
    }

    /**
     * Check in logged in
     * 
     * @return boolean
     */
    public static function loggedIn()
    {
        $userModel = new User;
        $user = $userModel->current();

        return !empty($user);
    }
}