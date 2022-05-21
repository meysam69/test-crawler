<?php

namespace App\Service;

interface ServiceInterface
{
    public function request(string $url, string $method = 'GET', array $fields = [],
                            array $headers = [], array $cookies = []): string|null;

    public function imageToBase64(string $imageUrl, string $ext): ?string;
}