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
}
