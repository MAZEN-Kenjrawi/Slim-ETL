<?php

namespace ETL;

use DI\Bridge\Slim\App as DiBridge;
use DI\ContainerBuilder;

class App extends DIBridge
{
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            'settings.displayErrorDetails' => true,
        ]);

        $builder->addDefinitions(__DIR__.DIRECTORY_SEPARATOR.'container.php');
    }
}
