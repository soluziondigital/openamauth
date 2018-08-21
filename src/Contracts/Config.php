<?php

namespace Maenbn\OpenAmAuth\Contracts;

interface Config
{
    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getBaseUrl();

    /**
     * @return bool
     */
    public function isUrlWithRealm();

    /**
     * @param bool $urlWithRealm
     * @return $this
     */
    public function setUrlWithRealm($urlWithRealm);

    /**
     * @return null|string
     */
    public function getCookieName();

    /**
     * @param string $cookieName
     * @return $this
     */
    public function setCookieName($cookieName);

    /**
     * @return null|string
     */
    public function getAdminUser();

    /**
     * @param string $adminUser
     * @return $this
     */
    public function setAdminUser($adminUser);

    /**
     * @return null|string
     */
    public function getAdminPassword();

    /**
     * @param string $adminPassword
     * @return $this
     */
    public function setAdminPassword($adminPassword);

    /**
     * @return null|bool
     */
    public function getSecureCookie();

    /**
     * @param string $secureCookie
     * @return $this
     */
    public function setSecureCookie($secureCookie);
}
