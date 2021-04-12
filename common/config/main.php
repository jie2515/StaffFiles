<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        "@mdm/admin" => "@vendor/mdmsoft/yii2-admin",

    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        "authManager" => [
            "class" => 'yii\rbac\DbManager',
            'assignmentTable'=> 'sf_auth_assignment',
            'itemChildTable'=> 'sf_auth_item_child',
            'itemTable'=> 'sf_auth_item',
            'ruleTable'=> 'sf_auth_rule',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'helper' => [
            'class' => 'common\components\Helper',
              'property' => '123',
        ]
    ],
];
