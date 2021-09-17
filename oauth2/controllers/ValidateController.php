<?php

namespace longthanhtran\oauth2\controllers;

use longthanhtran\oauth2\filters\ResourceFilter;
use longthanhtran\oauth2\services\TokenService;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use Yii;

class ValidateController extends Controller
{
    public function behaviours()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ResourceFilter::class
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index' => ['POST', 'HEAD'],
            ],
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $token = Yii::$app->request->getBodyParam('access_token');
        $response = Yii::$app->response;
        if (empty($token)) {
            $response->statusCode = 400;
            return [
                'error' => 'access_token is missing or invalid'
            ];
        }
        $tokenService = new TokenService();
        $tokenValid = ! $tokenService->isAccessTokenRevoked($token);
        $status = $tokenValid ? 200 : 400;
        $response->statusCode = $status;
        return [
            'status' => $status,
            'token_valid' => $tokenValid
        ];
    }
}