# Yii2 base OAuth2 authorization server

## Installation

```shell
composer require longthanhtran/yii2-oauth2-server
```

## Notes

* The package serves as Yii2 module to perform 2 main functions of OAuth2 Authorization server. This bases on league/oauth2-server and can run on PHP 7.4 or 8.0.

* Sample module config
Review and create a file name modules.php inside `config` folder with following content. Then append `'modules' => $modules,` right inside @app/config/web.php (behind $params key)

```php
use longthanhtran\oauth2\Module;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;

return [
    'oauth2' => [
        'class' => 'longthanhtran\oauth2\Module',
        'privateKey' => __DIR__ . '/../keys/private.key',
        'publicKey' => __DIR__ . '/../keys/public.key',
        'encryptionKey' => "you-need-to-prepare-this-encryption-key",
        'enableGrantTypes' => function(Module $module) {
            $server = $module->authorizationServer;

            // Client Credentials Grant
            $server->enableGrantType(
                new ClientCredentialsGrant(),
                new DateInterval('PT1H') // expires after 1 hour
            );

            // Authorization Code Grant
            $authCodeGrant = new AuthCodeGrant(
                $module->authCodeRepository,
                $module->refreshTokenRepository,
                new DateInterval('PT10M') // expires after 10 minutes
            );
            $authCodeGrant->setRefreshTokenTTL(
                new DateInterval('P1M') // expires after 1 month
            );
            $server->enableGrantType(
                $authCodeGrant,
                new DateInterval('PT1H') // expires after 1 hour
            );

            // Refresh Token Grant
            $refreshTokenGrant = new RefreshTokenGrant(
                $module->refreshTokenRepository
            );
            $refreshTokenGrant->setRefreshTokenTTL(
                new DateInterval('P1M') // expires after 1 month
            );
            $server->enableGrantType(
                $refreshTokenGrant,
                new DateInterval('PT1H') // expires after 1 hour
            );
            // Password Grant - legacy grant
            $passwordGrant = new PasswordGrant(
                $module->userRepository,
                $module->refreshTokenRepository
            );
            $passwordGrant->setRefreshTokenTTL(new DateInterval('P1M'));
            $server->enableGrantType(
                $passwordGrant,
                new DateInterval('PT1H') // expires after 1 hour
            );
        }
    ]
];
```

Be sure to prepare the private key, public key (in @app/keys folder) and encryption Key.

* To prepare the schema, run migration with
```shell
yii migrate --migrationPath=@vendor/longthanhtran/yii2-oauth2-authorization-server/oauth2/migrations
```

* To validate user's credential, you can implement UserEntityInterface for your User class, sample provide below. Be sure to `use UserQueryTrait` in `User`

```php
namespace app\models;

use League\OAuth2\Server\Entities\ClientEntityInterface;

trait UserQueryTrait {

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
```