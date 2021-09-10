<?php

namespace longthanhtran\oauth2;


use Codeception\Lib\Generator\Group;
use longthanhtran\oauth2\core\repositories\AccessTokenRepository;
use longthanhtran\oauth2\core\repositories\AuthCodeRepository;
use longthanhtran\oauth2\core\repositories\ClientRepository;
use longthanhtran\oauth2\core\repositories\RefreshTokenRepository;
use longthanhtran\oauth2\core\repositories\ScopeRepository;
use longthanhtran\oauth2\core\repositories\UserRepository;
use longthanhtran\oauth2\Psr7\ServerRequest;
use longthanhtran\oauth2\Psr7\ServerResponse;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use longthanhtran\oauth2\controllers\TokenController;
use longthanhtran\oauth2\controllers\AuthorizeController;
use Yii;
use yii\base\BootstrapInterface;
use yii\console\Application;
use yii\helpers\ArrayHelper;
use yii\rest\UrlRule;
use yii\web\GroupUrlRule;
use yii\filters\Cors;

/**
 * @property-read AuthorizationServer $authorizationServer
 * @property ClientRepositoryInterface $clientRepository
 * @property AccessTokenRepositoryInterface $accessTokenRepository
 * @property ScopeRepositoryInterface $scopeRepository
 * @property AuthCodeRepositoryInterface $authCodeRepository
 * @property RefreshTokenRepository $refreshTokenRepository
 * @property UserRepository $userRepository
 */
class Module extends \yii\base\Module implements BootstrapInterface {

    /**
     * @var array
     */
    public $controllerMap = [
        'authorize' => [
            'class' => AuthorizeController::class,
            'as corsFilter' => Cors::class,
        ],
        'token' => [
            'class' => TokenController::class,
            'as corsFilter' => Cors::class,
        ]
    ];

    public $privateKey;
    public $publicKey;
    private $encryptionKey;
    private $_serverResponse;
    private $_serverRequest;
    public $enableGrantTypes;
    private $_authorizationServer;
    public $urlManagerRules = [];

    public $controllerNamespace = 'longthanhtran\oauth2\controllers';

    public function init() {
        parent::init();

        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'longthanhtran\oauth2\commands';
        }

        if (!$this->privateKey instanceof CryptKey) {
            $this->privateKey = new CryptKey($this->privateKey);
        }

        if (!$this->publicKey instanceof CryptKey) {
            $this->publicKey = new CryptKey($this->publicKey);
        }

    }

    public function __construct($id, $parent = null, $config = [])
    {
        parent::__construct($id, $parent, ArrayHelper::merge([
            'components' => [
                'userRepository' => [
                    'class' => UserRepository::class,
                ],
                'clientRepository' => [
                    'class' => ClientRepository::class,
                ],
                'accessTokenRepository' => [
                    'class' => AccessTokenRepository::class
                ],
                'scopeRepository' => [
                    'class' => ScopeRepository::class
                ],
                'authCodeRepository' => [
                    'class' => AuthCodeRepository::class
                ],
                'refreshTokenRepository' => [
                    'class' => RefreshTokenRepository::class
                ],
            ],
        ], $config));
    }

    public function getAuthorizationServer(): AuthorizationServer
    {
        if (!$this->_authorizationServer instanceof AuthorizationServer) {
            $this->buildAuthorizationServer();
        }

        return $this->_authorizationServer;
    }

    protected function buildAuthorizationServer() {

        $this->_authorizationServer = new AuthorizationServer(
            $this->clientRepository,
            $this->accessTokenRepository,
            $this->scopeRepository,
            $this->privateKey,
            $this->encryptionKey
        );

        if (is_callable($this->enableGrantTypes) !== true) {
            $this->enableGrantTypes = function() {
                throw OAuthServerException::unsupportedGrantType();
            };
        }
        call_user_func_array($this->enableGrantTypes, [&$this]);
    }

    public function setEncryptionKey(string $encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    public function getServerResponse(): ServerResponse
    {
        if (!$this->_serverResponse instanceof ServerResponse) {
            $this->_serverResponse = new ServerResponse();
        }

        return $this->_serverResponse;
    }

    public function getServerRequest()
    {
        if (!$this->_serverRequest instanceof ServerRequest) {
            $request = Yii::$app->request;
            $this->_serverRequest = (new ServerRequest($request))
                ->withParsedBody($request->getQueryParams());
        }

        return $this->_serverRequest;
    }

    public function bootstrap($app)
    {
        $app->getUrlManager()
            ->addRules((new GroupUrlRule([
                'ruleConfig' => [
                    'class' => UrlRule::class,
                    'pluralize' => false,
                    'only' => ['create', 'options'],
                ],
                'rules' => ArrayHelper::merge([
                    ['controller' => $this->uniqueId . '/authorize'],
                    ['controller' => $this->uniqueId . '/token'],
                ], $this->urlManagerRules)
            ]))->rules, false);
    }
}