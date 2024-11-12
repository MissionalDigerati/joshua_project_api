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
 * @OA\Schema(
 *     schema="500JSONResponse",
 *     type="object",
 *     example={
 *         "api": {
 *             "status": "error",
 *             "error": {
 *                 "code": 500,
 *                 "message": "Internal Server Error",
 *                 "details": "Not implemented."
 *             }
 *         }
 *     },
 *     properties={
 *         @OA\Property(
 *             property="api",
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(
 *                 property="error",
 *                 type="object",
 *                 @OA\Property(property="code", type="integer", example=500),
 *                 @OA\Property(property="message", type="string", example="Internal Server Error"),
 *                 @OA\Property(property="details", type="string", example="Not implemented.")
 *             )
 *         )
 *     }
 * )
 */
/**
 * @OA\Schema(
 *     schema="500XMLResponse",
 *     type="object",
 *     @OA\Xml(
 *         name="api"
 *     ),
 *     example="<api><status>error</status><error><code>500</code><message>Internal Server Error</message><details>Not implemented.</details></error></api>",
 *     properties={
 *         @OA\Property(property="status", type="string", example="error"),
 *         @OA\Property(
 *             property="error",
 *             type="object",
 *             @OA\Property(property="code", type="integer", example=500),
 *             @OA\Property(property="message", type="string", example="Internal Server Error"),
 *             @OA\Property(property="details", type="string", example="Not implemented.")
 *         )
 *     }
 * )
 */
// phpcs:enable Generic.Files.LineLength
