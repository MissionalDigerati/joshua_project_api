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
        $getVars = array('day' => 11);
        $peopleGroup = new \QueryGenerators\PeopleGroup($getVars);
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
        $getVars = array('month' => 11);
        $peopleGroup = new \QueryGenerators\PeopleGroup($getVars);
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
        $getVars = array('month' => 13, 'day' => 1);
        $peopleGroup = new \QueryGenerators\PeopleGroup($getVars);
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
        $getVars = array('month' => 12, 'day' => 32);
        $peopleGroup = new \QueryGenerators\PeopleGroup($getVars);
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
        $getVars = array();
        $peopleGroup = new \QueryGenerators\PeopleGroup($getVars);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('validateProvidedParams');
        $method->setAccessible(true);
        $method->invoke($peopleGroup, array('name'));
    }
    /**
     * Tests that validateContinents throws the correct error if a continent in the continents param is invalid
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testShouldErrorIfValidateContinentsFindsABadContinent()
    {
        $getVars = array('continents' => 'AFR|BEN');
        $peopleGroup = new \QueryGenerators\PeopleGroup($getVars);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('validateContinents');
        $method->setAccessible(true);
        $method->invoke($peopleGroup);
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
     * validateStringLength() should error if the character length is incorrect
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testValidateVariableLengthShouldThrowErrorIfLengthIsIncorrect()
    {
        $data = array();
        $testString = "iloveicecream";
        $peopleGroup = new \QueryGenerators\PeopleGroup($data);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('validateStringLength');
        $method->setAccessible(true);
        $method->invoke($peopleGroup, $testString, 20);
    }
    /**
     * Validate that validateVariableInRange() sends back false if it is not in range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * @expectedException InvalidArgumentException
     */
    public function testValidateVariableInRangeShouldThrowErrorIfVariableIsOutOfRange()
    {
        $data = array('out_range' => 10);
        $peopleGroup = new \QueryGenerators\PeopleGroup($data);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('validateVariableInRange');
        $method->setAccessible(true);
        $actual = $method->invoke($peopleGroup, 'out_range', 1, 7);
    }
    /**
     * findByIdAndCountry() should return the correct people group, based on the supplied ID, and country.
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindByIdAndCountryShouldReturnTheCorrectPeopleGroup()
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedName = "Khmer, Central";
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['id'], $data[0]['PeopleID3']);
        $this->assertEquals($expected['country'], $data[0]['ROG3']);
        $this->assertEquals($expectedName, $data[0]['PeopNameInCountry']);
    }
    /**
     * findByIdAndCountry() should require an ID
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindByIdAndCountryShouldErrorIfNoIDProvided()
    {
        $expected = array('country' => 'CB');
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
    }
    /**
     * findByIdAndCountry() should require an country
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindByIdAndCountryShouldErrorIfNoCountryProvided()
    {
        $expected = array('id' => '12662');
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
    }
    /**
     * findById() should return the correct people group, from many countries
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindByIdShouldReturnTheCorrectPeopleGroups()
    {
        $expected = array('id' => '12662');
        $expectedPeopleGroups = 10;
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->findById();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['id'], $data[0]['PeopleID3']);
        $this->assertEquals($expectedPeopleGroups, count($data));
    }
    /**
     * findById() should require an id
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindByIdShouldErrorIfNoIDProvided()
    {
        $expected = array();
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->findById();
    }
    /**
     * findAllWithFilters() query should return 100 people groups by default
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersReturns100ResultsWithNoFiltersByDefault()
    {
        $expectedNumberOfResults = 100;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedNumberOfResults, count($data));
    }
    /**
     * findAllWithFilters() query should filter by PeopleID1
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPeopleID1()
    {
        $expectedPeopleIds = array(17, 23);
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('people_id1' => join("|", $expectedPeopleIds)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID1']), $expectedPeopleIds));
        }
    }
    /**
     * findAllWithFilters() query should filter by ROP1
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByROP1()
    {
        $expectedROP = array('A014', 'A010');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('rop1' => join("|", $expectedROP)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROP1'], $expectedROP));
        }
    }
    /**
     * findAllWithFilters() query should filter by ROP1 and PeopleID1
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByROP1AndPeopleID1()
    {
        $expectedROP = 'A014';
        $expectedPeopleID = 23;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('rop1' => $expectedROP, 'people_id1' => $expectedPeopleID));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals($expectedROP, $peopleGroup['ROP1']);
            $this->assertEquals($expectedPeopleID, intval($peopleGroup['PeopleID1']));
        }
    }
    /**
     * findAllWithFilters() query should filter by PeopleID2
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPeopleID2()
    {
        $expectedPeopleIDs = array(117, 115);
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('people_id2' => join("|", $expectedPeopleIDs)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID2']), $expectedPeopleIDs));
        }
    }
    /**
     * findAllWithFilters() query should filter by ROP2
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByROP2()
    {
        $expectedROP = array('C0013', 'C0067');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('rop2' => join("|", $expectedROP)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROP2'], $expectedROP));
        }
    }
    /**
     * findAllWithFilters() query should filter by PeopleID3
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPeopleID3()
    {
        $expectedPeopleIDs = array(11722, 19204);
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('people_id3' => join("|", $expectedPeopleIDs)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID3']), $expectedPeopleIDs));
        }
    }
    /**
     * findAllWithFilters() query should filter by ROP3
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByROP3()
    {
        $expectedROP = array(115485, 115409);
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('rop3' => join("|", $expectedROP)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['ROP3']), $expectedROP));
        }
    }
    /**
     * findAllWithFilters() query should filter by continents
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByContinents()
    {
        $expectedContinents = array('AFR', 'NAR');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('continents' => join("|", $expectedContinents)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROG2'], $expectedContinents));
        }
    }
    /**
     * findAllWithFilters() query should filter by countries
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByCountries()
    {
        $expectedCountries = array('AN', 'BG');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('countries' => join("|", $expectedCountries)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROG3'], $expectedCountries));
        }
    }
    /**
      * Tests that findAllWithFilters() throws the correct error if a the continent is not a correct continent
      *
      * @return void
      * @access public
      * @author Johnathan Pulos
      * 
      * @expectedException InvalidArgumentException
      */
    public function testShouldErrorIfFindAllWithFilterFindsInCorrectContinents()
    {
        $expectedCountries = array('BBC', 'DED');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('continents' => join("|", $expectedCountries)));
        $peopleGroup->findAllWithFilters();
    }
    /**
      * Tests that findAllWithFilters() throws the correct error if a the regions are not in the correct range
      *
      * @return void
      * @access public
      * @author Johnathan Pulos
      * 
      * @expectedException InvalidArgumentException
      */
    public function testShouldErrorIfFindAllWithFilterFindsInCorrectRegionCodes()
    {
        $regionCodes = array(0, 13);
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('regions' => join("|", $regionCodes)));
        $peopleGroup->findAllWithFilters();
    }
    /**
     * Tests that findAllWithFilters() filters by the given region codes
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByRegions()
    {
        $expectedRegions = array(3 => 'northeast asia', 4 => 'south asia');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('regions' => join("|", array_keys($expectedRegions))));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['RegionCode']), array_keys($expectedRegions)));
            $this->assertTrue(in_array(strtolower($peopleGroup['RegionName']), array_values($expectedRegions)));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by the given window1040
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterBy1040Window()
    {
        $expected1040Window = 'N';
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('window1040' => $expected1040Window));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals(null, $peopleGroup['Window10_40']);
        }
    }
    /**
     * Tests that findAllWithFilters() filters by the given languages
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByLanguages()
    {
        $expectedLanguages = array('AKA', 'ALE');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('languages' => join("|", $expectedLanguages)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(strtoupper($peopleGroup['ROL3']), $expectedLanguages));
        }
    }
    /**
      * Tests that findAllWithFilters() throws the correct error if the window1040 is set to anything else but Y & N
      *
      * @return void
      * @access public
      * @author Johnathan Pulos
      * 
      * @expectedException InvalidArgumentException
      */
    public function testShouldErrorIfFindAllWithFilterFindsInCorrectWindow1040()
    {
        $regionCodes = array(0, 13);
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('window1040' => 'b'));
        $peopleGroup->findAllWithFilters();
    }
    /**
     * Tests that paramExists() returns true if the param is in the providedParams array
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testParamExistsShouldReturnTrueWhenItExists()
    {
        $data = array('PeopleIdAndName' => '23-Tibetian');
        $peopleGroup = new \QueryGenerators\PeopleGroup($data);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('paramExists');
        $method->setAccessible(true);
        $this->assertTrue($method->invoke($peopleGroup, 'PeopleIdAndName'));
    }
    /**
     * Tests that paramExists() returns false if the param is not in the providedParams array
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testParamExistsShouldReturnFalseWhenItDoesNotExist()
    {
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('paramExists');
        $method->setAccessible(true);
        $this->assertFalse($method->invoke($peopleGroup, 'PeopleIdAndName'));
    }
    /**
     * The findAllWithFilters query should exclude several fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersQueryShouldExcludeSeveralFieldsAndChanges1040Field()
    {
        $restrictedFields = array('JPScalePC', 'LeastReachedPC', 'RLG3PC', 'PrimaryReligionPC', 'JPScalePGAC', 'LeastReachedPGAC', 'RLG3PGAC', 'PrimaryReligionPGAC', 'ROL3Edition14Orig', 'EthnologueCountryCode', 'EthnologueMapExists', 'WorldMapExists', 'UNMap', 'EthneMonth');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('limit' => 1));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($restrictedFields as $field) {
            $this->assertFalse(array_key_exists($field, $data[0]));
        }
        $this->assertTrue(array_key_exists('Window10_40', $data[0]));
        $this->assertFalse(array_key_exists('10_40Window', $data[0]));
    }
    /**
     * The findById query should exclude several fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindByIDQueryShouldExcludeSeveralFieldsAndChanges1040Field()
    {
        $restrictedFields = array('JPScalePC', 'LeastReachedPC', 'RLG3PC', 'PrimaryReligionPC', 'JPScalePGAC', 'LeastReachedPGAC', 'RLG3PGAC', 'PrimaryReligionPGAC', 'ROL3Edition14Orig', 'EthnologueCountryCode', 'EthnologueMapExists', 'WorldMapExists', 'UNMap', 'EthneMonth');
        $data = array('id' => '12662');
        $peopleGroup = new \QueryGenerators\PeopleGroup($data);
        $peopleGroup->findById();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($restrictedFields as $field) {
            $this->assertFalse(array_key_exists($field, $data[0]));
        }
        $this->assertTrue(array_key_exists('Window10_40', $data[0]));
        $this->assertFalse(array_key_exists('10_40Window', $data[0]));
    }
    /**
     * The findByIdAndCountry query should exclude several fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindByIdAndCountryQueryShouldExcludeSeveralFieldsAndChanges1040Field()
    {
        $restrictedFields = array('JPScalePC', 'LeastReachedPC', 'RLG3PC', 'PrimaryReligionPC', 'JPScalePGAC', 'LeastReachedPGAC', 'RLG3PGAC', 'PrimaryReligionPGAC', 'ROL3Edition14Orig', 'EthnologueCountryCode', 'EthnologueMapExists', 'WorldMapExists', 'UNMap', 'EthneMonth');
        $data = array('id' => '12662', 'country' => 'CB');
        $peopleGroup = new \QueryGenerators\PeopleGroup($data);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($restrictedFields as $field) {
            $this->assertFalse(array_key_exists($field, $data[0]));
        }
        $this->assertTrue(array_key_exists('Window10_40', $data[0]));
        $this->assertFalse(array_key_exists('10_40Window', $data[0]));
    }
    /**
     * The dailyUnreached query should exclude several fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testDailyUnreachedQueryShouldExcludeSeveralFieldsAndChanges1040Field()
    {
        $restrictedFields = array('JPScalePC', 'LeastReachedPC', 'RLG3PC', 'PrimaryReligionPC', 'JPScalePGAC', 'LeastReachedPGAC', 'RLG3PGAC', 'PrimaryReligionPGAC', 'ROL3Edition14Orig', 'EthnologueCountryCode', 'EthnologueMapExists', 'WorldMapExists', 'UNMap', 'EthneMonth');
        $data = array('month' => 1, 'day' => 11);
        $peopleGroup = new \QueryGenerators\PeopleGroup($data);
        $peopleGroup->dailyUnreached();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($restrictedFields as $field) {
            $this->assertFalse(array_key_exists($field, $data[0]));
        }
        $this->assertTrue(array_key_exists('Window10_40', $data[0]));
        $this->assertFalse(array_key_exists('10_40Window', $data[0]));
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('generateInStatementFromPipedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($peopleGroup, '1|2|3', 'PeopleId1');
        $this->assertEquals($expectedString, $actualString);
        $this->assertEquals($expectedKeys, array_keys($peopleGroup->preparedVariables));
        $this->assertEquals($expectedValues, array_values($peopleGroup->preparedVariables));
    }
}