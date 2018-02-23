<?php

namespace Ornito\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OrnitoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
