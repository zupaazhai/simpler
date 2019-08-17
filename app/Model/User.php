<?php

namespace App\Model;

use App\Exception\UserEmailExistsException;
use App\Exception\UserExistsException;
use App\Exception\UserNotFoundException;
use App\Library\FileCrypt;

class User
{
    protected $userFile;

    protected $fileCrypt;

    protected $passpharse;

    protected $sessionKey;

    public $fillable = array(
        'username',
        'email',
        'password'
    );

    public function __construct()
    {
        $this->fileCrypt = new FileCrypt;

        $this->userFile = USER_FILE_KEY;
        $this->passpharse = USER_PASSPHARSE;

        $this->sessionKey = 'user';
    }

    /**
     * Create user
     *
     * @param array $user
     * 
     * @return array
     */
    public function create($user = array())
    {
        $user = array_only($user, $this->fillable);

        $foundUser = $this->findOne('username', $user['username']);
        $isExist = !empty($foundUser);

        if ($isExist) {
            throw new UserExistsException();
        }

        $foundUser = $this->findOne('email', $user['email']);

        if ($foundUser['email'] == $user['email']) {
            throw new UserEmailExistsException();
        }

        $users = $this->readFile();
        $user['id'] = md5(time() . rand(1, 99));
        $user['password'] = $this->password($user['password']);
        
        $users[] = $user;
        $this->storeFile($users);

        unset($user['password']);

        return $user;
    }

    /**
     * Edit user
     *
     * @param string $findKey
     * @param mixed $findValue
     * @param array $data
     * 
     * @return mixed
     */
    public function edit($findKey = 'id', $findValue, $data = array())
    {
        $user = $this->findOne($findKey, $findValue);

        if (!$user) {
            return false;
        }

        unset($data['id']);

        if (!empty($data['password'])) {
            $data['password'] = $this->password($data['password']);
        }

        $user = array_merge($user, $data);
        $userIndex = $this->findIndex($findKey, $findValue);

        if ($userIndex == -1) {
            return false;
        }

        $users = $this->findAll();
        $users[$userIndex] = $user;

        $this->storeFile($users);

        unset($user['password']);

        return $user;
    }

    /**
     * Find user
     *
     * @param string $findKey
     * @param mixed $findValue
     * 
     * @return array
     */
    public function findOne($findKey, $findValue)
    {
        $users = $this->findAll();
        $users = array_filter($users, function ($user) use ($findKey, $findValue) {
            return !empty($user[$findKey]) && $user[$findKey] == $findValue;
        });

        $user = array_shift($users);

        return empty($user) ? false : $user;
    }
    
    /**
     * Find all user
     *
     * @return array
     */
    public function findAll()
    {
        return $this->readFile();
    }

    /**
     * Delete all user
     *
     * @return array
     */
    public function deleteAll()
    {
        return $this->storeFile(array());
    }

    /**
     * Check in any user registered
     *
     * @return boolean
     */
    public function noBodyHere()
    {
        $users = $this->findAll();

        return empty($users);
    }
    
    /**
     * Check user
     *
     * @param string $username
     * @param string $password
     * 
     * @return boolean
     */
    public function checkUser($username, $password)
    {
        $users = $this->findAll();
        $password = $this->password($password);

        $user = array_filter($users, function ($user) use ($username, $password) {
            return $user['username'] == $username && $user['password'] == $password;
        });

        $user = array_shift($user);

        if (empty($user)) {
            throw new UserNotFoundException();
        }
        
        return $user;
    }

    /**
     * Get user session
     *
     * @param array $user
     * 
     * @return void
     */
    public function setUserSession($user)
    {
        $_SESSION[$this->sessionKey] = json_encode($user);
    }

    /**
     * Get user session
     *
     * @return array
     */
    public function current()
    {
        if (empty($_SESSION[$this->sessionKey])) {
            return false;
        }
        
        return json_decode($_SESSION[$this->sessionKey], true);
    }

    /**
     * Find index of user
     *
     * @param string $findKey
     * @param mixed $findValue
     * 
     * @return array
     */
    private function findIndex($findKey, $findValue)
    {
        $users = $this->findAll();

        foreach ($users as $index => $user) {
            if ($user[$findKey] == $findValue) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * Save all to file
     *
     * @param array $data
     * 
     * @return void
     */
    private function storeFile($data = array())
    {
        $data = json_encode($data);

        $this->fileCrypt->encrypt(
            $this->userFile,
            $data,
            $this->passpharse
        );
    }

    /**
     * Read user file
     *
     * @return array
     */
    private function readFile()
    {
        $users = $this->fileCrypt->decrypt($this->userFile, $this->passpharse);
        $users = json_decode($users, true);

        return empty($users) ? array() : $users;
    }

    /**
     * Create password hash 
     *
     * @param string $password
     * 
     * @return string
     */
    private function password($password)
    {
        return sha1($password);
    }
}