<?php

namespace App\Controller\Api;

class EmptyController
{
    public function __invoke($data) 
    {
        return $data;
    }
}