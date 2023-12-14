<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionService
{
    public function __construct(private readonly RequestStack $stack)
    {
    }

    public function generateUniqueIdentifier()
    {
        $uniqIdentifier = uniqid('user', true);
        $session = $this->stack->getSession();
        $session->set( 'user_identifier', $uniqIdentifier );
    }
}