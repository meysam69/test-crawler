<?php

require 'vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', __DIR__ . DS);
define('APP_PATH', BASE_PATH . 'app');
define('CACHE_PATH', BASE_PATH . 'storage'.DS.'cache');


use App\PasargadBankReporter;
use App\Service\HttpService;

$step = $_REQUEST['step'] ?? '1';
$username = $_REQUEST['username'] ?? null;
$password = $_REQUEST['password'] ?? null;
$captcha = $_REQUEST['captcha'] ?? null;
$captchaImg = null;
$code = $_REQUEST['code'] ?? null;
$balance = 0;

$reporter  = new PasargadBankReporter(new HttpService());

if ( $step === '1') {
    $captchaImg = $reporter->getCaptchaPhoto();
}
else{
    if ($step === '2') {
        $login = $reporter->login();
        echo $login;
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Crawler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
    <div class="container bg-light p-5">
        <div class="row">
            <div class="col">
                <h1>Get Pasargad Bank's Balance</h1>
            </div>
        </div>
        <?php include APP_PATH.DS.'Pages'.DS.'step'.$step.'.php'; ?>
    </div>
</body>
</html>
