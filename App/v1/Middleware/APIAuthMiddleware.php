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
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Middleware for auth checking for API requests.
 */
class APIAuthMiddleware
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
     * Our database
     *
     * @var \PDO
     */
    private $db;

    /**
     * Set up the middleware
     *
     * @param \PDO      $db         The database
     * @param array     $options    The options
     */
    public function __construct(\PDO $db, $options = [])
    {
        $this->db = $db;
        $this->options = array_merge($this->options, $options);
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
        $params = $req->getQueryParams();
        $format = $req->getAttribute('route')->getArgument('format');
        if (!$this->shouldProcess($req)) {
            return $next($req, $res);
        }
        if (empty($params)) {
            return $this->sendError(
                401,
                'You are missing your API key.',
                $format,
                'Unauthorized',
                $res
            );
        }
        $apiKey = strip_tags($params['api_key']);
        if ((!isset($apiKey)) || (empty($apiKey))) {
            return $this->sendError(
                401,
                'You are missing your API key.',
                $format,
                'Unauthorized',
                $res
            );
        }
        if (!$this->isValidKey($apiKey)) {
            return $this->sendError(
                401,
                'The provided API key is invalid.',
                $format,
                'Unauthorized',
                $res
            );
        }

        return $next($req, $res);
    }

    /**
     * Check if the API key is valid?
     *
     * @param string $apiKey    The API key
     *
     * @return boolean          yes|no
     */
    private function isValidKey($apiKey)
    {
        $query = "SELECT * FROM md_api_keys where api_key = :api_key LIMIT 1";
        $statement = $this->db->prepare($query);
        $statement->execute(array('api_key' => $apiKey));
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($data)) {
            return false;
        }
        /**
         * Pending (0) or Suspended (2)
         */
        if ((intval($data[0]['status']) === 0) || (intval($data[0]['status']) === 2)) {
            return false;
        }
        return true;
    }
}
