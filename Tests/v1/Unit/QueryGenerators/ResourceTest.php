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
 * @copyright Copyright 2013 Missional Digerati
 * 
 */
namespace Tests\v1\Unit\QueryGenerators;

/**
 * Test the Query Generator for the people group ProfileText Data
 *
 * @author Johnathan Pulos
 */
class ResourceTest extends \PHPUnit_Framework_TestCase
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
     * We should throw an InvalidArgumentException if I do not send the ID to find
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindAllByLanguageIdShouldThrowErrorIfMissingID()
    {
        $getVars = array();
        $resource = new \QueryGenerators\Resource($getVars);
        $resource->findAllByLanguageId();
    }
    /**
     * We should verify ROL3 is lower cased when passed in
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllByLanguageIdShouldLowerCaseTheID()
    {
        $expectedId = 'abv';
        $getVars = array('id' => $expectedId);
        $resource = new \QueryGenerators\Resource($getVars);
        $resource->findAllByLanguageId();
        $this->assertEquals($resource->preparedVariables['id'], $expectedId);
        $this->assertNotEquals($resource->preparedVariables['id'], strtoupper($expectedId));
    }
    /**
     * We should return the correct list of resources for a specific language
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllByLanguageIdShouldReturnCorrectResources()
    {
        $expectedId = 'abv';
        $getVars = array('id' => $expectedId);
        $resource = new \QueryGenerators\Resource($getVars);
        $resource->findAllByLanguageId();
        $statement = $this->db->prepare($resource->preparedStatement);
        $statement->execute($resource->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $resourceResult) {
            $this->assertEquals($resourceResult['ROL3'], $expectedId);
        }
    }
}
