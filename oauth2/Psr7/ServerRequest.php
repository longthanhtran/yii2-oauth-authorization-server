<?php
namespace longthanhtran\oauth2\Psr7;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\Request;

/**
 * Class ServerRequest
 * @package longthanhtran\oauth2\Psr7
 */
class ServerRequest extends \GuzzleHttp\Psr7\ServerRequest {

    /**
     * ServerRequest constructor
     * @param Request $request
     */
    public function __construct(Request $request){
        parent::__construct(
            $request->method,
            $request->url,
            $request->headers->toArray(),
            $request->rawBody
        );
    }

    /**
     * @throws InvalidConfigException
     */
    public function getParsedBody(): array
    {
        return Yii::$app->request->getBodyParams();
    }

    public function getQueryParams(): array
    {
        return Yii::$app->request->getQueryParams();
    }

}