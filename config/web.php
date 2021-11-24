<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'tabbyld2-client',
    'name' => 'TabbyLD2 Client',
    'defaultRoute' => 'site/index',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'language' => 'ru-RU',
        'request' => [
            'class' => 'app\components\LangRequest',
            // site root directory
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wCSbmxwBdII7e7rKlBjM0EyclBhqcWp4',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'app\components\LangUrlManager',
            'rules' => [
                '/' => 'site/index',
                'login' => 'site/login',
                'upload-csv-table' => 'site/upload-csv-table',
                'upload-json-table' => 'site/upload-json-table',
                'annotate-table' => 'site/annotate-table',
                'select-subject-column' => 'site/select-subject-column',
                'generate-candidate-classes' => 'site/generate-candidate-classes',
                'select-reference-class' => 'site/select-reference-class',
                'annotate-literal-columns' => 'site/annotate-literal-columns',
                'generate-candidate-entities' => 'site/generate-candidate-entities',
                'select-reference-entity' => 'site/select-reference-entity',
                'generate-candidate-properties' => 'site/generate-candidate-properties',
                'select-reference-property' => 'site/select-reference-property',
                'augment-knowledge-base' => 'site/augment-knowledge-base',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;