<?php

declare(strict_types=1);

namespace HPlus\Helper;


class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'commands' => [
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
