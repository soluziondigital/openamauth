<?php

namespace Maenbn\OpenAmAuth;

use GuzzleHttp\Cookie\SetCookie as CookieParser;
use Maenbn\OpenAmAuth\Contracts\Config as ConfigContract;
use Maenbn\OpenAmAuth\Contracts\Guzzle as GuzzleContract;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7 as GuzzlePsr7;
use GuzzleHttp\Exception\GuzzleException as GuzzleException;

class Guzzle implements GuzzleContract
{

    /**
     * @var string
     */
    protected $usersUri = "/users";

    /**
     * @var string
     */
    protected $authUri = "/authenticate";

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $userToken;

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @var ConfigContract
     */
    private $config;

    /**
     * Guzzle constructor.
     * @param ConfigContract $config
     */
    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
        $this->url = $this->config->setUrlWithRealm(true)->getUrl();

        $this->client = new GuzzleClient([
            'verify' => false
        ]);
    }

    /**
     * @param $user
     * @return mixed
     * @throws \Exception
     */
    public function getUserInformation($user){
        $token = $this->getAdminToken();

        //Convert USER uri string to Guzzle uri resource
        $uriRes = GuzzlePsr7\uri_for($this->url . $this->usersUri . '/' . $user);

        //Make GET call with auth token
        try{
            $response = $this->client->get($uriRes, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept-API-Version' => 'resource=3.0, protocol=1.0',
                    'iPlanetDirectoryPro' => $token
                ]
            ]);

            return json_decode($response->getBody()->getContents());
        } catch (GuzzleException $ge){
            throw new \Exception($ge->getMessage(), 555);
        }
    }

    /**
     * Retrieves user token and returns it for further api calls
     *
     * @return string
     * @throws \Exception
     */
    public function getUserToken($user, $password)
    {
        //Check if we have a token already stored to avoid unnecessary calls
        if ($this->userToken != "") {
            return $this->userToken;
        } else {
            //Convert AUTH uri string to Guzzle uri resource
            $authUri = GuzzlePsr7\uri_for($this->url . $this->authUri);
            try {
                //Make POST call with admin parameters to get the auth token
                $authResonse = $this->client->post($authUri, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept-API-Version' => 'resource=2.0, protocol=1.0',
                        'X-OpenAM-Username' => $user,
                        'X-OpenAM-Password' => $password
                    ]
                ]);

                $cookieParser = new CookieParser();
                foreach ($authResonse->getHeaders()['Set-Cookie'] as $cookie){
                    $cookie = $cookieParser->fromString($cookie);
                    setrawcookie (
                        $cookie->getName(),
                        $cookie->getValue(),
                        $cookie->getExpires(),
                        $cookie->getPath(),
                        $cookie->getDomain()
                    );
                }

                //Decode json string from the response body
                $authResult = json_decode($authResonse->getBody()->getContents());

                //Return token
                $this->userToken = urldecode($authResult->tokenId);

                return $this->userToken;
            } catch (GuzzleException $ge){
                throw new \Exception($ge->getMessage(), 555);
            }
        }
    }

    /**
     * @param $token
     * @return bool|mixed
     */
    public function validateTokenId($token){
        //Convert USER uri string to Guzzle uri resource
        $uriRes = GuzzlePsr7\uri_for($this->config->getBaseUrl() . "/sessions/?tokenId=" . $token . "&_action=validate");

        //Make POST call with auth token to validate it
        try{
            $response = $this->client->post($uriRes, [
                'headers' => [
                    'Accept-API-Version' => 'resource=2.0, protocol=1.0',
                ]
            ]);

            return json_decode($response->getBody()->getContents(),true);
        } catch (GuzzleException $ge){
            return false;
        }
    }

    /**
     * Retrieves admin token and returns it for further api calls
     *
     * @return string
     */
    public function getAdminToken()
    {
        //Check if we have a token already stored to avoid unnecessary calls
        if ($this->token != "") {
            return $this->token;
        } else {
            //Convert AUTH uri string to Guzzle uri resource
            $authUri = GuzzlePsr7\uri_for($this->url . $this->authUri);
            try {
                //Make POST call with admin parameters to get the auth token
                $authResonse = $this->client->post($authUri, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept-API-Version' => 'resource=2.0, protocol=1.0',
                        'X-OpenAM-Username' => 'amAdmin',
                        'X-OpenAM-Password' => 'amadminpass'
                    ]
                ]);

                //Decode json string from the response body
                $authResult = json_decode($authResonse->getBody()->getContents());

                $this->token = $authResult->tokenId;
                //Return token
                return $authResult->tokenId;
            } catch (GuzzleException $ge){
                abort(555, $ge->getMessage());
            }
        }
    }

    /**
     * @return bool|mixed
     */
    public function logout(){
        $uriRes = GuzzlePsr7\uri_for($this->url . '/sessions/?_action=logout');

        try{
            $response = $this->client->post($uriRes, [
                'headers' => [
                    'Accept-API-Version' => 'resource=2.0, protocol=1.0',
                ]
            ]);

            return json_decode($response->getBody()->getContents());
        } catch (GuzzleException $ge){
            return false;
        }
    }
}
