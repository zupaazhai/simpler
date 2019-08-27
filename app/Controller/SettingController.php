<?php

namespace App\Controller;

use App\Model\Setting;
class SettingController
{
    protected $setting;

    public function __construct()
    {
        $this->setting = new Setting;    
    }

    /**
     * Setting index
     *
     * @return \Flight
     */
    public function index()
    {
        $data = array(
            'title' => 'Setting',
            'settings' => $this->setting->findAll()
        );

        view('setting.index', $data, 'content');

        return layout('app');
    } 

    /**
     * Update setting
     *
     * @return \Flight
     */
    public function update()
    {
        $req = put();

        $this->setting->update($req);

        flash('status', array(
            'message' => 'Save setting success',
            'status' => 'success'
        ));

        return back();
    }
}