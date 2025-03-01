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
 *     schema="401JSONResponse",
 *     type="object",
 *     example={
 *         "api": {
 *             "status": "error",
 *             "error": {
 *                 "code": 401,
 *                 "message": "Unauthorized",
 *                 "details": "You are missing your API key."
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
 *                 @OA\Property(property="code", type="integer", example=401),
 *                 @OA\Property(property="message", type="string", example="Unauthorized"),
 *                 @OA\Property(property="details", type="string", example="You are missing your API key.")
 *             )
 *         )
 *     }
 * )
 */
/**
 * @OA\Schema(
 *     schema="401XMLResponse",
 *     type="object",
 *     @OA\Xml(
 *         name="api"
 *     ),
 *     example="<api><status>error</status><error><code>401</code><message>Unauthorized</message><details>You are missing your API key.</details></error></api>",
 *     properties={
 *         @OA\Property(property="status", type="string", example="error"),
 *         @OA\Property(
 *             property="error",
 *             type="object",
 *             @OA\Property(property="code", type="integer", example=401),
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *             @OA\Property(property="details", type="string", example="You are missing your API key.")
 *         )
 *     }
 * )
 */
// phpcs:enable Generic.Files.LineLength
