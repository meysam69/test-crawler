<?php

namespace App;

use App\Service\ServiceInterface;

class PasargadBankReporter extends BankReporter
{
    private string $baseUrl = 'https://ib.bpi.ir';
    private string $username;
    private string $password;
    private string $captcha;

    public function __construct(private ServiceInterface $service, array $config = []) {
        $this->setConfig($config);
    }

    public function setConfig(array $config): void
    {
        if (empty($config)) return;
        foreach(['username', 'password', 'captcha'] as $key) {
            if (isset($config[$key])) {
                $this->{$key} = $config[$key];
            }
        }
    }

    public function getCaptchaPhoto(): ?string
    {
        $content = $this->service->request($this->baseUrl);
        $pattern = '/<img\s*src=(ImageHandler.ashx\?[^>]+)>/';
        preg_match($pattern, $content, $matches);
        if (!isset($matches[1])) {
            throw new \Exception('Can not find image src url');
        }
        $imageUrl =  $this->baseUrl.'/'.$matches[1];
        return $this->service->imageToBase64($imageUrl, 'png');
    }

    public function login()
    {
        $fields = [
            'username' => $this->username,
            'password' => $this->password,
            'captchaTxt' => $this->captcha,
        ];
        $login = $this->service->request($this->baseUrl, 'POST', $fields);
        return $login;
    }

    public function getBankConnector(): BankConnectorInterface
    {
        return new PasargadConnector($this->username, $this->password, $this->captcha);
    }
}