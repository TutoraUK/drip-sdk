<?php
namespace TutoraUK\Drip\Tests\Api;

use PHPUnit\Framework\TestCase;
use TutoraUK\Drip\Drip;
use TutoraUK\Drip\Auth\Oauth;
use TutoraUK\Drip\Auth\Token;
use GuzzleHttp\ClientInterface;
use TutoraUK\Drip\Api\Endpoint;

class EndpointTest extends TestCase
{
    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(
            'TutoraUK\Drip\Api\Endpoint',
            new Endpoint(
                new Drip(
                    $this->createMock(ClientInterface::class)
                )
            )
        );
    }

    public function testBuildUrlCorrectlyBuildsEndpointUrl()
    {
        $Drip = new Drip(
            $this->createMock(ClientInterface::class)
        );
        $Drip->setAccountId('123456');

        $Endpoint = new Endpoint(
            $Drip
        );

        $this->assertSame(
            'https://api.getdrip.com/v2/123456/subscribers',
            $Endpoint->buildUrl(
                $Drip,
                ':accountId/subscribers'
            )
        );
    }

    public function testBuildUrlCorrectlyBuildsEndpointUrlWithTrailingSlashes()
    {
        $Drip = new Drip(
            $this->createMock(ClientInterface::class)
        );
        $Drip->setAccountId('123456');
        $Drip->apiUrl = 'https://api.getdrip.com/v2/';

        $Endpoint = new Endpoint(
            $Drip
        );

        $this->assertSame(
            'https://api.getdrip.com/v2/123456/subscribers',
            $Endpoint->buildUrl(
                $Drip,
                '/:accountId/subscribers'
            )
        );
    }

    public function testBuildOptionsCorrectlyBuildsOptionsArrayWithTokenAuth()
    {
        $Drip = new Drip(
            $this->createMock(ClientInterface::class)
        );
        $Drip->setAccountId('123456');
        $Drip->setAuth(
            new Token('my-token-here')
        );

        $Endpoint = new Endpoint(
            $Drip
        );

        $this->assertSame(
            [
                'headers' => [
                    'User-Agent' => $Drip->userAgent,
                    'Content-Type' => 'application/vnd.api+json',
                    'Accept' => 'application/vnd.api+json'
                ],
                'auth' => [
                    'my-token-here',
                    ''
                ],
                'body' => [
                    'test' => 'foobar',
                    'foo' => 'bar'
                ]
            ],
            $Endpoint->buildOptions(
                $Drip,
                [
                    'body' => [
                        'test' => 'foobar',
                        'foo' => 'bar'
                    ]
                ]
            )
        );
    }

    public function testBuildOptionsCorrectlyBuildsOptionsArrayWithOauthAuth()
    {
        $Drip = new Drip(
            $this->createMock(ClientInterface::class)
        );
        $Drip->setAccountId('123456');
        $Drip->setAuth(
            new Oauth('my-token-here')
        );

        $Endpoint = new Endpoint(
            $Drip
        );

        $this->assertSame(
            [
                'headers' => [
                    'User-Agent' => $Drip->userAgent,
                    'Content-Type' => 'application/vnd.api+json',
                    'Accept' => 'application/vnd.api+json',
                    'Authorization' => 'Bearer my-token-here',
                ],
                'body' => [
                    'test' => 'foobar',
                    'foo' => 'bar'
                ]
            ],
            $Endpoint->buildOptions(
                $Drip,
                [
                    'body' => [
                        'test' => 'foobar',
                        'foo' => 'bar'
                    ]
                ]
            )
        );
    }
}
