<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Yiisoft\Aliases\Aliases;
use Yiisoft\Db\Migration\Service\MigrationService;
use Yiisoft\Di\ServiceProviderInterface;

use function dirname;

final readonly class ServiceProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return [

            MigrationService::class => [
                'setNewMigrationNamespace()' => ['App\\Migration'],
                'setSourceNamespaces()' => [['App\\Migration']],
                'setSourcePaths()' => [[
                    dirname(__DIR__, 2) . '/vendor/yiisoft/rbac-db/migrations/assignments',
                ]],
            ],
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
