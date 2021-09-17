<?php

namespace longthanhtran\oauth2\filters;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use longthanhtran\oauth2\core\repositories\AccessTokenRepository;
use longthanhtran\oauth2\Psr7\ServerRequest;
use yii\base\ActionFilter;
use Yii;

class ResourceFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $server = new ResourceServer(
            new AccessTokenRepository(),
            $this->getPublicKeyPath()
        );
        try {
            $server->validateAuthenticatedRequest(
                new ServerRequest(
                    Yii::$app->getRequest()
                )
            );
        } catch (OAuthServerException $authServerException) {
            $response = Yii::$app->response;
            $response->statusCode = $authServerException->getHttpStatusCode();
            $response->data = ['error' => $authServerException->getMessage()];
            return false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Extract public key path, by convention within @app/keys
     */
    protected function getPublicKeyPath()
    {
        $appPath = Yii::getAlias("@app");
        return "{$appPath}/keys/public.key";
    }
}
