<?php

namespace App\Providers;

protected function map()
{
    $this->app['view']->addNamespace('custom', base_path('resources/views/custom')); 
}

?>