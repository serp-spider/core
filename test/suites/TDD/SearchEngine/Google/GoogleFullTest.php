<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\SearchEngine\Google;

use Http\Client\Curl\Client;
use Http\Client\Plugin\PluginClient;
use Http\Client\Plugin\RedirectPlugin;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Http\Message\StreamFactory\DiactorosStreamFactory;
use Serps\SearchEngine\Google\Page\GoogleDom;
use Serps\SearchEngine\Google\GoogleUrlArchive;
use Serps\SearchEngine\Google\GoogleUrl;

class GoogleFullTest extends \PHPUnit_Framework_TestCase
{

    public function testGoogleCall()
    {

        return;

        $googleUrl = new GoogleUrl();
        $googleUrl->setSearchTerm('simpsons');

        $request = $googleUrl->buildRequest();

        $baseClient = new Client(new DiactorosMessageFactory(), new DiactorosStreamFactory());
        $httpClient = new PluginClient(
            $baseClient,
            [
                new RedirectPlugin()
            ]
        );

        $response = $httpClient->sendRequest($request);
        $result = new GoogleDom((string)$response->getBody(), $googleUrl->getArchive());



    }
}
