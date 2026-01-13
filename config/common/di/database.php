<?php

declare(strict_types=1);

use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Mysql\Connection;
use Yiisoft\Db\Mysql\Driver;
use Yiisoft\Definitions\Reference;

/**
 * @var array $params
 */

return [
    ConnectionInterface::class => [
        'class' => Connection::class,
        '__construct()' => [
            Reference::to(Driver::class),
        ],
    ],

    Driver::class => [
        'class' => Driver::class,
        '__construct()' => [
            'dsn' => $params['yiisoft/db-mysql']['dsn'],
            'username' => $params['yiisoft/db-mysql']['username'],
            'password' => $params['yiisoft/db-mysql']['password'],
        ],
    ],
];
