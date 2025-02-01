<?php

namespace App\Trait;

trait TenantConnection
{
    public function __construct(...$args)
    {
        $this->setConnection('tenant');
        parent::__construct($args);

    }
}
