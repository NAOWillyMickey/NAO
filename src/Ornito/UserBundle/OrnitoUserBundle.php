<?php

namespace Ornito\UserBundle;

use FOS\UserBundle\FOSUserBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OrnitoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
