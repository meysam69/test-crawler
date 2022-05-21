<?php

namespace App\Service;

use App\Service\ServiceInterface;

class HttpService implements ServiceInterface
{
    private string $cookieFile;

    public function __construct()
    {
        $this->cookieFile = CACHE_PATH. DS.'cookies.txt';
    }

    public function request(string $url, string $method = 'GET', array $fields = [],
                            array $headers = [], array $cookies = []): string|null
    {
        $options = [
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'], // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYHOST => false,     // Disabled SSL Cert checks,
            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks,
            CURLOPT_COOKIEFILE => $this->cookieFile,
            CURLOPT_COOKIEJAR => $this->cookieFile,
            CURLOPT_VERBOSE => true,
            CURLINFO_HEADER_OUT => true,
        ];

        if (strtolower($method) === 'post') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = http_build_query($fields);
//            $headers[] = 'content-length: '.strlen($options[CURLOPT_POSTFIELDS]);
        }

        if (count($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        $sentHeaders = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        curl_close( $ch );

        return $content;
    }

    public function imageToBase64(string $imageUrl, string $ext): ?string{
        $imageData = base64_encode($this->request($imageUrl));
        $mime_types = array(
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'odt' => 'application/vnd.oasis.opendocument.text ',
            'docx'	=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'gif' => 'image/gif',
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'bmp' => 'image/bmp'
        );

        if (array_key_exists($ext, $mime_types)) {
            $a = $mime_types[$ext];
        }
        return 'data: '.$a.';base64,'.$imageData;
    }
}