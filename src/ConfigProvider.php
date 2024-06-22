<?php

declare(strict_types=1);

namespace HPlus\Helper;
use HPlus\Helper\Kernel\Model\ModelUpdateVisitor;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'commands' => [
            ],
            'dependencies'=>[
                Hyperf\Database\Commands\Ast\ModelUpdateVisitor::class => ModelUpdateVisitor::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ]
        ];
    }
}
