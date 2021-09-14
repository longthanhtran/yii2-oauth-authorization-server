<?php

namespace longthanhtran\oauth2\core\models;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;


class Client implements ClientEntityInterface
{
    use ClientTrait;

    /**
     * The client identifier
     * @var string
     */
    protected string $identifier;
    /**
     * @var mixed|null
     */
    public $provider;

    public function __construct(
        $identifier,
        $name,
        $redirectUri,
        $isConfidential = false,
        $provider = null
    )
    {
        $this->setIdentifier($identifier);
        $this->name = $name;
        $this->isConfidential = $isConfidential;
        $this->redirectUri = explode(',', $redirectUri);
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return void
     */
    public function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;
    }

}