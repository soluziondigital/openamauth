<?php

namespace Maenbn\OpenAmAuth\Contracts;

interface Guzzle
{
    /**
     * @param $user
     * @return mixed
     * @throws \Exception
     */
    public function getUserInformation($user);

    /**
     * Retrieves user token and returns it for further api calls
     *
     * @return string
     * @throws \Exception
     */
    public function getUserToken($user, $password);

    /**
     * @param $token
     * @return bool|mixed
     */
    public function validateTokenId($token);

    /**
     * Retrieves admin token and returns it for further api calls
     *
     * @return string
     */
    public function getAdminToken();

    /**
     * @return bool|mixed
     */
    public function logout();
}