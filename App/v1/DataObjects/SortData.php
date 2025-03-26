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

namespace DataObjects;

class SortData
{
    /**
     * Build the data object
     *
     * @param string $field     The field to sort by
     * @param string $direction The direction to sort by
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        public string $field,
        public string $direction,
    ) {
        if (!in_array(strtoupper($direction), ['ASC', 'DESC'], true)) {
            throw new \InvalidArgumentException(
                "Invalid sort direction: $direction. Allowed values are 'ASC' or 'DESC'."
            );
        }
    }
}
