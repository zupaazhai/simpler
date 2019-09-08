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
        'password',
        'created_at',
        'updated_at'
    );

    public function __construct()
    {
        $this->fileCrypt = new FileCrypt;

        $this->userFile = config('USER_FILE_KEY');
        $this->passpharse = config('USER_PASSPHARSE');

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
        $user['created_at'] = time();
        $user['updated_at'] = time();
        
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

        $users = $this->findAll();

        $filterMeOut = array_filter($users, function ($current) use ($findValue, $user) {
            return ($current['id'] != $findValue);
        });

        $usernameExists = array_filter($filterMeOut, function ($current) use ($data) {
            return $current['username'] == $data['username'];
        });

        if (!empty($usernameExists)) {
            throw new UserExistsException();
        }

        $emailExists = array_filter($filterMeOut, function ($current) use ($data) {
            return $current['email'] == $data['email'];
        });

        if (!empty($emailExists)) {
            throw new UserEmailExistsException();
        }

        unset($data['id']);

        if (!empty($data['password'])) {
            $data['password'] = $this->password($data['password']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        unset($data['confirm_password']);

        $data['updated_at'] = time();

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

        if (empty($user)) {
            return false;
        }

        unset($user['password']);

        return $user;
    }
    
    /**
     * Find all user
     *
     * @return array
     */
    public function findAll($sort = 'created_at', $direction = 'asc')
    {
        $users = $this->readFile();
        $columns = array();

        foreach ($users as $user) {
            $columns[] = $user[$sort];
        }
                
        array_multisort($columns, $direction == 'asc' ? SORT_ASC : SORT_DESC, $users);

        return $users;
    }

    /**
     * Count all user
     *
     * @return int
     */
    public function count()
    {
        $users = $this->findAll();

        return count($users);
    }

    /**
     * Delete user
     *
     * @param string $findKey
     * @param mixed $findValue
     * 
     * @return \Flight
     */
    public function delete($findKey, $findValue)
    {
        $saveUsers = array();
        $users = $this->findAll();

        foreach ($users as $user) {
            if ($user[$findKey] == $findValue) {
                continue;
            }

            $saveUsers[] = $user;
        }

        $this->storeFile($saveUsers);

        return back();
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
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        unset($_SESSION[$this->sessionKey]);
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

    public static function auth()
    {
        $user  = new self;
        return $user->current();
    }
}