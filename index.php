<?php
require 'vendor/autoload.php';

$app = new \tests\App();

$app->run(function (\net\context\Context $context) {
    $context->statusCode(200);
    $context->response()->cookie()->setCookie("key", "value", time() + 60);
    $context->writeString("{\"name\":\"bzeron\"}");
});

$app->context->response()->Send();