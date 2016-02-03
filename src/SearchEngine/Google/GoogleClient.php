<?php
/**
 * @license see LICENSE
 */

namespace Serps\SearchEngine\Google\Page;

use Http\Client\HttpClient;
use Http\Client\Plugin\PluginClient;
use Http\Client\Plugin\RedirectPlugin;
use Http\Discovery\HttpClientDiscovery;
use Serps\Exception;
use Serps\SearchEngine\Google\Page\GoogleError;
use Serps\SearchEngine\Google\Page\GoogleSerp;
use Serps\SearchEngine\Google\GoogleUrl;
use Serps\SearchEngine\Google\GoogleUrlTrait;

class GoogleClient
{

    /**
     * @var HttpClient
     */
    protected $client;

    public function __construct(HttpClient $client = null)
    {
        if (!$client) {
            if (class_exists('Http\Discovery\HttpClientDiscovery')) {
                $client = HttpClientDiscovery::find();
                if (!$client) {
                    throw new Exception(
                        'Client discovery service was unable to find a valid http client.'
                    );
                }
            } else {
                throw new Exception(
                    'No HTTP client was provided and no discovery service is present to guess it.'
                    . 'Maybe you need it install php-http/discovery package ?'
                );
            }
        }
        $this->client = new PluginClient($client, [
            new RedirectPlugin()
        ]);
    }

    public function query(GoogleUrlTrait $googleUrl)
    {
        $request = $googleUrl->buildRequest();
        $response = $this->client->sendRequest($request);

        $statusCode = $response->getStatusCode();
        $urlArchive = $googleUrl->getArchive();

        if (200 == $statusCode) {

            switch ($urlArchive->getResultType()) {
                case GoogleUrl::RESULT_TYPE_ALL:
                    $dom = new GoogleSerp((string)$response->getBody(), $urlArchive);
                    break;
                default:
                    $dom = new GoogleDom((string)$response->getBody(), $urlArchive);
            }

            return $dom;
        } else {
            if (404 == $statusCode) {
                throw new Exception\PageNotFoundException();
            } else {
                $errorDom = new GoogleError((string)$response->getBody(), $urlArchive);

                if ($errorDom->isCaptcha()) {
                    throw new Exception\CaptchaException();
                }
            }
        }

    }
}
