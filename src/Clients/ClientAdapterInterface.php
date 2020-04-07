<?php

namespace atipics\MoodleClient\Clients;

/**
 * Interface ClientAdapterInterface
 * @package atipics\MoodleClient\Clients
 */
interface ClientAdapterInterface
{
    /**
     * Send API request
     * @param $function
     * @param array $arguments
     * @return mixed
     */
    public function sendRequest($function, array $arguments = []);
}
