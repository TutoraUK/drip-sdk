<?php

namespace TutoraUK\Drip\Api\Actions\Subscribers;

use TutoraUK\Drip\Api\Actions\AbstractAction;

class CreateOrUpdateSubscribers extends AbstractAction
{
    /**
     * @param array $Subscribers
     */
    public function __construct(array $Subscribers)
    {
        $this->Subscribers = $Subscribers;
    }

    /**
     * return the method type to use
     *
     * @return string $method
     */
    public function getMethodType()
    {
        return 'POST';
    }

    /**
     * return the constructed API endpoint
     *
     * @return string $endpointUrl
     */
    public function getEndpointUrl()
    {
        return ':accountId/subscribers/batches';
    }

    /**
     * return an array of request options - useful for post data
     *
     * @link http://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @return array
     */
    public function getRequestOptions()
    {
        return [
            'json' => [
                'batches' => [
                    [
                        'subscribers' => $this->Subscribers,
                    ],
                ],
            ],
        ];
    }

    /**
     * process the response from the guzzle request
     *
     * @param GuzzleHttp\Psr7\Response $Response
     * @return TutoraUK\Result|Result
     */
    public function processResponse($Response)
    {
        if ($Response->getStatusCode() === 201) {
            return \Snscripts\Result\Result::success(
                \Snscripts\Result\Result::FOUND,
                'Subscribers created / updated',
                [],
                []
            );
        }

        return \Snscripts\Result\Result::fail(
            \Snscripts\Result\Result::ERROR,
            'There was an error while attempting to create / update subscribers'
        );
    }
}
