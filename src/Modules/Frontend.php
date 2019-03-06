<?php

namespace KikCMS\Modules;

use KikCMS\Plugins\FrontendNotFoundPlugin;
use KikCMS\Plugins\ParamConverterPlugin;
use Phalcon\Events\Manager;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Frontend implements ModuleDefinitionInterface
{
    protected $defaultNamespace = "KikCMS\\Controllers";

    /**
     * @inheritdoc
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        // nothing else needed
    }

    /**
     * @inheritdoc
     */
    public function registerServices(DiInterface $di)
    {
        $defaultNameSpace = $this->defaultNamespace;

        $di->set("dispatcher", function () use ($defaultNameSpace) {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace($defaultNameSpace);

            $eventsManager = new Manager;
            $eventsManager->attach('dispatch:beforeException', new FrontendNotFoundPlugin);
            $eventsManager->attach("dispatch:beforeDispatchLoop", new ParamConverterPlugin);

            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });
    }
}