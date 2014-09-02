<?php

namespace G\Io\Storage;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class Provider implements ServiceProviderInterface, StackIface
{
    private $storage;

    public function __construct(StorageIface $storage)
    {
        $this->storage = $storage;
    }

    public function register(Container $app)
    {
        $app['gdb.get'] = $app->protect(function ($key) {
            return $this->storage->get($key);
        });

        $app['gdb.post'] = $app->protect(function ($key, $value) {
            return $this->storage->post($key, $value);
        });
    }
}