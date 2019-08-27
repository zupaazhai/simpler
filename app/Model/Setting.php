<?php

namespace App\Model;

use App\Enum\SettingEnum;

class Setting
{
    protected $basePath;

    public function __construct()
    {
        $this->basePath = config('DATA_DIR') . 'setting.config';
        $this->handleCreateFile();
    }
    
    /**
     * Update setting
     *
     * @param array $setting
     * 
     * @return bool
     */
    public function update($setting = array())
    {
        $savedSetting = $this->findAll();
        $savedSetting = array_merge($savedSetting, $setting);

        return $this->write($savedSetting);
    }

    /**
     * Find all setting
     *
     * @return array
     */
    public function findAll()
    {
        $setting = file_get_contents($this->basePath);

        return json_decode($setting, true);
    }

    /**
     * Write setting
     *
     * @param array $setting
     * 
     * @return void
     */
    public function write($setting)
    {
        $defaultValue = json_encode($setting);

        file_put_contents($this->basePath, $defaultValue);
    }

    /**
     * Create when file is empty
     *
     * @return void
     */
    private function handleCreateFile()
    {
        if (file_exists($this->basePath)) {
            return;
        }

        $this->write(SettingEnum::defaultSetting());
    }
}