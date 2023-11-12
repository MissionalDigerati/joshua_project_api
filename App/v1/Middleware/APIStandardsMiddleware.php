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
use Middleware\Traits\ReturnsErrorsTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

/**
 * A middleware that checks that the request conforms to our API standards.
 * Is it requesting the correct format? Is the an available version number?
 * Options:
 *
 * formats: An array of supported formats
 * paths: The paths to cache
 * passthrough: The paths to ignore caching
 * versions: An array of supported version numbers
 */
class APIStandardsMiddleware
{
    /**
     * This is a path based middleware
     */
    use PathBasedTrait;

    /**
     * This returns errors for the API
     */
    use ReturnsErrorsTrait;

    /**
     * Build the middleware
     *
     * @param array   $options      The array of options.
     */
    public function __construct(array $options)
    {
        $this->options['formats'] = [];
        $this->options['versions'] = [];
        $this->options = array_merge($this->options, $options);
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
        $response = $handler->handle($request);
        $routeContext = RouteContext::fromRequest($request);                                                                                                             
        $route = $routeContext->getRoute(); 
        if (!$route) {
            $info = pathinfo($request->getUri()->getPath());
            $format = (array_key_exists('extension', $info)) ? $info['extension'] : '';
            return $this->sendError(
                400,
                'You are requesting an unavailable API version number.',
                $format,
                'Bad Request',
                $response
            );
        }
        $format = $route->getArgument('format');
        $version = $route->getArgument('version');
        if (!$this->shouldProcess($request)) {
            return $response;
        }
        if (!in_array($format, $this->options['formats'])) {
            return $this->sendError(
                400,
                'You are requesting an unsupported format.',
                $format,
                'Bad Request',
                $response
            );
        }
        if (!in_array($version, $this->options['versions'])) {
            return $this->sendError(
                400,
                'You are requesting an unavailable API version number.',
                $format,
                'Bad Request',
                $response
            );
        }

        return $response;
    }
}
