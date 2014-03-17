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
namespace Tests\v1\Unit\QueryGenerators;

/**
 * Test the Query Generator for the Continet Data
 *
 * @author Johnathan Pulos
 */
class ContinentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The PDO database connection object
     *
     * @var object
     */
    private $db;
    /**
     * Setup the test methods
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp()
    {
        $pdoDb = \PHPToolbox\PDODatabase\PDODatabaseConnect::getInstance();
        $pdoDb->setDatabaseSettings(new \JPAPI\DatabaseSettings);
        $this->db = $pdoDb->getDatabaseInstance();
    }
    /**
     * Test that the provided params are sanitized upon intializing the class
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldSanitizeProvidedDataOnInitializing()
    {
        $data = array('continent' => 'HORSE#%', 'test' => 'CA%$');
        $expected = array('continent' => 'HORSE', 'test' => 'CA');
        $reflectionOfContinent = new \ReflectionClass('\QueryGenerators\Continent');
        $providedParams = $reflectionOfContinent->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new \QueryGenerators\Continent($data));
        $this->assertEquals($expected, $result);
    }
    /**
     * findById() should return the correct continent
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindByIdShouldReturnCorrectContinent()
    {
        $expected = array('id'  =>  'lam');
        $expectedContinent = 'south america';
        $continent = new \QueryGenerators\Continent($expected);
        $continent->findById();
        $statement = $this->db->prepare($continent->preparedStatement);
        $statement->execute($continent->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['id'], strtolower($data[0]['ROG2']));
        $this->assertEquals($expectedContinent, strtolower($data[0]['Continent']));
    }
    /**
     * Tests that findById throws the correct error if a the id is not provided
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindByIdShouldThrowErrorIfNoIdFound()
    {
        $expected = array();
        $continent = new \QueryGenerators\Continent($expected);
        $continent->findById();
    }
    /**
     * Tests that findById throws the correct error if the id is not valid
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindByIdShouldThrowErrorIfIdInvalid()
    {
        $expected = array('id' => 'WWQQ');
        $continent = new \QueryGenerators\Continent($expected);
        $continent->findById();
    }
    /**
     * Tests that findById throws the correct error if the id is not one of the indicated ids
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindByIdShouldThrowErrorIfIdNotAcceptable()
    {
        $expected = array('id' => 'ggi');
        $continent = new \QueryGenerators\Continent($expected);
        $continent->findById();
    }
}
