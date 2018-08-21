<?php

namespace Maenbn\OpenAmAuth\Factories;


use Maenbn\OpenAmAuth\Contracts\Config;
use Maenbn\OpenAmAuth\Guzzle;
use Maenbn\OpenAmAuth\OpenAm;

class OpenAmFactory
{
    /**
     * @param Config $config
     * @return OpenAm
     */
    public static function create(Config $config)
    {
        $curlFactory =  new CurlFactory();
        $strategyFactory = new StrategiesFactory();
        $guzzle = new Guzzle($config);
        $curl = $curlFactory->newCurl();
        $curl->setResultFormat($strategyFactory->newJsonToObject());

        return new OpenAm($config, $curl, $guzzle);
    }
}