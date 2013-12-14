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
 * Test the Query Generator for the Country Data
 *
 * @author Johnathan Pulos
 */
class CountryTest extends \PHPUnit_Framework_TestCase
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
        $data = array('country' => 'HORSE#%', 'state' => 'CA%$');
        $expected = array('country' => 'HORSE', 'state' => 'CA');
        $reflectionOfCountry = new \ReflectionClass('\QueryGenerators\Country');
        $providedParams = $reflectionOfCountry->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new \QueryGenerators\Country($data));
        $this->assertEquals($expected, $result);
    }
    /**
     * Several fields need to be renamed for easier use
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testQueryGeneratorShouldRenameTheCorrectFields()
    {
        $country = new \QueryGenerators\Country(array('id'  =>  'BE'));
        $country->findById();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertTrue(array_key_exists('Window10_40', $data[0]));
        $this->assertTrue(array_key_exists('Window10_40Original', $data[0]));
        $this->assertTrue(array_key_exists('Country', $data[0]));
        $this->assertTrue(array_key_exists('Country_ID', $data[0]));
        $this->assertTrue(array_key_exists('PrimaryReligion', $data[0]));
        $this->assertTrue(array_key_exists('RLG3', $data[0]));
    }
    /**
     * findById() should return the correct country, based on the supplied ID.
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindByIdShouldReturnTheCorrectCountry()
    {
        $expected = array('id'  =>  'BE');
        $expectedCountryName = 'Belgium';
        $country = new \QueryGenerators\Country($expected);
        $country->findById();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['id'], $data[0]['ROG3']);
        $this->assertEquals($expectedCountryName, $data[0]['Country']);
    }
    /**
     * findAllWithFilters() should return all countries with limit if there are no filters added
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldReturnAllCountriesWithoutFilters()
    {
        $expectedCount = 100;
        $expectedFirstCountry = 'Afghanistan';
        $country = new \QueryGenerators\Country(array());
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedCount, count($data));
        $this->assertEquals($expectedFirstCountry, $data[0]['Country']);
    }
    /**
     * findAllWithFilters() should return the set number of countries
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldLimitResults()
    {
        $expectedCount = 10;
        $country = new \QueryGenerators\Country(array('limit' => $expectedCount));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedCount, count($data));
    }
    /**
     * findAllWithFilters() should return only countries in the ids param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByIDs()
    {
        $expectedIDs = array('re', 'qa', 'qo');
        $country = new \QueryGenerators\Country(array('ids' => join('|', $expectedIDs)));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['ROG3']), $expectedIDs));
        }
    }
    /**
     * findAllWithFilters() should filter countries by continents
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByContinents()
    {
        $expectedContinents = array('lam', 'sop');
        $country = new \QueryGenerators\Country(array('continents' => join('|', $expectedContinents)));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['ROG2']), $expectedContinents));
        }
    }
    /**
     * findAllWithFilters() should filter countries by regions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByRegions()
    {
        $expectedRegions = array(1, 2);
        $country = new \QueryGenerators\Country(array('regions' => join('|', $expectedRegions)));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['RegionCode']), $expectedRegions));
        }
    }
    /**
     * findAllWithFilters() should filter countries by window1040
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByWindow1040()
    {
        $expectedWindow1040 = 'y';
        $country = new \QueryGenerators\Country(array('window1040' => $expectedWindow1040));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['Window10_40']), $expectedWindow1040);
        }
    }
    /**
     * findAllWithFilters() should filter countries by primary_languages
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPrimaryLanguages()
    {
        $expectedPrimaryLanguages = array('por', 'eng');
        $country = new \QueryGenerators\Country(array('primary_languages' => join('|', $expectedPrimaryLanguages)));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['ROL3OfficialLanguage']), $expectedPrimaryLanguages));
        }
    }
    /**
     * findAllWithFilters() should filter countries by population range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPopulationRange()
    {
        $expectedMin = 0;
        $expectedMax = 1000;
        $country = new \QueryGenerators\Country(array('population' => $expectedMin."-".$expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, intval($countryData['Population']));
            $this->assertGreaterThanOrEqual($expectedMin, intval($countryData['Population']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by exact population
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByExactPopulation()
    {
        $expectedPopulation = 1000;
        $country = new \QueryGenerators\Country(array('population' => $expectedPopulation));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals($expectedPopulation, intval($countryData['Population']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by primary religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPrimaryReligions()
    {
        $expectedReligions = array(2 => 'buddhism', 6 => 'islam');
        $country = new \QueryGenerators\Country(array('primary_religions' => join('|', array_keys($expectedReligions))));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['PrimaryReligion']), array_values($expectedReligions)));
            $this->assertTrue(in_array($countryData['RLG3'], array_keys($expectedReligions)));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Christianity
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentChristianity()
    {
        $expectedMin = 30;
        $expectedMax = 40;
        $country = new \QueryGenerators\Country(array('pc_christianity' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentChristianity']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentChristianity']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Evangelical
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentEvangelical()
    {
        $expectedMin = 20;
        $expectedMax = 50;
        $country = new \QueryGenerators\Country(array('pc_evangelical' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentEvangelical']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentEvangelical']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Buddhists
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentBuddhism()
    {
        $expectedMin = 15;
        $expectedMax = 45;
        $country = new \QueryGenerators\Country(array('pc_buddhist' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentBuddhism']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentBuddhism']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Ethnic Religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentEthnicReligions()
    {
        $expectedMin = 10;
        $expectedMax = 45;
        $country = new \QueryGenerators\Country(array('pc_ethnic_religion' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentEthnicReligions']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentEthnicReligions']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Hindu
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentHindu()
    {
        $expectedMin = 50;
        $expectedMax = 80;
        $country = new \QueryGenerators\Country(array('pc_hindu' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentHinduism']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentHinduism']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Islam
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentIslam()
    {
        $expectedMin = 50;
        $expectedMax = 90;
        $country = new \QueryGenerators\Country(array('pc_islam' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentIslam']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentIslam']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Non Religious
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentNonReligious()
    {
        $expectedMin = 43;
        $expectedMax = 69;
        $country = new \QueryGenerators\Country(array('pc_non_religious' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentNonReligious']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentNonReligious']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Other Religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentOtherReligions()
    {
        $expectedMin = 3;
        $expectedMax = 5;
        $country = new \QueryGenerators\Country(array('pc_other_religion' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentOtherSmall']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentOtherSmall']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Unknown
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentUnknown()
    {
        $expectedMin = 0;
        $expectedMax = 0.004;
        $country = new \QueryGenerators\Country(array('pc_unknown' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentUnknown']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentUnknown']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by JPScale
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPJPScale()
    {
        $expectedJPScales = "1.2|2.1";
        $expectedJPScalesArray = array(1.2, 2.1);
        $country = new \QueryGenerators\Country(array('jpscale' => $expectedJPScales));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(floatval($countryData['JPScaleCtry']), $expectedJPScalesArray));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Anglican
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentAnglican()
    {
        $expectedMin = 30;
        $expectedMax = 34;
        $country = new \QueryGenerators\Country(array('pc_anglican' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentAnglican']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentAnglican']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Independent
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentIndependent()
    {
        $expectedMin = 83;
        $expectedMax = 90.1;
        $country = new \QueryGenerators\Country(array('pc_independent' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentIndependent']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentIndependent']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Protestant
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentProtestant()
    {
        $expectedMin = 83;
        $expectedMax = 90.1;
        $country = new \QueryGenerators\Country(array('pc_protestant' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentProtestant']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentProtestant']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Orthodox
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentOrthodox()
    {
        $expectedMin = 94;
        $expectedMax = 97;
        $country = new \QueryGenerators\Country(array('pc_orthodox' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentOrthodox']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentOrthodox']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Roman Catholic
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentRomanCatholic()
    {
        $expectedMin = 84;
        $expectedMax = 87;
        $country = new \QueryGenerators\Country(array('pc_rcatholic' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentRomanCatholic']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentRomanCatholic']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Other Christians
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPercentOtherChristians()
    {
        $expectedMin = 33;
        $expectedMax = 34;
        $country = new \QueryGenerators\Country(array('pc_other_christian' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentOther']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentOther']));
        }
    }
}
