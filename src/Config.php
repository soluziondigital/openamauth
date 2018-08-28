<?php

namespace Maenbn\OpenAmAuth;

use Maenbn\OpenAmAuth\Contracts\Config as ConfigContract;

class Config implements ConfigContract
{

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string|null
     */
    protected $realm;

    /**
     * @var string
     */
    protected $cookieName;

    /**
     * @var string
     */
    protected $cookiePath;

    /**
     * @var string
     */
    protected $cookieDomain;

    /**
     * @var string
     */
    protected $adminUser;

    /**
     * @var string
     */
    protected $adminPassword;

    /**
     * @var bool
     */
    protected $secureCookie;

    /**
     * @var bool
     */
    protected $urlWithRealm = false;

    /**
     * Config constructor.
     * @param string $domainName
     * @param string|null $realm
     * @param string $uri
     */
    public function __construct($domainName, $realm = null, $uri = 'openam')
    {
        $this->setBaseUrl($domainName, $uri)->setRealm($realm);
    }

    /**
     * @param $domainName
     * @param $uri
     * @return $this
     */
    protected function setBaseUrl($domainName, $uri)
    {
        $this->baseUrl = $domainName . '/' . $uri . '/json';
        return $this;
    }

    /**
     * @param $realm
     * @return $this
     */
    protected function setRealm($realm)
    {
        $this->realm = $realm;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = $this->baseUrl;
        if($this->isUrlWithRealm() && !is_null($this->realm)){
            $url .= '/' . $this->realm;
        }
        return $url;
    }

    /**
     * @return string
     */
    public function getBaseUrl(){
        return $this->baseUrl;
    }

    /**
     * @return bool
     */
    public function isUrlWithRealm()
    {
        return $this->urlWithRealm;
    }
    
    /**
     * @param bool $urlWithRealm
     * @return $this
     */
    public function setUrlWithRealm($urlWithRealm)
    {
        $this->urlWithRealm = $urlWithRealm;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCookieName()
    {
        return $this->cookieName;
    }

    /**
     * @param string $cookieName
     * @return $this
     */
    public function setCookieName($cookieName)
    {
        $this->cookieName = $cookieName;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCookiePath()
    {
        return $this->cookiePath;
    }

    /**
     * @param string $cookieName
     * @return $this
     */
    public function setCookiePath($cookiePath)
    {
        $this->cookiePath = $cookiePath;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCookieDomain()
    {
        return $this->cookieDomain;
    }

    /**
     * @param string $cookieName
     * @return $this
     */
    public function setCookieDomain($cookieDomain)
    {
        $this->cookieDomain = $cookieDomain;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAdminUser()
    {
        return $this->adminUser;
    }

    /**
     * @param string $adminUser
     * @return $this
     */
    public function setAdminUser($adminUser)
    {
        $this->adminUser = $adminUser;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAdminPassword()
    {
        return $this->adminPassword;
    }

    /**
     * @param string $adminPassword
     * @return $this
     */
    public function setAdminPassword($adminPassword)
    {
        $this->adminPassword = $adminPassword;
        return $this;
    }

    /**
     * @return null|bool
     */
    public function getSecureCookie()
    {
        return $this->secureCookie;
    }

    /**
     * @param string $secureCookie
     * @return $this
     */
    public function setSecureCookie($secureCookie)
    {
        $this->secureCookie = $secureCookie;
        return $this;
    }
}