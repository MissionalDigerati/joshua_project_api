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
use Utilities\Sanitizer;

/**
 * Test the Sanitizer Utility
 *
 * @author Johnathan Pulos
 */
class SanitizerTest extends TestCase
{
    /**
     * The Sanitizer object
     *
     * @var Sanitizer
     */
    private $sanitizer;
    /**
     * Setup the test methods
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp(): void
    {
        $this->sanitizer = new Sanitizer();
    }
    /**
     * cleanParam() should return a cleaned parameter
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testCleanParamRemovesUnnecessaryCharacters(): void
    {
        $param = "gi|ti+=%&*123";
        $expected = "gi|ti123";
        $received = $this->sanitizer->cleanParam($param);
        $this->assertEquals($expected, $received);
    }
    /**
     * cleanArrayValues() should return an array with cleaned values
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testCleanArrayValuesRemovesUnnecessaryCharacters(): void
    {
        $str1 = "gi|ti+=%&*123";
        $expected1 = "gi|ti123";
        $str2 = "go$%^&bber$5";
        $expected2 = "gobber5";
        $arrayToClean = array('str1' => $str1, 'str2' => $str2);
        $received = $this->sanitizer->cleanArrayValues($arrayToClean);
        $this->assertEquals($expected1, $received['str1']);
        $this->assertEquals($expected2, $received['str2']);
    }
}
