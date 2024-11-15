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

use PHPUnit\Framework\TestCase;
use QueryGenerators\PeopleGroup;

/**
 * Test the Query Generator for the People Group Data
 *
 * @author Johnathan Pulos
 */
class PeopleGroupTest extends TestCase
{
    private $db;

    public function setUp(): void
    {
        $this->db = getDatabaseInstance();
    }

    public function testShouldSanitizeProvidedDataOnInitializing(): void
    {
        $data = array('country' => 'AZX#%', 'state' => 'AZ%$');
        $expected = array('country' => 'AZX', 'state' => 'AZ');
        $reflectionOfPeopleGroup = new \ReflectionClass('QueryGenerators\PeopleGroup');
        $providedParams = $reflectionOfPeopleGroup->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new PeopleGroup($data));
        $this->assertEquals($expected, $result);
    }

    public function testFindByIdAndCountryShouldReturnTheCorrectPeopleGroup(): void
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedName = "Khmer";
        $peopleGroup = new PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['id'], $data[0]['PeopleID3']);
        $this->assertEquals($expected['country'], $data[0]['ROG3']);
        $this->assertEquals($expectedName, $data[0]['PeopNameInCountry']);
    }

    public function testPeopleGroupQueryGeneratorShouldReturnCorrectPeopleGroupURL(): void
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedURL = "https://joshuaproject.net/people_groups/12662/cb";
        $peopleGroup = new PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedURL, strtolower($data[0]['PeopleGroupURL']));
    }

    public function testPeopleGroupQueryGeneratorShouldReturnCorrectPeopleGroupPhotoURL(): void
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedURL = "https://joshuaproject.net/assets/media/profiles/photos/";
        $peopleGroup = new PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $expectedURL .= strtolower($data[0]['PhotoAddress']);
        $this->assertEquals($expectedURL, strtolower($data[0]['PeopleGroupPhotoURL']));
    }

    public function testPeopleGroupQueryGeneratorShouldReturnCorrectCountryURL(): void
    {
        $expected = array('id' => '12662', 'country' => 'CB');
        $expectedURL = "https://joshuaproject.net/countries/cb";
        $peopleGroup = new PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedURL, strtolower($data[0]['CountryURL']));
    }

    public function testPeopleGroupQueryGeneratorShouldReturnCorrectJPScaleImageURL(): void
    {
        $paramData = array('id' => '10350', 'country' => 'AA');
        $peopleGroup = new PeopleGroup($paramData);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $expectedImageURL = "https://joshuaproject.net/assets/img/gauge/gauge-".round(intval($data[0]['JPScale'])).".png";
        $this->assertEquals($expectedImageURL, $data[0]['JPScaleImageURL']);
    }

    public function testFindByIdAndCountryShouldErrorIfNoIdProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expected = array('country' => 'CB');
        $peopleGroup = new PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
    }

    public function testFindByIdAndCountryShouldErrorIfNoCountryProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expected = array('id' => '12662');
        $peopleGroup = new PeopleGroup($expected);
        $peopleGroup->findByIdAndCountry();
    }

    public function testFindByIdShouldReturnTheCorrectPeopleGroups(): void
    {
        $expected = array('id' => '12662');
        $expectedPeopleGroups = 13;
        $peopleGroup = new PeopleGroup($expected);
        $peopleGroup->findById();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['id'], $data[0]['PeopleID3']);
        $this->assertEquals($expectedPeopleGroups, count($data));
    }

    public function testFindByIdShouldErrorIfNoIDProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expected = array();
        $peopleGroup = new PeopleGroup($expected);
        $peopleGroup->findById();
    }

    public function testFindAllWithFiltersReturnsLimitedResultsWithNoFiltersByDefault(): void
    {
        $expectedNumberOfResults = 250;
        $peopleGroup = new PeopleGroup(array());
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedNumberOfResults, count($data));
    }

    public function testFindAllWithFiltersShouldFilterByPeopleID1(): void
    {
        $expectedPeopleIds = array(17, 23);
        $peopleGroup = new PeopleGroup(array('people_id1' => join("|", $expectedPeopleIds)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID1']), $expectedPeopleIds));
        }
    }

    public function testFindAllWithFiltersShouldFilterByROP1(): void
    {
        $expectedROP = array('A014', 'A010');
        $peopleGroup = new PeopleGroup(array('rop1' => join("|", $expectedROP)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROP1'], $expectedROP));
        }
    }

    public function testFindAllWithFiltersShouldFilterByROP1AndPeopleID1(): void
    {
        $expectedROP = 'A014';
        $expectedPeopleID = 23;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterByPeopleID2(): void
    {
        $expectedPeopleIDs = array(117, 115);
        $peopleGroup = new PeopleGroup(array('people_id2' => join("|", $expectedPeopleIDs)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID2']), $expectedPeopleIDs));
        }
    }

    public function testFindAllWithFiltersShouldFilterByROP2(): void
    {
        $expectedROP = array('C0013', 'C0067');
        $peopleGroup = new PeopleGroup(array('rop2' => join("|", $expectedROP)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROP2'], $expectedROP));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPeopleID3(): void
    {
        $expectedPeopleIDs = array(11722, 19204);
        $peopleGroup = new PeopleGroup(array('people_id3' => join("|", $expectedPeopleIDs)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID3']), $expectedPeopleIDs));
        }
    }

    public function testFindAllWithFiltersShouldFilterByROP3(): void
    {
        $expectedROP = array(115485, 115409);
        $peopleGroup = new PeopleGroup(array('rop3' => join("|", $expectedROP)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['ROP3']), $expectedROP));
        }
    }

    public function testFindAllWithFiltersShouldFilterByContinents(): void
    {
        $expectedContinents = array('AFR', 'NAR');
        $peopleGroup = new PeopleGroup(array('continents' => join("|", $expectedContinents)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROG2'], $expectedContinents));
        }
    }

    public function testFindAllWithFiltersShouldFilterByCountries(): void
    {
        $expectedCountries = array('AN', 'BG');
        $peopleGroup = new PeopleGroup(array('countries' => join("|", $expectedCountries)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROG3'], $expectedCountries));
        }
    }

    public function testFindAllWithFilterShouldErrorIfIncorrectContinent(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expectedCountries = array('BBC', 'DED');
        $peopleGroup = new PeopleGroup(array('continents' => join("|", $expectedCountries)));
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFilterShouldErrorIfIncorrectRegionCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $regionCodes = array(0, 13);
        $peopleGroup = new PeopleGroup(array('regions' => join("|", $regionCodes)));
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFiltersShouldFilterByRegions(): void
    {
        $expectedRegions = array(3 => 'asia, northeast', 4 => 'asia, south');
        $peopleGroup = new PeopleGroup(array('regions' => join("|", array_keys($expectedRegions))));
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

    public function testFindAllWithFiltersShouldFilterBy1040Window(): void
    {
        $expected1040Window = 'N';
        $peopleGroup = new PeopleGroup(array('window1040' => $expected1040Window));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals('N', $peopleGroup['Window1040']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByLanguages(): void
    {
        $expectedLanguages = array('AKA', 'ALE');
        $peopleGroup = new PeopleGroup(array('languages' => join("|", $expectedLanguages)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(strtoupper($peopleGroup['ROL3']), $expectedLanguages));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPopulationRange(): void
    {
        $expectedMin = 10000;
        $expectedMax = 20000;
        $peopleGroup = new PeopleGroup(array('population' => $expectedMin."-".$expectedMax));
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

    public function testFindAllWithFiltersShouldFilterByPrimaryReligions(): void
    {
        $expectedReligions = array(2 => 'buddhism', 6 => 'islam');
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterBySinglePopulation(): void
    {
        $expectedPop = 3900;
        $peopleGroup = new PeopleGroup(array('population' => $expectedPop));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals($expectedPop, intval($peopleGroup['Population']));
        }
    }

    public function testFindAllWithFiltersShouldThrowErrorWithIncorrectPopulation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $peopleGroup = new PeopleGroup(array('population' => '1900-23000-3400'));
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFiltersShouldThrowErrorWithMinPopulationGreaterThanMaxPopulation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $peopleGroup = new PeopleGroup(array('population' => '30000-1000'));
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFiltersShouldFilterByPercentOfAdherents(): void
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterByPercentOfAdherentsWithOnlyOneDecimalParameter(): void
    {
        $expectedPercent = 1.6;
        $peopleGroup = new PeopleGroup(array('pc_adherent' => $expectedPercent));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals($expectedPercent, floatval($peopleGroup['PercentAdherents']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPercentOfEvangelicals(): void
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterByPercentOfBuddhists(): void
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterByPercentOfEthnicReligions(): void
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterByPercentOfHindus(): void
    {
        $expectedPercentMin = 50.0;
        $expectedPercentMax = 60.1;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterByPercentOfIslam(): void
    {
        $expectedPercentMin = 20.0;
        $expectedPercentMax = 30.1;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterByPercentOfNonReligious(): void
    {
        $expectedPercentMin = 22.0;
        $expectedPercentMax = 40.1;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterByPercentOfOtherReligions(): void
    {
        $expectedPercentMin = 2.0;
        $expectedPercentMax = 10.3;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterByPercentOfUnknownReligions(): void
    {
        $expectedPercentMin = 2.0;
        $expectedPercentMax = 10.3;
        $peopleGroup = new PeopleGroup(
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

    public function testFindAllWithFiltersShouldFilterOutIndigenousPeopleGroups(): void
    {
        $expectedIndigenousStatus = 'n';
        $peopleGroup = new PeopleGroup(array('indigenous' => $expectedIndigenousStatus));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals('N', $peopleGroup['IndigenousCode']);
        }
    }

    public function testFindAllWithFiltersShouldFilterOutLeastReachedPeopleGroups(): void
    {
        $expectedLeastReachedStatus = 'n';
        $peopleGroup = new PeopleGroup(array('least_reached' => $expectedLeastReachedStatus));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals('N', $peopleGroup['LeastReached']);
        }
    }

    public function testFindAllWithFiltersShouldFiltersByJPScale(): void
    {
        $expectedJPScales = "1|2";
        $expectedJPScalesArray = array(1, 2);
        $peopleGroup = new PeopleGroup(array('jpscale' => $expectedJPScales));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array(floatval($peopleGroup['JPScale']), $expectedJPScalesArray));
        }
    }

    public function testFindAllWithFilterShouldErrorIfIncorrectJPScale(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expectedJPScales = "1.5|2.6";
        $peopleGroup = new PeopleGroup(array('jpscale' => $expectedJPScales));
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFilterShouldErrorIfIncorrectWindow1040(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $peopleGroup = new PeopleGroup(array('window1040' => 'b'));
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFilterShouldFilterByFrontier(): void
    {
        $peopleGroup = new PeopleGroup(array('is_frontier' => 'N'));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals('N', strtoupper($peopleGroup['Frontier']));
        }
    }

    public function testFindAllWithFilterShouldFilterByAllCountryPopulationInRange(): void
    {
        $min = 10000;
        $max = 11000;
        $peopleGroup = new PeopleGroup(array('population_pgac' => $min . '-' . $max));
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

    public function testFindAllWithFilterShouldFilterByAllCountryPopulationSingleValue(): void
    {
        $expected = 12000;
        $peopleGroup = new PeopleGroup(array('population_pgac' => $expected));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals($expected, $peopleGroup['PopulationPGAC']);
        }
    }

    public function testFindAllWithFliterShouldFilterByBibleStatus(): void
    {
        $expected = 2;
        $peopleGroup = new PeopleGroup(array('bible_status' => $expected));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertEquals($expected, $peopleGroup['BibleStatus']);
        }
    }

    public function testFindAllWithFliterShouldFilterByMultipleBibleStatus(): void
    {
        $expected = [2, 3];
        $peopleGroup = new PeopleGroup(array('bible_status' => join('|', $expected)));
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(in_array((int) $peopleGroup['BibleStatus'], $expected));
        }
    }

    public function testFindAllWithFiltersShouldAddPeopleId3Rog3Field(): void
    {
        $queryOne = new PeopleGroup(['people_id3' => 11722, 'countries' => 'YM']);
        $queryOne->findAllWithFilters();
        $statement = $this->db->prepare($queryOne->preparedStatement);
        $statement->execute($queryOne->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $peopleGroup) {
            $this->assertTrue(isset($peopleGroup['PeopleID3ROG3']));
            $this->assertEquals('11722YM', $peopleGroup['PeopleID3ROG3']);
        }
    }

    public function testFindByIdAndCountryShouldAddPeopleID3Rog3Field(): void
    {
        $params = array('id' => 11722, 'country' => 'AE');
        $peopleGroup = new PeopleGroup($params);
        $peopleGroup->findByIdAndCountry();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        $this->assertTrue(isset($data[0]['PeopleID3ROG3']));
        $this->assertEquals('11722AE', $data[0]['PeopleID3ROG3']);
    }

    public function testFindCountryListShouldReturnCountryList(): void
    {
        $expected = [
            ['ROG3' => 'CB', 'Ctry' => 'Cambodia', 'Population' => 11000, 'JPScale' => 4],
            ['ROG3' => 'LA', 'Ctry' => 'Laos', 'Population' => 28000, 'JPScale' => 1],
            ['ROG3' => 'VM', 'Ctry' => 'Vietnam', 'Population' => 500, 'JPScale' => 2]
        ];
        $peopleGroup = new PeopleGroup([]);
        $peopleGroup->findCountryList(10960);
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        $this->assertEquals(3, count($data));
        $this->assertEqualsCanonicalizing($expected, $data);
    }
}
