<?php

namespace App\Mercure;

use Symfony\Component\Mercure\Jwt\TokenProviderInterface;
use Symfony\Component\Mercure\Jwt\LcobucciFactory;

final class TokenProvider implements TokenProviderInterface
{
    private $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    public function getJwt(): string
    {
        return (new LcobucciFactory($this->secret))->create(['*'], ['*']);
    }
}