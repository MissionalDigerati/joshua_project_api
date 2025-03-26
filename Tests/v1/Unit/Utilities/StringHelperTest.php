<?php
declare(strict_types=1);

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
namespace Tests\v1\Unit\Utilities;

use PHPUnit\Framework\TestCase;
use Utilities\StringHelper;

class StringHelperTest extends TestCase
{
    public function testNullToEmptyShouldReturnAnEmptyString(): void
    {
        $this->assertNotNull(StringHelper::nullToEmpty(null));
    }

    public function testNullToEmptyShouldReturnTheOriginalString(): void
    {
        $this->assertEquals('I am a string.', StringHelper::nullToEmpty('I am a string.'));
    }

    public function testEnsureTrailingSpaceShouldReturnStringWithSpace(): void
    {
        $string = 'Hello';
        $expected = 'Hello ';
        $actual = StringHelper::ensureTrailingSpace($string);
        $this->assertEquals($expected, $actual);
    }

    public function testEnsureTrailingSpaceShouldReturnStringWithSpaceIfAlreadyPresent(): void
    {
        $string = 'Hello ';
        $expected = 'Hello ';
        $actual = StringHelper::ensureTrailingSpace($string);
        $this->assertEquals($expected, $actual);
    }
    
}
