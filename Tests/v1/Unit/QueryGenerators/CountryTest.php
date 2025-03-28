<?php
declare(strict_types=1);

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

use \QueryGenerators\Country;
use PHPUnit\Framework\TestCase;

/**
 * Test the Query Generator for the Country Data
 *
 * @author Johnathan Pulos
 */
class CountryTest extends TestCase
{
    private $db;

    public function setUp(): void
    {
        $this->db = getDatabaseInstance();
    }

    public function testShouldSanitizeProvidedDataOnInitializing(): void
    {
        $data = array('country' => 'HORSE#%', 'state' => 'CA%$');
        $expected = array('country' => 'HORSE', 'state' => 'CA');
        $reflectionOfCountry = new \ReflectionClass('\QueryGenerators\Country');
        $providedParams = $reflectionOfCountry->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new Country($data));
        $this->assertEquals($expected, $result);
    }

    public function testFindByIdShouldReturnTheCorrectCountry(): void
    {
        $expected = array('id'  =>  'BE');
        $expectedCountryName = 'Belgium';
        $country = new Country($expected);
        $country->findById();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['id'], $data[0]['ROG3']);
        $this->assertEquals($expectedCountryName, $data[0]['Ctry']);
    }

    public function testFindAllWithFiltersShouldReturnAllCountriesWithoutFilters(): void
    {
        // Limit is 250, but there is only 238 countries
        $expectedCount = 238;
        $expectedFirstCountry = 'Afghanistan';
        $country = new Country(array());
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedCount, count($data));
        $this->assertEquals($expectedFirstCountry, $data[0]['Ctry']);
    }

    public function testFindAllWithFiltersShouldLimitedResults(): void
    {
        $expectedCount = 10;
        $country = new Country(array('limit' => $expectedCount));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedCount, count($data));
    }

    public function testFindAllWithFiltersShouldFilterByIds(): void
    {
        $expectedIDs = array('re', 'qa', 'qo');
        $country = new Country(array('ids' => join('|', $expectedIDs)));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['ROG3']), $expectedIDs));
        }
    }

    public function testFindAllWithFiltersShouldFilterByContinents(): void
    {
        $expectedContinents = array('lam', 'sop');
        $country = new Country(array('continents' => join('|', $expectedContinents)));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['ROG2']), $expectedContinents));
        }
    }

    public function testFindAllWithFiltersShouldFilterByRegions(): void
    {
        $expectedRegions = array(1, 2);
        $country = new Country(array('regions' => join('|', $expectedRegions)));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertTrue(in_array($countryData['RegionCode'], $expectedRegions));
        }
    }

    public function testFindAllWithFiltersShouldFilterByWindow1040(): void
    {
        $expectedWindow1040 = 'y';
        $country = new Country(array('window1040' => $expectedWindow1040));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['Window1040']), $expectedWindow1040);
        }
    }

    public function testFindAllWithFiltersShouldFilterByPrimaryLanguages(): void
    {
        $expectedPrimaryLanguages = array('por', 'eng');
        $country = new Country(array('primary_languages' => join('|', $expectedPrimaryLanguages)));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['ROL3OfficialLanguage']), $expectedPrimaryLanguages));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPopulationRange(): void
    {
        $expectedMin = 0;
        $expectedMax = 1000;
        $country = new Country(array('population' => $expectedMin."-".$expectedMax));
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

    public function testFindAllWithFiltersShouldFilterByExactPopulation(): void
    {
        $expectedPopulation = 44000;
        $country = new Country(array('population' => $expectedPopulation));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals($expectedPopulation, intval($countryData['Population']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPrimaryReligions(): void
    {
        $expectedReligions = array(2 => 'buddhism', 6 => 'islam');
        $country = new Country(
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

    public function testFindAllWithFiltersShouldFilterByJPScale(): void
    {
        $expectedJPScales = "1|2";
        $expectedJPScalesArray = array(1, 2);
        $country = new Country(array('jpscale' => $expectedJPScales));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(in_array(floatval($countryData['JPScaleCtry']), $expectedJPScalesArray));
        }
    }

    public function testCountryQueryGeneratorShouldSetJPScaleTextToUnreached(): void
    {
        $expectedJPScaleText = "unreached";
        $country = new Country(array('jpscale' => '1'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['JPScaleText']), $expectedJPScaleText);
        }
    }

    public function testCountryQueryGeneratorShouldSetJPScaleTextToMinimallyReached(): void
    {
        $expectedJPScaleText = "minimally reached";
        $country = new Country(array('jpscale' => '2'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['JPScaleText']), $expectedJPScaleText);
        }
    }

    public function testCountryQueryGeneratorShouldSetJPScaleTextToSuperficiallyReached(): void
    {
        $expectedJPScaleText = "superficially reached";
        $country = new Country(array('jpscale' => '3'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['JPScaleText']), $expectedJPScaleText);
        }
    }

    public function testCountryQueryGeneratorShouldSetJPScaleTextToPartiallyReached(): void
    {
        $expectedJPScaleText = "partially reached";
        $country = new Country(array('jpscale' => '4'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['JPScaleText']), $expectedJPScaleText);
        }
    }

    public function testCountryQueryGeneratorShouldSetJPScaleTextToSignificantlyReached(): void
    {
        $expectedJPScaleText = "significantly reached";
        $country = new Country(array('jpscale' => '5'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertEquals(strtolower($countryData['JPScaleText']), $expectedJPScaleText);
        }
    }

    public function testCountryQueryGeneratorShouldSetJPScaleImageURLCorrectly(): void
    {
        $expectedJPScaleText = "established church";
        $country = new Country(array('jpscale' => '4'));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $expectedImageURL = "https://joshuaproject.net/assets/img/gauge/gauge-".round(intval($countryData['JPScaleCtry'])).".png";
            $this->assertEquals(strtolower($countryData['JPScaleImageURL']), $expectedImageURL);
        }
    }

    public function testFindAllWithFiltersShouldFilterByCntPrimaryLanguagesInRange(): void
    {
        $min = 2;
        $max = 3;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByCntPrimaryLanguagesAtValue(): void
    {
        $value = 4;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByTranslationUnspecifiedInRange(): void
    {
        $min = 1;
        $max = 2;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByTranslationUnspecifiedAtValue(): void
    {
        $value = 1;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByTranslationNeededInRange(): void
    {
        $min = 1;
        $max = 2;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByTranslationNeededAtValue(): void
    {
        $value = 1;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByTranslationStartedInRange(): void
    {
        $min = 3;
        $max = 4;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByTranslationStartedAtValue(): void
    {
        $value = 1;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByBiblePortionsInRange(): void
    {
        $min = 2;
        $max = 3;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByBiblePortionsAtValue(): void
    {
        $value = 0;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByBibleNewTestamentInRange(): void
    {
        $min = 3;
        $max = 4;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByBibleNewTestamentAtValue(): void
    {
        $value = 1;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByBibleCompleteInRange(): void
    {
        $min = 3;
        $max = 4;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByBibleCompleteAtValue(): void
    {
        $value = 18;
        $country = new Country(array(
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

    public function testFindAllWithFiltersShouldFilterByPopLivingAmongUnreached(): void
    {
        $expectedMin = 0;
        $expectedMax = 1000;
        $country = new Country(array('pop_in_unreached' => $expectedMin."-".$expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, intval($countryData['PoplPeoplesLR']));
            $this->assertGreaterThanOrEqual($expectedMin, intval($countryData['PoplPeoplesLR']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPopLivingAmongFrontier(): void
    {
        $expectedMin = 1000;
        $expectedMax = 3000;
        $country = new Country(array('pop_in_frontier' => $expectedMin."-".$expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, intval($countryData['PoplPeoplesFPG']));
            $this->assertGreaterThanOrEqual($expectedMin, intval($countryData['PoplPeoplesFPG']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPCBuddhist(): void
    {
        $expectedMin = 15;
        $expectedMax = 45;
        $country = new Country(array('pc_buddhist' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(array_key_exists('PercentBuddhism', $countryData));
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentBuddhism']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentBuddhism']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPCChristianity(): void
    {
        $expectedMin = 30;
        $expectedMax = 40;
        $country = new Country(array('pc_christianity' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(array_key_exists('PercentChristianity', $countryData));
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentChristianity']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentChristianity']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPCEthnicReligion(): void
    {
        $expectedMin = 10;
        $expectedMax = 45;
        $country = new Country(array('pc_ethnic_religion' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(array_key_exists('PercentEthnicReligions', $countryData));
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentEthnicReligions']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentEthnicReligions']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPCEvangelical(): void
    {
        $expectedMin = 20;
        $expectedMax = 50;
        $country = new Country(array('pc_evangelical' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(array_key_exists('PercentEvangelical', $countryData));
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentEvangelical']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentEvangelical']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPercentHindu(): void
    {
        $expectedMin = 50;
        $expectedMax = 90;
        $country = new Country(array('pc_hindu' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(array_key_exists('PercentHinduism', $countryData));
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentHinduism']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentHinduism']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPCIslam(): void
    {
        $expectedMin = 50;
        $expectedMax = 90;
        $country = new Country(array('pc_islam' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(array_key_exists('PercentIslam', $countryData));
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentIslam']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentIslam']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPCNonReligious(): void
    {
        $expectedMin = 43;
        $expectedMax = 69;
        $country = new Country(array('pc_non_religious' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(array_key_exists('PercentNonReligious', $countryData));
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentNonReligious']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentNonReligious']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPercentOtherReligions(): void
    {
        $expectedMin = 3;
        $expectedMax = 5;
        $country = new Country(array('pc_other_religion' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(array_key_exists('PercentOtherSmall', $countryData));
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentOtherSmall']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentOtherSmall']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPCUnknown(): void
    {
        $expectedMin = 0;
        $expectedMax = 0.004;
        $country = new Country(array('pc_unknown' => $expectedMin . '-' . $expectedMax));
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $countryData) {
            $this->assertTrue(array_key_exists('PercentUnknown', $countryData));
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentUnknown']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentUnknown']));
        }
    }

    public function testFindAllWithFiltersShouldAllowSettingSortOrder(): void
    {
        $country = new Country(['sort_field' => 'Ctry', 'sort_direction' => 'DESC', 'limit' => 10]);
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $sorted = $data;
        usort($sorted, fn ($a, $b) => strcmp($b['Ctry'], $a['Ctry']));
        $this->assertEquals($sorted, $data);
    }

    public function testFindAllWithFiltersShouldSortByDefault(): void
    {
        $country = new Country(['limit' => 10]);
        $country->findAllWithFilters();
        $statement = $this->db->prepare($country->preparedStatement);
        $statement->execute($country->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $sorted = $data;
        usort($sorted, fn ($a, $b) => strcmp($a['Ctry'], $b['Ctry']));
        $this->assertEquals($sorted, $data);
    }

    public function testFindAllWithFilterWillThrowErrorIfWrongDirectionProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $country = new Country(['sort_field' => 'RegionName', 'sort_direction' => 'WRONG', 'limit' => 10]);
        $country->findAllWithFilters();
    }

    public function testFindAllWithFilterWillThrowErrorIfNonWhitelistedFieldProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $country = new Country(['sort_field' => 'WRONG', 'sort_direction' => 'ASC', 'limit' => 10]);
        $country->findAllWithFilters();
    }

}
