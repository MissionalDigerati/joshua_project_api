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

use QueryGenerators\Continent;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Swagger\Annotations as SWG;

// phpcs:disable Generic.Files.LineLength
/**
 * @SWG\Resource(
 *     apiVersion="1",
 *     swaggerVersion="1.1",
 *     resourcePath="/continents",
 *     basePath="/v1"
 * )
 */
/**
  *
  * @SWG\API(
  *  path="/continents/{id}.{format}",
  *  description="Retrieve the details of a specific Continent.",
  *  @SWG\Operations(
  *      @SWG\Operation(
  *          httpMethod="GET",
  *          nickname="continentShow",
  *          summary="Retrieve the details of a specific Continent (JSON or XML)",
  *          notes="Retrieve the details of a specific Continent by supplying a three letter ISO Continent code (id).  Use the following codes:<br><ul><li>AFR - Africa</li><li>ASI  - Asia</li><li>AUS - Australia</li><li>EUR - Europe</li><li>NAR - North America</li><li>SOP - Oceania (South Pacific)</li><li>LAM - South America</li></ul>",
  *          @SWG\Parameters(
  *              @SWG\Parameter(
  *                  name="api_key",
  *                  description="Your Joshua Project API key.",
  *                  paramType="query",
  *                  required="true",
  *                  allowMultiple="false",
  *                  dataType="string"
  *              ),
  *              @SWG\Parameter(
  *                  name="id",
  *                  description="The 3 letter ISO Continent Code for the Continent you want to view. Use the codes indicated above.",
  *                  paramType="path",
  *                  required="true",
  *                  allowMultiple="false",
  *                  dataType="string"
  *              )
  *          ),
  *          @SWG\ErrorResponses(
  *              @SWG\ErrorResponse(
  *                  code="400",
  *                  reason="Bad request.  Your request is malformed in some way.  Check your supplied parameters."
  *              ),
  *              @SWG\ErrorResponse(
  *                  code="401",
  *                  reason="Unauthorized.  Your missing your API key, or it has been suspended."
  *              ),
  *              @SWG\ErrorResponse(
  *                  code="404",
  *                  reason="Not found.  The requested route was not found."
  *              ),
  *              @SWG\ErrorResponse(
  *                  code="500",
  *                  reason="Internal server error.  Please try again later."
  *              )
  *          )
  *      )
  *  )
  * )
  *
  */
// phpcs:enable Generic.Files.LineLength
$app->get(
    "/{version}/continents/{id}.{format}",
    function (Request $request, Response $response, $args = []): Response {
        /**
         * Make sure we have an ID, else crash.
         * This expression ("/\PL/u") removes all non-letter characters
         *
         * @author Johnathan Pulos
         */
        $format = $args['format'];
        $continentId = preg_replace(
            "/\PL/u",
            "",
            strip_tags(
                strtoupper($args['id'])
            )
        );
        if ((empty($continentId)) || (strlen($continentId) != 3)) {
            return $this->get('errorResponder')->get(
                400,
                'You provided an invalid continent id.',
                $format,
                'Bad Request',
                $response
            );
        }
        try {
            $continent = new Continent(['id' => $continentId]);
            $continent->findById();
            $statement = $this->get('db')->prepare($continent->preparedStatement);
            $statement->execute($continent->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                return $this->get('errorResponder')->get(
                    404,
                    'The continent does not exist for the given id.',
                    $format,
                    'Not Found',
                    $response
                );
            }
        } catch (Exception $e) {
            return $this->get('errorResponder')->get(
                500,
                $e->getMessage(),
                $format,
                'Internal Server Error',
                $response
            );
        }
        /**
         * Render the final data
         *
         * @author Johnathan Pulos
         */
        if ($format == 'json') {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode($data));
        } else {
            return $response
                ->withHeader('Content-Type', 'text/xml')
                ->write(arrayToXML($data, "continents", "continent"));
        }
    }
);
