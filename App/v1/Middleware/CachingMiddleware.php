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
 * A caching middleware that hashes the url for the key. Options:
 *
 * expire_cache: Total number of seconds before expiring cache (default: 1 day)
 * host: The host for the memcached server (default: 127.0.0.1)
 * port: The port for the memcached server (default: 11211)
 * paths: The paths to cache
 * passthrough: The paths to ignore caching
 */
class CachingMiddleware
{
    /**
     * This is a path based middleware
     */
    use PathBasedTrait;

    /**
     * The cache object
     */
    protected $cache;

    /**
     * Are we using caching?
     *
     * @var boolean
     */
    protected $isCaching = false;

    /**
     * Build the middleware
     *
     * @param bool      $isCaching    Do you want to enable caching? (default: false)
     * @param array     $options      The array of options.
     */
    public function __construct(bool $isCaching = false, array $options = [])
    {
        /**
         * Set defaults
         */
        $this->options['expire_cache'] = 86400; // 1 day
        $this->options['host'] = '127.0.0.1';
        $this->options['port'] = 11211;
        $this->options = array_merge($this->options, $options);
        if (($isCaching) && (class_exists('Memcached'))) {
            try {
                $this->cache = new \Memcached();
                $this->cache->addServer(
                    $this->options['host'],
                    $this->options['port']
                );
                $this->isCaching = true;
            } catch (\Exception $e) {
                $this->isCaching = false;
            }
        } else {
            $this->isCaching = false;
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
        $format = $route->getArgument('format');
        if (!$this->isCaching) {
            return $response;
        }
        if (!$this->shouldProcess($request)) {
            return $response;
        }
        $cacheKey = $this->getCacheKey($request);
        $cached = $this->cache->get($cacheKey);
        if (!empty($cached)) {
            switch ($format) {
                case 'json':
                    $contentType = 'application/json; charset=UTF-8';
                    break;
                case 'xml':
                    $contentType = 'application/xml; charset=UTF-8';
                    break;
                default:
                    $contentType = 'text/html; charset=UTF-8';
                    break;
            }
            $response->getBody()->write($cached);
            return $response->withHeader('Content-Type', $contentType);
        }
        /**
         * Set the data to the cache using it's cache key, and expire it in 1 day
         *
         * @author Johnathan Pulos
         */
        $this->cache->set($cacheKey, (string) $response->getBody(), $this->options['expire_cache']);
        return $response;
    }

    /**
     * Generate a unique cache key using the URL and it's parameters.
     *
     * @param  Request      $request    PSR7 request
     * @return string                   The key
     */
    private function getCacheKey(Request $request): string
    {
        $path = $request->getUri()->getPath();
        $params = $request->getQueryParams();
        if (array_key_exists('api_key', $params)) {
            unset($params['api_key']);
        }
        $cacheKey = $path;
        if (!empty($params)) {
            $cacheKey .= '?';
        }
        foreach ($params as $key => $val) {
            $cacheKey .= $key . '=' . $val;
        }
        return md5(strtolower($cacheKey));
    }
}
