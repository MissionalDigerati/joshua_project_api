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
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param boolean $isCaching    Do you want to enable caching? (default: false)
     * @param array   $options      The array of options.
     */
    public function __construct($isCaching = false, $options = [])
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
     * @param  ServerRequestInterface   $request  PSR7 request
     * @param  ResponseInterface        $response PSR7 response
     * @param  callable                 $next     Next middleware
     *
     * @return ResponseInterface                  The modified response
     */
    public function __invoke(
        ServerRequestInterface $req,
        ResponseInterface $res,
        callable $next
    ) {
        $format = $req
            ->getAttribute('route')
            ->getArgument('format');
        if (!$this->isCaching) {
            return $next($req, $res);
        }
        if (!$this->shouldProcess($req)) {
            return $next($req, $res);
        }
        $cacheKey = $this->getCacheKey($req);
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
            $res->getBody()->write($cached);
            return $res->withHeader('Content-Type', $contentType);
        }
        // Get the format, and return the correct response
        $response = $next($req, $res);
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
     * @param  ServerRequestInterface   $request  PSR7 request
     * @return string                             The key
     */
    private function getCacheKey(ServerRequestInterface $req)
    {
        $path = $req->getUri()->getPath();
        $params = $req->getQueryParams();
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
        return md5($cacheKey);
    }
}
