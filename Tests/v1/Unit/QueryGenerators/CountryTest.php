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
        // Limit is 250, but there is only 238 countries
        $expectedCount = 238;
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
            $expectedImageURL = "https://joshuaproject.net/images/scale".round($countryData['JPScaleCtry']).".jpg";
            $this->assertEquals(strtolower($countryData['JPScaleImageURL']), $expectedImageURL);
        }
    }

    public function testFindAllWithFiltersShouldFilterByCntPrimaryLanguagesInRange()
    {
        $min = 2;
        $max = 3;
        $country = new \QueryGenerators\Country(array(
            'cnt_primary_languages' => $min . '-' . $max,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertGreaterThanOrEqual($min, $countryData['CntPrimaryLanguages']);
            $this->assertLessThanOrEqual($max, $countryData['CntPrimaryLanguages']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByCntPrimaryLanguagesAtValue()
    {
        $value = 4;
        $country = new \QueryGenerators\Country(array(
            'cnt_primary_languages' => $value,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals($value, $countryData['CntPrimaryLanguages']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByTranslationUnspecifiedInRange()
    {
        $min = 1;
        $max = 2;
        $country = new \QueryGenerators\Country(array(
            'translation_unspecified' => $min . '-' . $max,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertGreaterThanOrEqual($min, $countryData['TranslationUnspecified']);
            $this->assertLessThanOrEqual($max, $countryData['TranslationUnspecified']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByTranslationUnspecifiedAtValue()
    {
        $value = 1;
        $country = new \QueryGenerators\Country(array(
            'translation_unspecified' => $value,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals($value, $countryData['TranslationUnspecified']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByTranslationNeededInRange()
    {
        $min = 1;
        $max = 2;
        $country = new \QueryGenerators\Country(array(
            'translation_needed' => $min . '-' . $max,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertGreaterThanOrEqual($min, $countryData['TranslationNeeded']);
            $this->assertLessThanOrEqual($max, $countryData['TranslationNeeded']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByTranslationNeededAtValue()
    {
        $value = 1;
        $country = new \QueryGenerators\Country(array(
            'translation_needed' => $value,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals($value, $countryData['TranslationNeeded']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByTranslationStartedInRange()
    {
        $min = 3;
        $max = 4;
        $country = new \QueryGenerators\Country(array(
            'translation_started' => $min . '-' . $max,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertGreaterThanOrEqual($min, $countryData['TranslationStarted']);
            $this->assertLessThanOrEqual($max, $countryData['TranslationStarted']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByTranslationStartedAtValue()
    {
        $value = 1;
        $country = new \QueryGenerators\Country(array(
            'translation_started' => $value,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals($value, $countryData['TranslationStarted']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByBiblePortionsInRange()
    {
        $min = 2;
        $max = 3;
        $country = new \QueryGenerators\Country(array(
            'bible_portions' => $min . '-' . $max,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertGreaterThanOrEqual($min, $countryData['BiblePortions']);
            $this->assertLessThanOrEqual($max, $countryData['BiblePortions']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByBiblePortionsAtValue()
    {
        $value = 0;
        $country = new \QueryGenerators\Country(array(
            'bible_portions' => $value,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals($value, $countryData['BiblePortions']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByBibleNewTestamentInRange()
    {
        $min = 3;
        $max = 4;
        $country = new \QueryGenerators\Country(array(
            'bible_new_testament' => $min . '-' . $max,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertGreaterThanOrEqual($min, $countryData['BibleNewTestament']);
            $this->assertLessThanOrEqual($max, $countryData['BibleNewTestament']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByBibleNewTestamentAtValue()
    {
        $value = 1;
        $country = new \QueryGenerators\Country(array(
            'bible_new_testament' => $value,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals($value, $countryData['BibleNewTestament']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByBibleCompleteInRange()
    {
        $min = 3;
        $max = 4;
        $country = new \QueryGenerators\Country(array(
            'bible_complete' => $min . '-' . $max,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertGreaterThanOrEqual($min, $countryData['BibleComplete']);
            $this->assertLessThanOrEqual($max, $countryData['BibleComplete']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByBibleCompleteAtValue()
    {
        $value = 18;
        $country = new \QueryGenerators\Country(array(
            'bible_complete' => $value,
            'limit' =>  5
        ));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals($value, $countryData['BibleComplete']);
        }
    }
}
