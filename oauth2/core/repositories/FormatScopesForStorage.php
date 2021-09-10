<?php

namespace longthanhtran\oauth2\core\repositories;

trait FormatScopesForStorage
{
    public function formatScopesForStorage(array $scopes)
    {
        return json_encode($this->scopesToArray($scopes));
    }

    public function scopesToArray(array $scopes): string
    {
        $built_scopes = array_reduce($scopes, function($acc, $scope) {
            return sprintf("%s %s", $acc, $scope->getIdentifier());
        }, '');
        return trim($built_scopes);
    }

}