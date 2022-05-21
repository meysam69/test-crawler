<?php

namespace App;

use App\Service\ServiceInterface;

class PasargadBankReporter extends BankReporter
{
    private string $baseUrl = 'https://ib.bpi.ir/';
    private array $headers = [
//        'authority: ib.bpi.ir',
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'accept-encoding: gzip, deflate, br',
        'accept-language: en-US,en;q=0.9,fa;q=0.8,la;q=0.7',
        'cache-control: max-age=0',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://ib.bpi.ir',
        'referer: https://ib.bpi.ir/',
        'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="101", "Google Chrome";v="101"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'sec-fetch-dest: document',
        'sec-fetch-mode: navigate',
        'sec-fetch-site: same-origin',
        'sec-fetch-user: ?1',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.67 Safari/537.36',
    ];
    private array $config = [];

    public function __construct(private ServiceInterface $service, array $config = []) {
        $this->setConfig($config);
    }

    public function setConfig(array $config): void
    {
        if (empty($config)) return;
        $this->config = $config;
    }

    public function getEssentialValues(): ?array
    {
        $content = $this->service->request($this->baseUrl);
        $pattern = '/<img\s*src=(ImageHandler.ashx\?[^>]+)>/';
        preg_match($pattern, $content, $matches);
        if (!isset($matches[1])) {
            throw new \Exception('Can not find image src url');
        }
        $imageUrl =  $this->baseUrl.'/'.$matches[1];

        $result = [
            'photoBase64' => $this->service->imageToBase64($imageUrl, 'png'),
        ];

        foreach(['VIEWSTATE', 'VIEWSTATEGENERATOR', 'EVENTVALIDATION'] as $id) {
            $pattern = '/<input.+id="(__'.$id.')" value="(.+?)"\s*\/>/';
            preg_match($pattern, $content, $matches);
            if (isset($matches[1])) {
                $result[$matches[1]] = $matches[2];
            }
        }

        return $result;
    }

    public function login()
    {
        $fields = [
            '__EVENTTARGET' => 'btnLogin',
            '__EVENTARGUMENT' => '',
            '__LASTFOCUS' => '',
            'StatemAgreementent' => 'on',
            'DropDownControl$hdfCurrentSelectItem' => '',
            'DropDownControl$hdfSelectedValue' => '0',
            'DropDownControl$hdfSelectedText' => 'رمز ثابت',
            'DropDownControl$hdfSelectedIndex' => '0',
            'd' => date('H:i:s').' GMT+0430 (Iran Daylight Time)',
            'hdfOtp' => '',
            'hdfToken' => '',
        ];
        $fields = array_merge($fields, $this->config);

        $login = $this->service->request($this->baseUrl, 'POST', $fields, $this->headers);
        return $login;
    }

    public function getBankConnector(): BankConnectorInterface
    {
        return new PasargadConnector($this->username, $this->password, $this->captcha);
    }
}