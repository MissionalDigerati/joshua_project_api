<?php
/**
 * This file is part of Joshua Project API.
 *
 * Joshua Project API is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Joshua Project API is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
namespace Middleware;

use Middleware\Traits\PathBasedTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

/**
 * Middleware that sends events to Google Analytics 4>. Options:
 *
 * api_secret: Your Google Analytics secret (required)
 * measurement_id: Your Google Analytics measurement id (required)
 * paths: The paths to cache
 * passthrough: The paths to ignore caching
 */
class GoogleAnalyticsMiddleware
{
    /**
     * This is a path based middleware
     */
    use PathBasedTrait;

    /**
     * Are we tracking requests?
     *
     * @var bool
     */
    protected $isTracking = false;

    /**
     * The URL where we make the request
     *
     * @var string
     */
    protected $url = 'https://www.google-analytics.com/mp/collect';

    public function __construct(bool $isTracking, array $options)
    {
        $this->isTracking = $isTracking;
        $this->options['measurement_id'] = '';
        $this->options['api_secret'] = '';
        $this->options = array_merge($this->options, $options);
        if ((!isset($this->options['measurement_id'])) || (!isset($this->options['api_secret']))) {
            $this->isTracking = false;
        }
    }

    /**
     * Our invokable class
     *
     * @param  Request          $request    PSR7 request
     * @param  RequestHandler   $handler    PSR-15 request handler
     *
     * @return Response                     The modified response
     */
    public function __invoke(
        Request $request,
        RequestHandler $handler
    ): Response {
        $routeContext = RouteContext::fromRequest($request);                                                                                                             
        $route = $routeContext->getRoute();   
        $response = $handler->handle($request);
        if (!$this->isTracking) {
            return $response;
        }
        if (!$this->shouldProcess($request)) {
            return $response;
        }
        $params = $request->getQueryParams();
        if (empty($params)) {
            return $response;
        }
        $route = $request->getAttribute('route');
        // Remove the extension
        $endpoint = preg_replace('/\\.[^.\\s]{3,4}$/', '', $request->getUri()->getPath());
        $format = $route->getArgument('format');
        $version = ltrim($route->getArgument('version'), 'v');
        $clientId = $params['api_key'];
        if ((!isset($clientId)) || (empty($clientId))) {
            return $response;
        }
        $this->sendEvent($clientId, $endpoint, $format, $version);
        return $response;
    }

    /**
     * Send an event to Google Analytics API. I tried using the PHPBox CurlUtility
     * but it would die on the request.
     *
     * @param string $clientId  The client request the endpoint
     * @param string $endpoint  The endpoint requested
     * @param string $format    The request format
     * @param string $version   The API version
     *
     * @return void
     */
    protected function sendEvent(
        string $clientId,
        string $endpoint,
        string $format,
        string $version
    ): void {
        $url = $this->url . '?measurement_id=' . $this->options['measurement_id'];
        $url .= '&api_secret=' . $this->options['api_secret'];
        $payload = array(
            'client_id'     => $clientId,
            'events'        =>  array(
                'name'      =>  'api_requests',
                'params'    =>  array(
                    'endpoint'  =>  $endpoint,
                    'format'    =>  $format,
                    'version'   =>  $version
                )
            )
        );
        $ch = curl_init();
        /**
         * Setup cURL, we start by spoofing the user agent since it is from code:
         * http://davidwalsh.name/set-user-agent-php-curl-spoof
         *
         * @author Johnathan Pulos
         */
        curl_setopt(
            $ch,
            CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) ' .
            'Gecko/20080311 Firefox/2.0.0.13'
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_exec($ch);
        curl_close($ch);
    }
}
