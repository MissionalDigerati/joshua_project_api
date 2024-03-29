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
namespace Tests\v1\Unit\QueryGenerators;

use PHPToolbox\PDODatabase\PDODatabaseConnect;
use PHPUnit\Framework\TestCase;

/**
 * Test the Query Generator for the Region Data
 *
 * @author Johnathan Pulos
 */
class RegionTest extends TestCase
{
    /**
     * The PDO database connection object
     *
     * @var PDODatabaseConnect
     */
    private $db;
    /**
     * Setup the test methods
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp(): void
    {
        $this->db = getDatabaseInstance();
    }
    /**
     * Test that the provided params are sanitized upon intializing the class
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldSanitizeProvidedDataOnInitializing(): void
    {
        $data = array('region' => 'HORSE#%', 'test' => 'CA%$');
        $expected = array('region' => 'HORSE', 'test' => 'CA');
        $reflectionOfContinent = new \ReflectionClass('\QueryGenerators\Region');
        $providedParams = $reflectionOfContinent->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new \QueryGenerators\Region($data));
        $this->assertEquals($expected, $result);
    }
    /**
     * findById() should return the correct region
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindByIdShouldReturnTheCorrectRegion(): void
    {
        $expected = array('id'  =>  9);
        $expectedRegion = 'europe, eastern and eurasia';
        $region = new \QueryGenerators\Region($expected);
        $region->findById();
        $statement = $this->db->prepare($region->preparedStatement);
        $statement->execute($region->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['id'], intval($data[0]['RegionCode']));
        $this->assertEquals($expectedRegion, strtolower($data[0]['RegionName']));
    }
    /**
     * findById() throws the correct error if a the id is not provided
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testFindByIdShouldThrowErrorIfNoIdFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expected = array();
        $region = new \QueryGenerators\Region($expected);
        $region->findById();
    }
    /**
     * findById() throws the correct error if a the id is invalid
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testFindByIdShouldThrowErrorIfNotValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expected = array('id'  =>  'aat');
        $region = new \QueryGenerators\Region($expected);
        $region->findById();
    }
    /**
     * findById() throws the correct error if a the id is out of range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testFindByIdShouldThrowErrorIfOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expected = array('id'  =>  21);
        $region = new \QueryGenerators\Region($expected);
        $region->findById();
    }
}
