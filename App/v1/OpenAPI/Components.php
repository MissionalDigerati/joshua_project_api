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

namespace OpenApi\Schemas;

use OpenApi\Attributes as OA;

// phpcs:disable Generic.Files.LineLength
/**
 * @OA\Info(
 *     title="Joshua Project API",
 *     version="1"
 * )
 */
/**
 * @OA\Components(
 *      @OA\Parameter(
 *         name="api_key",
 *         description="Your Joshua Project API key.",
 *         parameter="APIKeyParameter",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string")
 *      ),
 *      @OA\Parameter(
 *         description="The format of the response, either 'json' or 'xml'.",
 *         parameter="APIFormatParameter",
 *         name="format",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", enum={"json", "xml"})
 *      ),
 *      @OA\Response(
 *         response="400ApiResponse",
 *         description="Bad request. Your request is malformed in some way. Check your supplied parameters.",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/400JSONResponse")
 *         ),
 *         @OA\MediaType(
 *             mediaType="application/xml",
 *             @OA\Schema(ref="#/components/schemas/400XMLResponse")
 *         )
 *     ),
 *     @OA\Response(
 *         response="401ApiResponse",
 *         description="Unauthorized. You are missing your API key, or it has been suspended.",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/401JSONResponse")
 *         ),
 *         @OA\MediaType(
 *             mediaType="application/xml",
 *             @OA\Schema(ref="#/components/schemas/401XMLResponse")
 *         )
 *     ),
 *     @OA\Response(
 *         response="404ApiResponse",
 *         description="Not found. The requested route was not found.",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/404JSONResponse")
 *         ),
 *         @OA\MediaType(
 *             mediaType="application/xml",
 *             @OA\Schema(ref="#/components/schemas/401XMLResponse")
 *         )
 *     ),
 *     @OA\Response(
 *         response="500ApiResponse",
 *         description="Internal server error. Please try again later.",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/500JSONResponse")
 *         ),
 *         @OA\MediaType(
 *             mediaType="application/xml",
 *             @OA\Schema(ref="#/components/schemas/500XMLResponse")
 *         )
 *     )
 * )
 */
// phpcs:enable Generic.Files.LineLength
