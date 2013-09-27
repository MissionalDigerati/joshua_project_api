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
     * Validate that validateVariableInRange() throws error if it is not in range
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
     * Validate that validateVariableInRange() throws an error in the integer is in the $exceptions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * @expectedException InvalidArgumentException
     */
    public function testValidateVariableInRangeShouldThrowErrorIfVariableIsAnException()
    {
        $data = array('exception' => 5);
        $peopleGroup = new \QueryGenerators\PeopleGroup($data);
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('validateVariableInRange');
        $method->setAccessible(true);
        $actual = $method->invoke($peopleGroup, 'exception', 1, 7, array(5));
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
     * Should return the correct PeopleGroup URL
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testQueryShouldReturnCorrectPeopleGroupURL()
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedURL = "http://www.joshuaproject.net/people-profile.php?peo3=12662&amp;rog3=cb";
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedURL, strtolower($data[0]['PeopleGroupURL']));
    }
    /**
     * Should return the correct PeopleGroup Photo URL
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testQueryShouldReturnCorrectPeopleGroupPhotoURL()
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedURL = "http://www.joshuaproject.net/profiles/photos/";
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $expectedURL .= strtolower($data[0]['PhotoAddress']);
        $this->assertEquals($expectedURL, strtolower($data[0]['PeopleGroupPhotoURL']));
    }
    /**
     * Should return the correct Country URL
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testQueryShouldReturnCorrectCountryURL()
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedURL = "http://www.joshuaproject.net/countries.php?rog3=cb";
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedURL, strtolower($data[0]['CountryURL']));
    }
    /**
     * Should return the correct JPScaleText Equal to Unreached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testQueryShouldReturnCorrectJPScaleTextEqualToUnreached()
    {
        $paramData = array('id' => '12662', 'country' => 'CB');
        $expectedText = "unreached";
        $peopleGroup = new \QueryGenerators\PeopleGroup($paramData);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertGreaterThan(1, $data[0]['JPScale']);
        $this->assertLessThan(2, $data[0]['JPScale']);
        $this->assertEquals($expectedText, strtolower($data[0]['JPScaleText']));
    }
    /**
     * Should return the correct JPScaleText Equal to Nominal Church
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testQueryShouldReturnCorrectJPScaleTextEqualToNominalChurch()
    {
        $paramData = array('id' => '19007', 'country' => 'AE');
        $expectedText = "nominal church";
        $peopleGroup = new \QueryGenerators\PeopleGroup($paramData);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertGreaterThan(2, $data[0]['JPScale']);
        $this->assertLessThan(3, $data[0]['JPScale']);
        $this->assertEquals($expectedText, strtolower($data[0]['JPScaleText']));
    }
    /**
     * Should return the correct JPScaleImageURL
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testQueryShouldReturnCorrectJPScaleImageURL()
    {
        $paramData = array('id' => '16153', 'country' => 'IN');
        $peopleGroup = new \QueryGenerators\PeopleGroup($paramData);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $expectedImageURL = "http://www.joshuaproject.net/images/scale".round($data[0]['JPScale']).".jpg";
        $this->assertEquals($expectedImageURL, $data[0]['JPScaleImageURL']);
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
     * Tests that findAllWithFilters() filters by a population range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPopulationRange()
    {
        $expectedMin = 10000;
        $expectedMax = 20000;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('population' => $expectedMin."-".$expectedMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedMax, intval($peopleGroup['Population']));
            $this->assertGreaterThanOrEqual($expectedMin, intval($peopleGroup['Population']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by primary religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPrimaryReligions()
    {
        $expectedReligions = array(2 => 'buddhism', 6 => 'islam');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('primary_religions' => join('|', array_keys($expectedReligions))));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(strtolower($peopleGroup['PrimaryReligion']), array_values($expectedReligions)));
            $this->assertTrue(in_array($peopleGroup['RLG3'], array_keys($expectedReligions)));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a single population
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterBySinglePopulation()
    {
        $expectedPop = 19900;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('population' => $expectedPop));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals($expectedPop, intval($peopleGroup['Population']));
        }
    }
    /**
     * Tests that findAllWithFilters() throws error if incorrect population sent
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindAllWithFiltersShouldThrowErrorWithIncorrectPopulation()
    {
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('population' => '1900-23000-3400'));
        $peopleGroup->findAllWithFilters();
    }
    /**
     * Tests that findAllWithFilters() throws error if min is greater then max population
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindAllWithFiltersShouldThrowErrorWithMinPopulationGreaterThenMaxPopulation()
    {
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('population' => '30000-1000'));
        $peopleGroup->findAllWithFilters();
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of adherents
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfAdherents()
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_adherent' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PercentAdherents']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PercentAdherents']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of adherents with only 1 decimal value
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfAdherentsWithOnlyOneDecimalParameter()
    {
        $expectedPercent = 1.6;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_adherent' => $expectedPercent));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals($expectedPercent, floatval($peopleGroup['PercentAdherents']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of evangelicals
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfEvangelicals()
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_evangelical' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PercentEvangelical']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PercentEvangelical']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of buddhists
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfBuddhists()
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_buddhist' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCBuddhism']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCBuddhism']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of ethnic religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfEthnicReligions()
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_ethnic_religion' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCEthnicReligions']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCEthnicReligions']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of hindus
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfHindus()
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_hindu' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCHinduism']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCHinduism']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of islam
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfIslam()
    {
        $expectedPercentMin = 20.0;
        $expectedPercentMax = 30.1;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_islam' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCIslam']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCIslam']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of non-religious
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfNonReligions()
    {
        $expectedPercentMin = 22.0;
        $expectedPercentMax = 40.1;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_non_religious' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCNonReligious']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCNonReligious']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of Other Religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfOtherReligions()
    {
        $expectedPercentMin = 2.0;
        $expectedPercentMax = 10.3;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_other_religion' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCOtherSmall']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCOtherSmall']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of Unknown Religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfUnknownReligions()
    {
        $expectedPercentMin = 2.0;
        $expectedPercentMax = 10.3;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_unknown' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCUnknown']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCUnknown']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of Anglicans
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfAnglicans()
    {
        $expectedPercentMin = 5.4;
        $expectedPercentMax = 21.2;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_anglican' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCAnglican']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCAnglican']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of Independents
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfIndependents()
    {
        $expectedPercentMin = 5.4;
        $expectedPercentMax = 21.2;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_independent' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCIndependent']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCIndependent']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of Protestants
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfProtestants()
    {
        $expectedPercentMin = 33.4;
        $expectedPercentMax = 66.74;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_protestant' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCProtestant']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCProtestant']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of Orthodox
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfOrthodox()
    {
        $expectedPercentMin = 22.43;
        $expectedPercentMax = 74.56;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_orthodox' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCOrthodox']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCOrthodox']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of Roman Catholic
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfRomanCatholic()
    {
        $expectedPercentMin = 22.43;
        $expectedPercentMax = 74.56;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_rcatholic' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCRomanCatholic']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCRomanCatholic']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters by a percent of Other Christian
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterByPercentOfOtherChristian()
    {
        $expectedPercentMin = 22.43;
        $expectedPercentMax = 74.56;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('pc_other_christian' => $expectedPercentMin."-".$expectedPercentMax));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCOtherChristian']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCOtherChristian']));
        }
    }
    /**
     * Tests that findAllWithFilters() filters out Indigenous Groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterOutIndigenousPeopleGroups()
    {
        $expectedIndigenousStatus = 'n';
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('indigenous' => $expectedIndigenousStatus));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertNull($peopleGroup['IndigenousCode']);
        }
    }
    /**
     * Tests that findAllWithFilters() filters out Least Reached Groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterOutLeastReachedPeopleGroups()
    {
        $expectedLeastReachedStatus = 'n';
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('least_reached' => $expectedLeastReachedStatus));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertNull($peopleGroup['LeastReached']);
        }
    }
    /**
     * Tests that findAllWithFilters() filters out Unengaged Groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFilterOutUnengagedPeopleGroups()
    {
        $expectedUnengagedStatus = 'n';
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('unengaged' => $expectedUnengagedStatus));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals('', $peopleGroup['Unengaged']);
        }
    }
    /**
     * Tests that findAllWithFilters() filters by JPScale
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllWithFiltersShouldFiltersByJPScale()
    {
        $expectedJPScales = "1.2|2.1";
        $expectedJPScalesArray = array(1.2, 2.1);
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('jpscale' => $expectedJPScales));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(floatval($peopleGroup['JPScale']), $expectedJPScalesArray));
        }
    }
    /**
      * Tests that findAllWithFilters() throws the correct error if one of the jpscale parameters is not a required numbers
      *
      * @return void
      * @access public
      * @author Johnathan Pulos
      * 
      * @expectedException InvalidArgumentException
      */
    public function testShouldErrorIfFindAllWithFilterReceivesIncorrectJPScaleParameter()
    {
        $expectedJPScales = "1.5|2.6";
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('jpscale' => $expectedJPScales));
        $peopleGroup->findAllWithFilters();
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
    /**
     * generateBetweenStatementFromDashSeperatedString() should generate an appropriate BETWEEN statement with a max and minimum
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('generateBetweenStatementFromDashSeperatedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($peopleGroup, '10-20', 'Population', 'pop');
        $this->assertEquals($expectedString, $actualString);
        $this->assertEquals($expectedKeys, array_keys($peopleGroup->preparedVariables));
        $this->assertEquals($expectedValues, array_values($peopleGroup->preparedVariables));
    }
    /**
     * generateBetweenStatementFromDashSeperatedString() should generate an appropriate statement with only a minimum
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testGenerateBetweenStatementFromDashSeperatedStringShouldReturnCorrectStatementWithAMinOnly()
    {
        $expectedString = "Population = :total_population";
        $expectedKeys = array('total_population');
        $expectedValues = array(10);
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('generateBetweenStatementFromDashSeperatedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($peopleGroup, '10', 'Population', 'population');
        $this->assertEquals($expectedString, $actualString);
        $this->assertEquals($expectedKeys, array_keys($peopleGroup->preparedVariables));
        $this->assertEquals($expectedValues, array_values($peopleGroup->preparedVariables));
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
    public function testGenerateBetweenStatementFromDashSeperatedStringShouldThrowErrorIfMoreThenMaxAndMinAreGiven()
    {
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('generateBetweenStatementFromDashSeperatedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($peopleGroup, '10-20-39', 'Population', 'population');
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
    public function testGenerateBetweenStatementFromDashSeperatedStringShouldThrowErrorIfMoreMinIsGreaterThanMax()
    {
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('generateBetweenStatementFromDashSeperatedString');
        $method->setAccessible(true);
        $actualString = $method->invoke($peopleGroup, '30-20', 'Population', 'population');
    }
    /**
     * Tests that generateWhereStatementForBoolean generates the correct statement for a Yes Boolean
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testGenerateWhereStatementFromBooleanShouldReturnTheCorrectStatementForAYes()
    {
        $expectedStatement = "IndigenousCode = :indigenous";
        $expectedKeys = array('indigenous');
        $expectedValues = array('Y');
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('generateWhereStatementForBoolean');
        $method->setAccessible(true);
        $actualStatement = $method->invoke($peopleGroup, 'y', 'IndigenousCode', 'indigenous');
        $this->assertEquals($expectedStatement, $actualStatement);
        $this->assertEquals($expectedKeys, array_keys($peopleGroup->preparedVariables));
        $this->assertEquals($expectedValues, array_values($peopleGroup->preparedVariables));
    }
    /**
     * Tests that generateWhereStatementForBoolean generates the correct statement for a No Boolean
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testGenerateWhereStatementFromBooleanShouldReturnTheCorrectStatementForANo()
    {
        $expectedStatement = "(10_40Window IS NULL OR 10_40Window = '')";
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('generateWhereStatementForBoolean');
        $method->setAccessible(true);
        $actualStatement = $method->invoke($peopleGroup, 'n', '10_40Window', 'window_10_40');
        $this->assertEquals($expectedStatement, $actualStatement);
        $this->assertTrue(empty($peopleGroup->preparedVariables));
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
    public function testGenerateWhereStatementFromBooleanShouldThrowErrorIfNotYOrN()
    {
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('generateWhereStatementForBoolean');
        $method->setAccessible(true);
        $actualString = $method->invoke($peopleGroup, 'p', 'Population', 'population');
    }
    /**
     * Tests that validateBarSeperatedStringValueInArray() throws error if not in the given array
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testValidateBarSeperatedStringValueInArrayShouldThrowErrorIfNotInexpectedValues()
    {
        $peopleGroup = new \QueryGenerators\PeopleGroup(array());
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $method = $reflectionOfPeopleGroup->getMethod('validateBarSeperatedStringValuesInArray');
        $method->setAccessible(true);
        $actualString = $method->invoke($peopleGroup, '2.3|34.4', array('1.1', '5.4'));
    }
}
