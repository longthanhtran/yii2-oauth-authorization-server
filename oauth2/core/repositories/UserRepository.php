<?php

namespace Longthanhtran\OAuth2\core\repositories;

use app\models\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @param string $username
     * @param string $password
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @return User|UserEntityInterface|null
     */
    public function getUserEntityByUserCredentials($username,
                                                   $password,
                                                   $grantType,
                                                   ClientEntityInterface $clientEntity)
    {
        $user = User::findOne(['username' => $username]);
        if ($user && $user->validatePassword($password)) {
            return $user;
        }
        return null;
    }
}