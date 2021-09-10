<?php

namespace longthanhtran\oauth2\controllers;

use andreyv\ratelimiter\IpRateLimiter;
use yii\filters\VerbFilter;
use yii\rest\Controller;

abstract class BaseController extends Controller
{
    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['rateLimiter'] = [
            'class' => IpRateLimiter::class,
            'rateLimit' => 100,
            'timePeriod' => 600,
            'separateRates' => true,
            'enableRateLimitHeaders' => true,
            'actions' => ['index'],
        ];

        $behaviours['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index' => ['GET', 'POST', 'HEAD'],
            ],
        ];
        return $behaviours;
    }
}