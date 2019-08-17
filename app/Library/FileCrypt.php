<?php

namespace App\Library;

/**
 * File encrypt and decrypt
 * 
 * function encrypt and decrypt original created by Arjun
 * 
 * https://arjunphp.com/encrypt-decrypt-files-using-php/
 */
class FileCrypt
{
    /**
     * Encrypt file
     *
     * @param string $file
     * @param string $content
     * @param string $passphrase
     * 
     * @return void
     */
    public function encrypt($file, $content, $passphrase)
    {
        $this->handleCreateFile($file);

        $iv = substr(md5("\x1B\x3C\x58".$passphrase, true), 0, 8);
        $key = substr(md5("\x2D\xFC\xD8".$passphrase, true) . md5("\x2D\xFC\xD9".$passphrase, true), 0, 24);
        $opts = array('iv'=>$iv, 'key'=>$key);
        $fp = fopen($file, 'wb');
        
        stream_filter_append($fp, 'mcrypt.tripledes', STREAM_FILTER_WRITE, $opts); 
        fwrite($fp, $content);

        fclose($fp); 
    }

    /**
     * Decrypt file
     *
     * @param string $file
     * @param string $passphrase
     * 
     * @return mixed
     */
    public function decrypt($file, $passphrase)
    {
        $this->handleCreateFile($file);
        $iv = substr(md5("\x1B\x3C\x58".$passphrase, true), 0, 8);
        $key = substr(md5("\x2D\xFC\xD8".$passphrase, true) .
        
        md5("\x2D\xFC\xD9".$passphrase, true), 0, 24);
        
        $opts = array(
            'iv' => $iv, 
            'key' => $key
        );
        $fp = fopen($file, 'rb');
        stream_filter_append($fp, 'mdecrypt.tripledes', STREAM_FILTER_READ, $opts);

        $content = stream_get_contents($fp);

        return ltrim(rtrim($content));
    }

    /**
     * Handle create file
     *
     * @param string $file
     * 
     * @return void
     */
    public function handleCreateFile($file)
    {
        if (file_exists($file)) {
            return;
        }

        file_put_contents($file, '');
    }
}