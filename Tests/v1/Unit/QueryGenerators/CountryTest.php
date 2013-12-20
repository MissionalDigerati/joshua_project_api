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
     * Query Generator should rename column names
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testQueryGeneratorShouldRenameFields()
    {
        $renamedFields = array(
                                '10_40Window'               =>  'Window1040',
                                'JPScaleCtry'               =>  'JPScale',
                                'Ctry'                      =>  'Country',
                                'ReligionPrimary'           =>  'PrimaryReligionText',
                                'PercentAnglican'           =>  'PCAnglican',
                                'PercentBuddhism'           =>  'PCBuddhist',
                                'PercentChristianity'       =>  'PCChristianity',
                                'PercentEthnicReligions'    =>  'PCEthnicReligion',
                                'PercentEvangelical'        =>  'PCEvangelical',
                                'PercentHinduism'           =>  'PCHindu',
                                'PercentIndependent'        =>  'PCIndependent',
                                'PercentIslam'              =>  'PCIslam',
                                'PercentNonReligious'       =>  'PCNonReligious',
                                'PercentOtherSmall'         =>  'PCOtherReligion',
                                'PercentOrthodox'           =>  'PCOrthodox',
                                'PercentOther'              =>  'PCOtherChristian',
                                'PercentProtestant'         =>  'PCProtestant',
                                'PercentRomanCatholic'      =>  'PCRCatholic',
                                'PercentUnknown'            =>  'PCUnknown',
                                'ROL3OfficialLanguage'      =>  'PrimaryLanguage',
                                'ROL3SecondaryLanguage'     =>  'SecondaryLanguage',
                                'RegionCode'                =>  'Region',
                                'InternetCtryCode'          =>  'InternetCountryCode'
                            );
        $country = new \QueryGenerators\Country(array('id'  =>  'BB'));
        $country->findById();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($renamedFields as $currentColumn => $renamedColumn) {
            $this->assertTrue(array_key_exists($renamedColumn, $data[0]));
            $this->assertFalse(array_key_exists($currentColumn, $data[0]));
        }
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
            $this->assertTrue(in_array(strtolower($countryData['Region']), $expectedRegions));
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
            $this->assertEquals(strtolower($countryData['Window1040']), $expectedWindow1040);
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
            $this->assertTrue(in_array(strtolower($countryData['PrimaryLanguage']), $expectedPrimaryLanguages));
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
            $this->assertTrue(in_array(strtolower($countryData['PrimaryReligionText']), array_values($expectedReligions)));
            $this->assertTrue(in_array($countryData['PrimaryReligion'], array_keys($expectedReligions)));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Christianity
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCChristianity()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCChristianity']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCChristianity']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Evangelical
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCEvangelical()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCEvangelical']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCEvangelical']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Buddhists
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCBuddhist()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCBuddhist']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCBuddhist']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Ethnic Religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCEthnicReligion()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCEthnicReligion']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCEthnicReligion']));
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCHindu']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCHindu']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Islam
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCIslam()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCIslam']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCIslam']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Non Religious
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCNonReligious()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCNonReligious']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCNonReligious']));
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCOtherReligion']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCOtherReligion']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Unknown
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCUnknown()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCUnknown']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCUnknown']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by JPScale
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByJPScale()
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
            $this->assertTrue(in_array(floatval($countryData['JPScale']), $expectedJPScalesArray));
        }
    }
    /**
     * Country Query Generator should set the JPScaleText to Unreached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testCountryQueryGeneratorShouldSetJPScaleTextToUnreached()
    {
        $expectedJPScaleText = "unreached";
        $country = new \QueryGenerators\Country(array('jpscale' => '1.2'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['JPScaleText']), $expectedJPScaleText);
        }
    }
    /**
     * Country Query Generator should set the JPScaleText to Nominal Church
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testCountryQueryGeneratorShouldSetJPScaleTextToNominalChurch()
    {
        $expectedJPScaleText = "nominal church";
        $country = new \QueryGenerators\Country(array('jpscale' => '2.1'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['JPScaleText']), $expectedJPScaleText);
        }
    }
    /**
     * Country Query Generator should set the JPScaleText to Established Church
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testCountryQueryGeneratorShouldSetJPScaleTextToEstablishedChurch()
    {
        $expectedJPScaleText = "established church";
        $country = new \QueryGenerators\Country(array('jpscale' => '3.2'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['JPScaleText']), $expectedJPScaleText);
        }
    }
    /**
     * Country Query Generator should set the JPScaleImageURL
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testCountryQueryGeneratorShouldSetJPScaleImageURL()
    {
        $expectedJPScaleText = "established church";
        $country = new \QueryGenerators\Country(array('jpscale' => '3.2'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $expectedImageURL = "http://www.joshuaproject.net/images/scale".round($countryData['JPScale']).".jpg";
            $this->assertEquals(strtolower($countryData['JPScaleImageURL']), $expectedImageURL);
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Anglican
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCAnglican()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCAnglican']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCAnglican']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Independent
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCIndependent()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCIndependent']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCIndependent']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Protestant
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCProtestant()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCProtestant']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCProtestant']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Orthodox
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCOrthodox()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCOrthodox']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCOrthodox']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by percent of Roman Catholic
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByPCRCatholic()
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCRCatholic']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCRCatholic']));
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PCOtherChristian']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PCOtherChristian']));
        }
    }
}
