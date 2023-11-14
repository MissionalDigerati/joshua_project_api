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

use QueryGenerators\Region;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Swagger\Annotations as SWG;

// phpcs:disable Generic.Files.LineLength
/**
 * @SWG\Resource(
 *     apiVersion="1",
 *     swaggerVersion="1.1",
 *     resourcePath="/regions",
 *     basePath="/v1"
 * )
 */
/**
  *
  * @SWG\API(
  *  path="/regions/{id}.{format}",
  *  description="Retrieve the details of a specific Region.",
  *  @SWG\Operations(
  *      @SWG\Operation(
  *          httpMethod="GET",
  *          nickname="regionShow",
  *          summary="Retrieve the details of a specific Region (JSON or XML)",
  *          notes="Retrieve the details of a specific Region by supplying a unique id for the region.  Use the following numbers:<br><ul><li>1 - South Pacific</li><li>2 - Southeast Asia</li><li>3 - Northeast Asia</li><li>4 - South Asia</li><li>5 - Central Asia</li><li>6 - Middle East and North Africa</li><li>7 - East and Southern Africa</li><li>8 - West and Central Africa</li><li>9 - Eastern Europe and Eurasia</li><li>10 - Western Europe</li><li>11 - Central and South America</li><li>12 - North America and Caribbean</li></ul>",
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
  *                  description="The unique id for the region. Use the codes indicated above.",
  *                  paramType="path",
  *                  required="true",
  *                  allowMultiple="false",
  *                  dataType="int"
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
    "/{version}/regions/{id}.{format}",
    function (Request $request, Response $response, $args = []): Response {
        /**
         * Make sure we have an ID, else crash
         *
         * @author Johnathan Pulos
         */
        $regionId = intval(strip_tags($args['id']));
        if ((empty($regionId)) || ($regionId > 12)) {
            return $this->get('errorResponder')->get(
                400,
                'You provided an invalid region id.',
                $args['format'],
                'Bad Request',
                $response
            );
        }
        try {
            $region = new Region(['id' => $regionId]);
            $region->findById();
            $statement = $this->get('db')->prepare($region->preparedStatement);
            $statement->execute($region->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                return $this->get('errorResponder')->get(
                    404,
                    'The region does not exist for the given id.',
                    $args['format'],
                    'Not Found',
                    $response
                );
            }
        } catch (Exception $e) {
            return $this->get('errorResponder')->get(
                500,
                $e->getMessage(),
                $args['format'],
                'Internal Server Error',
                $response
            );
        }
        /**
         * Render the final data
         *
         * @author Johnathan Pulos
         */
        if ($args['format'] == 'json') {
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($data));
        } else {
            return $response
                ->withHeader('Content-type', 'text/xml')
                ->write(arrayToXML($data, "regions", "region"));
        }
    }
);
