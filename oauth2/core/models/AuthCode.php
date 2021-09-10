<?php

namespace longthanhtran\oauth2\core\models;

use League\oauth2\Server\Entities\AuthCodeEntityInterface;
use League\oauth2\Server\Entities\Traits\AuthCodeTrait;
use League\oauth2\Server\Entities\Traits\EntityTrait;
use League\oauth2\Server\Entities\Traits\TokenEntityTrait;

class AuthCode implements AuthCodeEntityInterface
{
    use AuthCodeTrait, EntityTrait, TokenEntityTrait;
}