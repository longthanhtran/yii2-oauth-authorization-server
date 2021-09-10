<?php

namespace longthanhtran\oauth2\services;

use longthanhtran\oauth2\models\Scope;

class ScopeService
{

    public function hasScopes(string $identifier): bool
    {
        $scope = Scope::findOne(['identifier' => $identifier]);
        return !empty($scope);
    }
}