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
namespace Middleware\Traits;

use Psr\Http\Message\ServerRequestInterface;

/**
 * This trait provides a way for middleware to set path and
 * passthrough options to do work only on a specific URLs.
 * passthrough will bypass the middleware, and it will only handle
 * paths.
 */
trait PathBasedTrait
{
    /**
     * The available options for our middleware
     *
     * paths: All the pathes that require an API key
     * passthrough: All the pathes to allow to bypass check that might match the path
     */
    private $options = [
        'paths'          =>  [],
        'passthrough'   =>  []
    ];

    /**
     * Should we process the current URL
     *
     * @param  ServerRequestInterface $request  PSR7 request
     *
     * @return  boolean                         yes|no
     */
    private function shouldProcess(ServerRequestInterface $req)
    {
        $uri = "/" . $req->getUri()->getPath();
        $uri = preg_replace("#/+#", "/", $uri);
        /* If request path is matches passthrough should not process. */
        foreach ($this->options["passthrough"] as $passthrough) {
            $passthrough = rtrim($passthrough, "/");
            /* The !! turns the preg_match result into a boolean */
            /* The @ symbol is the delimeter for the regular expression */
            if (!!preg_match("@^{$passthrough}(/.*)?$@", $uri)) {
                return false;
            }
        }
        /* Otherwise check if path matches and we should process. */
        foreach ($this->options["paths"] as $path) {
            $path = rtrim($path, "/");
            /* The !! turns the preg_match result into a boolean */
            /* The @ symbol is the delimeter for the regular expression */
            if (!!preg_match("@^{$path}(/.*)?$@", $uri)) {
                return true;
            }
        }
        return false;
    }
}
