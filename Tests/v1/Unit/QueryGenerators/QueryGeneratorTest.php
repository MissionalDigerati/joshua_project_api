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
 * Test the Query Generator for the Country Data
 *
 * @author Johnathan Pulos
 */
class QueryGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The PDO database connection object
     *
     * @var \PHPToolbox\PDODatabase\PDODatabaseConnect
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
        $data = array('country' => 'AZX#%', 'state' => 'AZ%$');
        $expected = array('country' => 'AZX', 'state' => 'AZ');
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $providedParams = $reflectionOfQueryGenerator->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new \QueryGenerators\QueryGenerator($data));
        $this->assertEquals($expected, $result);
    }
    /**
     * paramExists() should return true if it exists
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testParamExistsShouldReturnTrueIfItExists()
    {
        $params = array('id'  =>  'BE');
        $queryGenerator = new \QueryGenerators\QueryGenerator($params);
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('paramExists');
        $method->setAccessible(true);
        $this->assertTrue($method->invoke($queryGenerator, 'id'));
    }
    /**
     * paramExists() should return false if it does not exists
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testParamExistsShouldReturnFalseIfItDoesNotExists()
    {
        $params = array('id'  =>  'BE');
        $queryGenerator = new \QueryGenerators\QueryGenerator($params);
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('paramExists');
        $method->setAccessible(true);
        $this->assertFalse($method->invoke($queryGenerator, 'goober'));
    }
    /**
     * addLimitFilter() should set the default limit and starting params in the preparedVariables variable
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAddLimitFilterShouldSetTheDefaultLimitAndStartingPreparedVariables()
    {
        $expectedLimit = 100;
        $expectedStarting = 0;
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('addLimitFilter');
        $method->setAccessible(true);
        $method->invoke($queryGenerator);
        $this->assertEquals($expectedLimit, $queryGenerator->preparedVariables['limit']);
        $this->assertEquals($expectedStarting, $queryGenerator->preparedVariables['starting']);
    }
    /**
     * addLimitFilter() should set the limit param to the requested limit in the preparedVariables variable
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAddLimitFilterShouldSetTheLimitPreparedVariablesToGivenLimit()
    {
        $expectedLimit = 10;
        $params = array('limit' => $expectedLimit);
        $queryGenerator = new \QueryGenerators\QueryGenerator($params);
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('addLimitFilter');
        $method->setAccessible(true);
        $method->invoke($queryGenerator);
        $this->assertEquals($expectedLimit, $queryGenerator->preparedVariables['limit']);
    }
    /**
     * addLimitFilter() should set the starting param based on the limit and page param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAddLimitFilterShouldSetTheStartingPreparedVariablesBasedOnGivenLimitAndPageParams()
    {
        $params = array('limit' => 10, 'page' => 3);
        /**
         * (10 * 3) -1 = 29
         * Since DB calls always stat with a 0 starting, we minus 1
         *
         * @author Johnathan Pulos
         **/
        $expectedStarting = ($params['limit']*$params['page'])-1;
        $queryGenerator = new \QueryGenerators\QueryGenerator($params);
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('addLimitFilter');
        $method->setAccessible(true);
        $method->invoke($queryGenerator);
        $this->assertEquals($expectedStarting, $queryGenerator->preparedVariables['starting']);
    }
    /**
     * Tests that generateInStatementFromPipedString() returns the correct statement, and the variables exists
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testGenerateInStatementFromPipedStringShouldReturnCorrectStatement()
    {
        $expectedString = "PeopleId1 IN (:peopleid1_0, :peopleid1_1, :peopleid1_2)";
        $expectedKeys = array('peopleid1_0', 'peopleid1_1', 'peopleid1_2');
        $expectedValues = array(1, 2, 3);
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('generateInStatementFromPipedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($queryGenerator, '1|2|3', 'PeopleId1');
        $this->assertEquals($expectedString, $actualString);
        $this->assertEquals($expectedKeys, array_keys($queryGenerator->preparedVariables));
        $this->assertEquals($expectedValues, array_values($queryGenerator->preparedVariables));
    }
    /**
     * generateBetweenStatementFromDashSeperatedString() should generate an appropriate BETWEEN statement with a max
     * and minimum
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testGenerateBetweenStatementFromDashSeperatedStringShouldReturnCorrectStatementWithAMaxAndMin()
    {
        $expectedString = "Population BETWEEN :min_pop AND :max_pop";
        $expectedKeys = array('min_pop', 'max_pop');
        $expectedValues = array(10, 20);
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('generateBetweenStatementFromDashSeperatedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($queryGenerator, '10-20', 'Population', 'pop');
        $this->assertEquals($expectedString, $actualString);
        $this->assertEquals($expectedKeys, array_keys($queryGenerator->preparedVariables));
        $this->assertEquals($expectedValues, array_values($queryGenerator->preparedVariables));
    }
    /**
     * generateBetweenStatementFromDashSeperatedString() should generate an appropriate statement with only a minimum
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testGenerateBetweenStatementFromDashSeperatedStringShouldReturnCorrectStatementWithMinOnly()
    {
        $expectedString = "Population = :total_population";
        $expectedKeys = array('total_population');
        $expectedValues = array(10);
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('generateBetweenStatementFromDashSeperatedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($queryGenerator, '10', 'Population', 'population');
        $this->assertEquals($expectedString, $actualString);
        $this->assertEquals($expectedKeys, array_keys($queryGenerator->preparedVariables));
        $this->assertEquals($expectedValues, array_values($queryGenerator->preparedVariables));
    }
    /**
     * Tests that generateBetweenStatementFromDashSeperatedString() throws error if too many parameters are provided
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testGenerateBetweenStatementFromDashSeperatedStringShouldThrowErrorIfMoreMinThanMaxGiven()
    {
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('generateBetweenStatementFromDashSeperatedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($queryGenerator, '10-20-39', 'Population', 'population');
    }
    /**
     * Tests that generateBetweenStatementFromDashSeperatedString() throws error if min is greater than max
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testGenerateBetweenStatementFromDashSeperatedStringShouldThrowErrorIfMinGreaterThanMax()
    {
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('generateBetweenStatementFromDashSeperatedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($queryGenerator, '30-20', 'Population', 'population');
    }
    /**
     * Tests that generateWhereStatementForBoolean generates the correct statement for a Yes Boolean
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testGenerateWhereStatementFromBooleanShouldReturnTheCorrectStatementForYes()
    {
        $expectedStatement = "IndigenousCode = :indigenous";
        $expectedKeys = array('indigenous');
        $expectedValues = array('Y');
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('generateWhereStatementForBoolean');
        $method->setAccessible(true);
        $actualStatement = $method->invoke($queryGenerator, 'y', 'IndigenousCode', 'indigenous');
        $this->assertEquals($expectedStatement, $actualStatement);
        $this->assertEquals($expectedKeys, array_keys($queryGenerator->preparedVariables));
        $this->assertEquals($expectedValues, array_values($queryGenerator->preparedVariables));
    }
    /**
     * Tests that generateWhereStatementForBoolean generates the correct statement for a No Boolean
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testGenerateWhereStatementFromBooleanShouldReturnTheCorrectStatementForNo()
    {
        $expectedStatement = "(10_40Window IS NULL OR 10_40Window = '' OR 10_40Window = 'N')";
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('generateWhereStatementForBoolean');
        $method->setAccessible(true);
        $actualStatement = $method->invoke($queryGenerator, 'n', '10_40Window', 'window_10_40');
        $this->assertEquals($expectedStatement, $actualStatement);
        $this->assertTrue(empty($queryGenerator->preparedVariables));
    }
    /**
     * Tests that generateWhereStatementForBoolean() throws error if you send anything else but Y or N
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testGenerateWhereStatementFromBooleanShouldThrowErrorIfParamInvalid()
    {
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $method = $reflectionOfQueryGenerator->getMethod('generateWhereStatementForBoolean');
        $method->setAccessible(true);
        $actualString = $method->invoke($queryGenerator, 'p', 'Population', 'population');
    }
    /**
     * Tests that generateAliasSelectStatement() generates the correct statement
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testGenerateAliasSelectStatementShouldGenerateTheCorrectStatement()
    {
        $aliasFieldsData = array('bob' => 'tom', 'sue' => 'sam');
        $expectedStatement = "bob AS tom, sue AS sam";
        $queryGenerator = new \QueryGenerators\QueryGenerator(array());
        $reflectionOfQueryGenerator = new \ReflectionClass('\QueryGenerators\QueryGenerator');
        $aliasFields = $reflectionOfQueryGenerator->getProperty('aliasFields');
        $aliasFields->setAccessible(true);
        $aliasFields->setValue($queryGenerator, $aliasFieldsData);
        $method = $reflectionOfQueryGenerator->getMethod('generateAliasSelectStatement');
        $method->setAccessible(true);
        $actualStatement = $method->invoke($queryGenerator);
        $this->assertEquals($expectedStatement, $actualStatement);
    }
}
