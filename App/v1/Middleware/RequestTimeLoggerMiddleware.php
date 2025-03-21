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

declare(strict_types=1);

namespace Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Middleware that logs the time it takes to process a request
 */
class RequestTimeLoggerMiddleware implements MiddlewareInterface
{
    /**
     * The file where request times are logged
     *
     * @var string
     */
    private $logFile;

    /**
     * The paths to log only
     *
     * @var array
     */
    private $loggedPaths = [];
    /**
     * Set up the middleware
     *
     * @param string $logFile   The file where request times are logged
     * @param array  $loggedPaths The paths to log only
     */
    public function __construct(
        string $logFile,
        array $loggedPaths = []
    ) {
        $this->logFile = $logFile;
        $this->loggedPaths = $loggedPaths;
    }

    /**
     * Our invokable class
     *
     * @param  ServerRequestInterface   $request    PSR7 request
     * @param  RequestHandlerInterface  $handler    PSR-15 request handler
     *
     * @return ResponseInterface                    The modified response
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $startTime = microtime(true);
        $response = $handler->handle($request);
        $endTime = microtime(true);
        $duration = $endTime - $startTime;
        $uri = $request->getUri();
        $path = $uri->getPath();
        if (!$this->shouldLogPath($path)) {
            return $response;
        }
        $params = $request->getQueryParams();
        if (array_key_exists('api_key', $params)) {
            unset($params['api_key']);
        }
        $query = '';
        foreach ($params as $key => $val) {
            $query .= "{$key}={$val}";
        }
        if (!empty($query)) {
            $path .= "?{$query}";
        }
        $logMessage = sprintf(
            '[%s] %s %s - %s ms' . PHP_EOL,
            date('Y-m-d H:i:s'),
            $request->getMethod(),
            $path,
            round($duration * 1000, 2)
        );
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        return $response;
    }

    /**
     * Check if the path should be logged
     *
     * @param string $path The path to check
     *
     * @return bool
     */
    private function shouldLogPath(string $path): bool
    {
        if (empty($this->loggedPaths)) {
            return true;
        }
        foreach ($this->loggedPaths as $loggedPath) {
            if (strpos($path, $loggedPath) !== false) {
                return true;
            }
        }

        return false;
    }
}
