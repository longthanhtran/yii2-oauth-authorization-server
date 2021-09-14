<?php

namespace longthanhtran\oauth2\controllers;


use app\models\User;
use longthanhtran\oauth2\Module;
use League\OAuth2\Server\Exception\OAuthServerException;
use Yii;

class AuthorizeController extends BaseController
{

    /**
     */
    public function actionIndex()
    {
        /** @var Module $module */
        $module = $this->module;
        $server = $module->getAuthorizationServer();
        try {
            $authRequest = $server->validateAuthorizationRequest($module->getServerRequest());
            $authRequest->setUser(new User());
            $authRequest->setAuthorizationApproved(true);
            $response = $server->completeAuthorizationRequest($authRequest, $module->getServerResponse());

            return $this->redirect($response->getHeader('Location')[0]);
        } catch (OAuthServerException $e) {
            Yii::$app->response->statusCode = $e->getHttpStatusCode();
            return ['error' => $e->getMessage()];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;
            return ['error' => $e->getMessage()];
        }
    }

}