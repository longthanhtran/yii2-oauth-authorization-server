<?php

namespace longthanhtran\oauth2\core\models;

use League\oauth2\Server\Entities\RefreshTokenEntityInterface;
use League\oauth2\Server\Entities\Traits\EntityTrait;
use League\oauth2\Server\Entities\Traits\RefreshTokenTrait;


class RefreshToken implements RefreshTokenEntityInterface
{
    use EntityTrait, RefreshTokenTrait;
}