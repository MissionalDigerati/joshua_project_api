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
namespace Tests\Unit\QueryGenerators;

/**
 * Test the Query Generator for the People Group Data
 *
 * @author Johnathan Pulos
 */
class PeopleGroupTest extends \PHPUnit_Framework_TestCase
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
     * Test that we get back the right query for unreached of the day
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldReturnCorrectResultsForDailyUnreachedyQuery()
    {
        $expected = array('month' => 1, 'day' => 11);
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->dailyUnreached();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['month'], $data[0]['LRofTheDayMonth']);
        $this->assertEquals($expected['day'], $data[0]['LRofTheDayDay']);
    }
    /**
     * We should throw an InvalidArgumentException if I do not send the month to dailyUnreached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
      * 
      * @expectedException InvalidArgumentException
     */
    public function testShouldThrowErrorIfMissingMonthOnDailyUnreached()
    {
        $expected = array('day' => 11);
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->dailyUnreached();
    }
    /**
     * We should throw an InvalidArgumentException if I do not send the day to dailyUnreached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
      * 
      * @expectedException InvalidArgumentException
     */
    public function testShouldThrowErrorIfMissingDayOnDailyUnreached()
    {
        $expected = array('month' => 11);
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->dailyUnreached();
    }
    /**
     * We should throw an InvalidArgumentException if I do not send a month in range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
      * 
      * @expectedException InvalidArgumentException
     */
    public function testDailyUnreachedShouldThrowErrorIfMonthIsOutOfRange()
    {
        $expected = array('month' => 13, 'day' => 1);
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->dailyUnreached();
    }
    /**
     * We should throw an InvalidArgumentException if I do not send a day in range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
      * 
      * @expectedException InvalidArgumentException
     */
    public function testDailyUnreachedShouldThrowErrorIfDayIsOutOfRange()
    {
        $expected = array('month' => 12, 'day' => 32);
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->dailyUnreached();
    }
    /**
     * Tests that validateProvidedFields throws the correct error if a param is missing
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testShouldErrorIfValidateProvidedParamsFindsMissingParam()
    {
        $expected = array();
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('validateProvidedParams');
        $method->setAccessible(true);
        $method->invoke($peopleGroup, array('name'));
    }
    /**
     * cleanParams() should return safe variables
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldReturnCleanedVariableFromCleanParams()
    {
        $var = "<html>1223 Fresh Cake <a href='hello'>Boo</a></html>";
        $expected = array("my_string" => "1223 Fresh Cake Boo");
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        /**
         * We need to reset $this->providedParams, since it is cleaned during the construction of the class
         *
         * @package default
         * @author Johnathan Pulos
         */
        $property = $reflectionOfPeopleGroup->getProperty('providedParams');
        $property->setAccessible(true);
        $property->setValue($reflectionOfPeopleGroup, $expected);
        $method = $reflectionOfPeopleGroup->getMethod('cleanParams');
        $method->setAccessible(true);
        $method->invoke($peopleGroup);
        $actual = $property->getValue($reflectionOfPeopleGroup);
        $this->assertEquals($expected['my_string'], $actual['my_string']);
    }
    /**
     * Validate that validateVariableInRange() sends back true if it is in range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testValidateVariableInRangeShouldReturnTrueIfVariableIsInRange()
    {
        $data = array('in_range' => 5);
        $peopleGroup = new \QueryGenerators\PeopleGroup($data);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('validateVariableInRange');
        $method->setAccessible(true);
        $actual = $method->invoke($peopleGroup, 'in_range', 1, 7);
        $this->assertTrue($actual);
    }
    /**
     * Validate that validateVariableInRange() sends back false if it is not in range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testValidateVariableInRangeShouldReturnFalseIfVariableIsOutOfRange()
    {
        $data = array('out_range' => 10);
        $peopleGroup = new \QueryGenerators\PeopleGroup($data);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('validateVariableInRange');
        $method->setAccessible(true);
        $actual = $method->invoke($peopleGroup, 'out_range', 1, 7);
        $this->assertFalse($actual);
    }
}
