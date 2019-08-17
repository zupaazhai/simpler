<?php

namespace App\Model;

use App\Exception\UserExistsException;
use App\Library\FileCrypt;

class User
{
    protected $userFile;

    protected $fileCrypt;

    protected $passpharse;

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

        $isExist = $this->findOne('username', $user['username']);
        $isExist = !empty($isExist);

        if ($isExist) {
            throw new UserExistsException();
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
            return $user[$findKey] == $findValue;
        });

        return empty($users[0]) ? false : $users[0];
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