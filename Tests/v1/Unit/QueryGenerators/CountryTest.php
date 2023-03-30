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
class CountryTest extends \PHPUnit_Framework_TestCase
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
        $data = array('country' => 'HORSE#%', 'state' => 'CA%$');
        $expected = array('country' => 'HORSE', 'state' => 'CA');
        $reflectionOfCountry = new \ReflectionClass('\QueryGenerators\Country');
        $providedParams = $reflectionOfCountry->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new \QueryGenerators\Country($data));
        $this->assertEquals($expected, $result);
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
        $this->assertEquals($expectedCountryName, $data[0]['Ctry']);
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
        $this->assertEquals($expectedFirstCountry, $data[0]['Ctry']);
    }
    /**
     * findAllWithFilters() should return the set number of countries
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldLimitedResults()
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
    public function testFindAllWithFiltersShouldFilterByIds()
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
        $expectedPopulation = 44000;
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
        $country = new \QueryGenerators\Country(
            array(
                'primary_religions' => join('|', array_keys($expectedReligions))
            )
        );
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['ReligionPrimary']), array_values($expectedReligions)));
            $this->assertTrue(in_array($countryData['RLG3Primary'], array_keys($expectedReligions)));
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
        $expectedMax = 90;
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
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentUnknown']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentUnknown']));
        }
    }
    /**
     * findAllWithFilters() should filter countries by JPScaleCtry
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldFilterByJPScale()
    {
        $expectedJPScales = "1|2";
        $expectedJPScalesArray = array(1, 2);
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
     * Country Query Generator should set the JPScaleText to Unreached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testCountryQueryGeneratorShouldSetJPScaleTextToUnreached()
    {
        $expectedJPScaleText = "unreached";
        $country = new \QueryGenerators\Country(array('jpscale' => '1'));
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
     * Country Query Generator should set the JPScaleText to Minimally Reached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testCountryQueryGeneratorShouldSetJPScaleTextToMinimallyReached()
    {
        $expectedJPScaleText = "minimally reached";
        $country = new \QueryGenerators\Country(array('jpscale' => '2'));
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
     * Country Query Generator should set the JPScaleText to Superficially Reached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testCountryQueryGeneratorShouldSetJPScaleTextToSuperficiallyReached()
    {
        $expectedJPScaleText = "superficially reached";
        $country = new \QueryGenerators\Country(array('jpscale' => '3'));
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
     * Country Query Generator should set the JPScaleText to Partially Reached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testCountryQueryGeneratorShouldSetJPScaleTextToPartiallyReached()
    {
        $expectedJPScaleText = "partially reached";
        $country = new \QueryGenerators\Country(array('jpscale' => '4'));
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
     * Country Query Generator should set the JPScaleText to Significantly Reached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testCountryQueryGeneratorShouldSetJPScaleTextToSignificantlyReached()
    {
        $expectedJPScaleText = "significantly reached";
        $country = new \QueryGenerators\Country(array('jpscale' => '5'));
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
    public function testCountryQueryGeneratorShouldSetJPScaleImageURLCorrectly()
    {
        $expectedJPScaleText = "established church";
        $country = new \QueryGenerators\Country(array('jpscale' => '4'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $expectedImageURL = "http://www.joshuaproject.net/images/scale".round($countryData['JPScaleCtry']).".jpg";
            $this->assertEquals(strtolower($countryData['JPScaleImageURL']), $expectedImageURL);
        }
    }
}
