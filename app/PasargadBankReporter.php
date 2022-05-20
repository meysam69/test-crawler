<?php

namespace App;

use App\Service\ServiceInterface;

class PasargadBankReporter extends BankReporter
{
    private string $baseUrl = 'https://ib.bpi.ir';
    private string $username;
    private string $password;
    private string $captcha;
    private string $viewState;

    public function __construct(private ServiceInterface $service, array $config = []) {
        $this->setConfig($config);
    }

    public function setConfig(array $config): void
    {
        if (empty($config)) return;
        foreach(['username', 'password', 'captcha', 'viewState'] as $key) {
            if (isset($config[$key])) {
                $this->{$key} = $config[$key];
            }
        }
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

        preg_match('/<input.+id="__VIEWSTATE" value="(.+?)"\s*\/>/', $content, $matches);

        return [
            'photoBase64' => $this->service->imageToBase64($imageUrl, 'png'),
            '__VIEWSTATE' => $matches[1] ?? '',
        ];
    }

    public function login()
    {
        $fields = [
            'username' => $this->username,
            'password' => $this->password,
            'captchaTxt' => $this->captcha,
            '__EVENTTARGET' => 'btnLogin',
            '__EVENTARGUMENT' => '',
            '__LASTFOCUS' => '',
            '__VIEWSTATE' => $this->viewState,
            '__VIEWSTATEGENERATOR' => '',
            '__EVENTVALIDATION' => '',
            'StatemAgreementent' => 'on',
            'DropDownControl$hdfCurrentSelectItem' => '',
            'ropDownControl$hdfSelectedValue' => '0',
            'DropDownControl$hdfSelectedText' => 'رمز ثابت',
            'DropDownControl$hdfSelectedIndex' => '0',
            'd' => date('H:i:s').' GMT+0430 (Iran Daylight Time)',
            'hdfOtp' => '',
            'hdfToken' => '',
        ];

        $login = $this->service->request($this->baseUrl, 'POST', $fields);
        return $login;
    }

    public function getBankConnector(): BankConnectorInterface
    {
        return new PasargadConnector($this->username, $this->password, $this->captcha);
    }
}