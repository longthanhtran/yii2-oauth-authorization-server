<?php

namespace longthanhtran\oauth2\controllers;

use longthanhtran\oauth2\Module;
use League\oauth2\Server\Exception\OAuthServerException;
use Yii;
use yii\helpers\Json;

class TokenController extends BaseController
{

    public function actionIndex()
    {
        /** @var Module $module */
        $module = $this->module;
        $server = $module->getAuthorizationServer();
        try {
            $response = $server->respondToAccessTokenRequest(
                $module->getServerRequest(),
                $module->getServerResponse());
            return Json::decode($response->getBody()->__toString());
        } catch (OAuthServerException $e) {
            Yii::$app->response->statusCode = $e->getHttpStatusCode();
            return ['error' => $e->getMessage()];
        }
    }
}