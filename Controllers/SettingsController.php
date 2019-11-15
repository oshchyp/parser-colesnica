<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller;

use Component\CurlComponent;
use isv\Controller\ControllerBase;
use isv\IS;
use isv\View\ViewBase;

/**
 * Description of SettingsController
 *
 * @author denis
 */
class SettingsController extends ControllerBase {

    private $configComp;
    private $mainComp;

    public function init() {
        $this->configComp = new \Component\ConfigComponent();
        $this->mainComp = new \Component\MainComponent();
    }

    public function indexAction() {
        $this->redirect('/404');
    }

    public function doc_inAction() {

        if (IS::app()->request()->postData()) {
            $data = $this->mainComp->arrayFormateKeyVal(IS::app()->request()->postData('key'), IS::app()->request()->postData('value'));
            $this->configComp->set('key', 'doc_in_' . $this->params('id'))->save($data);
            IS::app()->session()->setFlash('success', 'Успешно');
            $this->redirect('/settings/doc_in/' . $this->params('id'));
        }
        $cats = $this->configComp->set('key', 'shabs')->get();
        $cat_name = isset($cats[$this->params('id')]) ? '"' . $cats[$this->params('id')] . '"' : '';
        return new ViewBase([
            'cat' => $this->params('id'),
            'cat_name' => $cat_name
        ]);
    }

    public function doc_outAction() {

        if (IS::app()->request()->postData()) {
            $data = $this->mainComp->arrayFormateKeyVal(IS::app()->request()->postData('key'), IS::app()->request()->postData('value'));
            $this->configComp->set('key', 'doc_out_' . $this->params('id'))->save($data);
            IS::app()->session()->setFlash('success', 'Успешно');
            $this->redirect('/settings/doc_out/' . $this->params('id'));
        }

        $cats = $this->configComp->set('key', 'cat')->get();
        $cat_name = isset($cats[$this->params('id')]) ? '"' . $cats[$this->params('id')] . '"' : '';

        return new ViewBase([
            'cat' => $this->params('id'),
            'cat_name' => $cat_name
        ]);
    }

    public function doc_fieldsAction() {
        $this->configComp->set('key', 'doc_fields');

        if (IS::app()->request()->postData()) {
            $data = $this->mainComp->arrayFormateKeyVal(IS::app()->request()->postData('key'), IS::app()->request()->postData('value'));
            $this->configComp->save($data);
            IS::app()->session()->setFlash('success', 'Успешно');
            $this->redirect('/settings/doc_fields');
        }

        $fields = $this->configComp->get();

        return new ViewBase([
            'fields' => $fields
        ]);
    }

    public function imgAction() {

        $cat = $this->configComp->set('key', 'cat')->get();
        $set = $this->configComp->set('key', 'img')->get();

        if (IS::app()->request()->postData()) {
            $data = IS::app()->request()->postData();
            $fileComp = new \Component\UploadFileComponent();
            $fileComp->load([
                'exp' => false,
                'dir' => ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/images',
                'name' => 'float'
            ]);
            if ($fileComp->save()) {
                if (isset($data['width']) && (float) $data['width'] > 0) {
                    $imgComp = new \Component\ImgComponent();
                    $imgComp->get('obj')->load(ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/images/float' . $fileComp->get('exp'));
                    $imgComp->get('obj')->resampleToWidth((float) $data['width']);
                    $r = $imgComp->get('obj')->save(ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/images/float' . $fileComp->get('exp'));
                    //\isv\Developer\Developer::dump($imgComp -> get('obj'),1);
                }

                $data['set']['img'] = 'float' . $fileComp->get('exp');
            }
            $this->configComp->save($data['set']);
            IS::app()->session()->setFlash('success', 'Успешно');
            $this->redirect('/settings/img');
        }


        return new ViewBase([
            'set' => $set,
            'cat' => $cat
        ]);
    }

    public function catAction() {

        if (IS::app()->request()->postData()) {
            $data = $this->mainComp->arrayFormateKeyVal(IS::app()->request()->postData('key'), IS::app()->request()->postData('value'));
            $this->configComp->set('key', 'cat')->save($data);
            IS::app()->session()->setFlash('success', 'Успешно');
            $this->redirect('/settings/cat');
        }

        return new ViewBase([
        ]);
    }

    public function shabsAction() {
        if (IS::app()->request()->postData()) {
            $data = $this->mainComp->arrayFormateKeyVal(IS::app()->request()->postData('key'), IS::app()->request()->postData('value'));
            $this->configComp->set('key', 'shabs')->save($data);
            IS::app()->session()->setFlash('success', 'Успешно');
            $this->redirect('/settings/shabs');
        }

        return new ViewBase([
        ]);
    }

    public function loginAction() {
        $log = $this->configComp->set('key', 'login')->set('structure', ['login', 'password'])->get();
        $curlObj = new CurlComponent();

        if (IS::app()->request()->postData()) {
            $this->configComp->save(IS::app()->request()->postData());
            $curlObj->set('url', 'https://b2b.euro-diski.ru/auth.php')->curlInit();
            $curlObj->setPost(IS::app()->request()->postData());
            $curlObj->setCookieFile('/' . IS::app()->getConfig('config')['publicDir'] . '/files/cookie.txt');
            $curlObj->getHtml();
            $curlObj->close();
            $this->redirect('/settings/login');
        }

        return new ViewBase([
            'log' => $log
        ]);
    }

    public function logoutAction() {
        file_put_contents(ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/cookie.txt', '');
        $this->redirect('/settings/login');
    }

    public function test_authAction() {
        \isv\Developer\Developer::dump($this->mainComp->isAuth());
        die();
    }

    public function convert_nameAction() {
        $cat = $this->configComp->set('key', 'cat')->get();
        $convertName = $this->configComp->set('key', 'convert_name')->get();
        if (IS::app()->request()->postData()) {
            $this->configComp->save(IS::app()->request()->postData());
            IS::app()->session()->setFlash('success', 'Успешно');
            $this->redirect('/settings/convert_name');
        }
        $fields =  $this->configComp->set('key', 'doc_fields')->get();
        return new ViewBase([
            'cat' => $cat,
            'convertName' => $convertName,
            'fields' => $fields
        ]);
    }

//put your code here
}
