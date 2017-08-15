<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'httpclient' => [
            'class' => 'understeam\httpclient\Client',
            'detectMimeType' => true, // automatically transform request to data according to response Content-Type header
        ],
        'jira' => [
            'class' => 'understeam\jira\Client',
        ],
    ],
];
