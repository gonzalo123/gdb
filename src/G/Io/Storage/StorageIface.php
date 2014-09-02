<?php

namespace G\Io\Storage;

interface StorageIface
{
    public function get($key);

    public function post($key, $value);
}