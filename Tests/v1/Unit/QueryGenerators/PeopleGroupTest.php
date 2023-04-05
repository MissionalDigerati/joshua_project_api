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
 * Test the Query Generator for the People Group Data
 *
 * @author Johnathan Pulos
 */
class PeopleGroupTest extends \PHPUnit_Framework_TestCase
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
        $this->db = getDatabaseInstance();
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
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroup');
        $providedParams = $reflectionOfPeopleGroup->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new \QueryGenerators\PeopleGroup($data));
        $this->assertEquals($expected, $result);
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
        $expectedName = "Khmer";
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
    public function testPeopleGroupQueryGeneratorShouldReturnCorrectPeopleGroupURL()
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedURL = "http://joshuaproject.net/people_groups/12662/cb";
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
    public function testPeopleGroupQueryGeneratorShouldReturnCorrectPeopleGroupPhotoURL()
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
    public function testPeopleGroupQueryGeneratorShouldReturnCorrectCountryURL()
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedURL = "http://joshuaproject.net/countries/cb";
        $peopleGroup = new \QueryGenerators\PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedURL, strtolower($data[0]['CountryURL']));
    }
    /**
     * Should return the correct JPScaleImageURL
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testPeopleGroupQueryGeneratorShouldReturnCorrectJPScaleImageURL()
    {
        $paramData = array('id' => '10350', 'country' => 'AA');
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
    public function testFindByIdAndCountryShouldErrorIfNoIdProvided()
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
        $expectedPeopleGroups = 13;
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
    public function testFindAllWithFiltersReturnsLimitedResultsWithNoFiltersByDefault()
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'rop1' => $expectedROP, 'people_id1' => $expectedPeopleID
            )
        );
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
    public function testFindAllWithFilterShouldErrorIfIncorrectContinent()
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
    public function testFindAllWithFilterShouldErrorIfIncorrectRegionCode()
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
        $expectedRegions = array(3 => 'asia, northeast', 4 => 'asia, south');
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
            $this->assertEquals('N', $peopleGroup['Window1040']);
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'primary_religions' => join('|', array_keys($expectedReligions))
            )
        );
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(strtolower($peopleGroup['PrimaryReligion']), array_values($expectedReligions)));
            $this->assertEquals(6, $peopleGroup['RLG3']);
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
        $expectedPop = 3900;
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
    public function testFindAllWithFiltersShouldThrowErrorWithMinPopulationGreaterThanMaxPopulation()
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'pc_adherent' => $expectedPercentMin."-".$expectedPercentMax
            )
        );
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'pc_evangelical' => $expectedPercentMin."-".$expectedPercentMax
            )
        );
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'pc_buddhist' => $expectedPercentMin."-".$expectedPercentMax
            )
        );
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'pc_ethnic_religion' => $expectedPercentMin."-".$expectedPercentMax
            )
        );
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'pc_hindu' => $expectedPercentMin."-".$expectedPercentMax
            )
        );
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'pc_islam' => $expectedPercentMin."-".$expectedPercentMax
            )
        );
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
    public function testFindAllWithFiltersShouldFilterByPercentOfNonReligious()
    {
        $expectedPercentMin = 22.0;
        $expectedPercentMax = 40.1;
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'pc_non_religious' => $expectedPercentMin."-".$expectedPercentMax
            )
        );
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'pc_other_religion' => $expectedPercentMin."-".$expectedPercentMax
            )
        );
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
        $peopleGroup = new \QueryGenerators\PeopleGroup(
            array(
                'pc_unknown' => $expectedPercentMin."-".$expectedPercentMax
            )
        );
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
            $this->assertEquals('N', $peopleGroup['IndigenousCode']);
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
            $this->assertEquals('N', $peopleGroup['LeastReached']);
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
        $expectedJPScales = "1|2";
        $expectedJPScalesArray = array(1, 2);
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
      * Tests that findAllWithFilters() throws the correct error if one of the jpscale parameters is not a
      * required numbers
      *
      * @return void
      * @access public
      * @author Johnathan Pulos
      *
      * @expectedException InvalidArgumentException
      */
    public function testFindAllWithFilterShouldErrorIfIncorrectJPScale()
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
    public function testFindAllWithFilterShouldErrorIfIncorrectWindow1040()
    {
        $regionCodes = array(0, 13);
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('window1040' => 'b'));
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFilterShouldFilterByFrontier()
    {
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('is_frontier' => 'N'));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals('N', strtoupper($peopleGroup['Frontier']));
        }
    }

    public function testFindAllWithFilterShouldFilterByAllCountryPopulationInRange()
    {
        $min = 10000;
        $max = 11000;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('population_pgac' => $min . '-' . $max));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertGreaterThanOrEqual($min, $peopleGroup['PopulationPGAC']);
            $this->assertLessThanOrEqual($max, $peopleGroup['PopulationPGAC']);
        }
    }

    public function testFindAllWithFilterShouldFilterByAllCountryPopulationSingleValue()
    {
        $expected = 12000;
        $peopleGroup = new \QueryGenerators\PeopleGroup(array('population_pgac' => $expected));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals($expected, $peopleGroup['PopulationPGAC']);
        }
    }
}
