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

namespace Utilities;

/**
 * A class that provides string helper methods.
 */
class StringHelper
{
    /**
     * Appends a space on the given string if it does not already have one.
     *
     * @param string $string    The string to check for a space.
     * @return string   The string with a space appended if it did not already have one.
     */
    public static function ensureTrailingSpace(string $string): string
    {
        return (substr($string, -1) === ' ') ? $string :  "{$string} ";
    }
    /**
     * If a string is null, returns an empty string
     *
     * @param ?string   $string    The string to convert
     *
     * @return string           The string or an empty string if it was null
     */
    public static function nullToEmpty(?string $string): string
    {
        return (is_null($string)) ? '' : $string;
    }
}
