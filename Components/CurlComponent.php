<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Component;

use isv\Component\ISVComponent;
use isv\Component\ISVComponentInterface;
use isv\IS;

/**
 * Description of CurlComponent
 *
 * @author denis
 */
class CurlComponent extends ISVComponent implements ISVComponentInterface {

    private $ch;
    private $url;
    private $html;

    public function init() {
        
    }

    public function set($k, $v) {
        $this->$k = $v;
        return $this;
    }

    public function get($k) {
        return $this->$k;
    }

    public function load($data = []) {
        if (count($data))
            foreach ($data as $k => $v)
                $this->$k = $v;
        return $this;
    }

    public function curlInit() {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    public function setPost($post = false) {
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
    }

    public function setCookieFile($path = '') {
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, ROOTDIR . $path);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, ROOTDIR . $path);
    }
    
    public function setCookieForwarding($path = '') {
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, ROOTDIR . $path);
    }
    
    public function setCookieReceipt($path = '') {
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, ROOTDIR . $path);
    }

    public function setClient($agent) {
//        /"Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4"
        curl_setopt($this->ch, CURLOPT_USERAGENT, $agent);
    }

    public function getHtml() {
        return curl_exec($this->ch);
    }

    public function getCookies() {
        return curl_getinfo($this->ch, CURLINFO_COOKIELIST);
    }

    public function close() {
        curl_close($this->ch);
    }
}
