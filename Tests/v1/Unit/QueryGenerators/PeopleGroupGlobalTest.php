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

 declare(strict_types=1);

namespace Tests\v1\Unit\QueryGenerators;

use PHPUnit\Framework\TestCase;
use QueryGenerators\PeopleGroupGlobal;



/**
 * Test the Query Generator for the People Group Global Data
 *
 * @author Johnathan Pulos
 */
class PeopleGroupGlobalTest extends TestCase
{

    private $db;

    public function setUp(): void
    {
        $this->db = getDatabaseInstance();
    }

    public function testInitializationShouldSanitizeProvidedData(): void
    {
        $data = ['country' => 'AZX#%', 'state' => 'AZ%$'];
        $expected = ['country' => 'AZX', 'state' => 'AZ'];
        $reflectionOfPeopleGroup = new \ReflectionClass('\QueryGenerators\PeopleGroupGlobal');
        $providedParams = $reflectionOfPeopleGroup->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new PeopleGroupGlobal($data));
        $this->assertEquals($expected, $result);
    }

    public function testFindByIdShouldReturnCorrectPeopleGroup(): void
    {
        $peopleGroup = new PeopleGroupGlobal(['id' => 22279]);
        $peopleGroup->findById();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals(1, count($data));
        $this->assertEquals(22279, $data[0]['PeopleID3']);
        $this->assertEquals('Pashtun Tirahi', $data[0]['PeopName']);
        $this->assertEquals(21, $data[0]['PeopleID1']);
        $this->assertEquals('South Asian Peoples', $data[0]['AffinityBloc']);
        $this->assertEquals(463, $data[0]['PeopleID2']);
        $this->assertEquals('South Asia Muslim - Pashtun', $data[0]['PeopleCluster']);
        $this->assertEquals(119298, $data[0]['ROP3']);
        $this->assertEquals(306585, $data[0]['ROP25']);
        $this->assertEquals('Pashtun', $data[0]['ROP25Name']);
        $this->assertEquals(1, $data[0]['JPScalePGAC']);
        $this->assertEquals(29000, $data[0]['PopulationPGAC']);
        $this->assertEquals('Y', $data[0]['LeastReachedPGAC']);
        $this->assertEquals('Y', $data[0]['FrontierPGAC']);
        $this->assertEquals(1, $data[0]['CntPGIC']);
        $this->assertEquals(1, $data[0]['CntUPG']);
        $this->assertEquals(1, $data[0]['CntFPG']);
        $this->assertEquals('PK', $data[0]['ROG3Largest']);
        $this->assertEquals('Pakistan', $data[0]['CtryLargest']);
        $this->assertEquals('pbu', $data[0]['ROL3PGAC']);
        $this->assertEquals('Pashto, Northern', $data[0]['PrimaryLanguagePGAC']);
        $this->assertEquals(6, $data[0]['RLG3PGAC']);
        $this->assertEquals('Islam', $data[0]['PrimaryReligionPGAC']);
        $this->assertEquals(0.000, $data[0]['PercentChristianPGAC']);
        $this->assertEquals(0.000, $data[0]['PercentEvangelicalPGAC']);
        $this->assertEquals('Unreached', $data[0]['JPScaleText']);
        $this->assertEquals('https://joshuaproject.net/assets/img/gauge/gauge-1.png', $data[0]['JPScaleImageURL']);
    }

    public function testFindByIdShouldThrowExceptionIfIdIsNotProvided(): void
    {
        $this->expectException(\Exception::class);
        $peopleGroup = new PeopleGroupGlobal([]);
        $peopleGroup->findById();
    }

    public function testFindAllWithFiltersShouldReturnAllResultsWithoutFilters(): void
    {
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $peopleGroup = new PeopleGroupGlobal(['limit' => $count + 100]);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
    }

    public function testFindAllWithFiltersShouldReturnFilteredByPeopleID3(): void
    {
        $ids = [10122, 10121, 21337, 15625];
        $params = ['people_id3' => implode('|', $ids)];
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals(count($ids), count($data));
        foreach ($data as $row) {
            $this->assertTrue(in_array($row['PeopleID3'], $ids));
        }
    }

    public function testFindAllWithFiltersShouldReturnFilteredByPeopleID1(): void
    {
        $ids = [16, 23];
        $params = ['people_id1' => implode('|', $ids)];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE PeopleID1 IN (16, 23)");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        // Let's bypass the limit of 250 to verify we get all the results
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertTrue(in_array($row['PeopleID1'], $ids));
        }
    }

    public function testFindAllWithFiltersShouldReturnFilteredByPeopleID2(): void
    {
        $ids = [298, 273, 133];
        $params = ['people_id2' => implode('|', $ids)];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE PeopleID2 IN (298, 273, 133)");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        // Let's bypass the limit of 250 to verify we get all the results
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertTrue(in_array($row['PeopleID2'], $ids));
        }
    }

    public function testFindAllWithFiltersShouldReturnFilteredByROP3(): void
    {
        $ropIds = [111012, 100246];
        $params = ['rop3' => implode('|', $ropIds)];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE ROP3 IN (111012, 100246)");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertTrue(in_array($row['ROP3'], $ropIds));
        }
    }

    public function testFindAllWithFiltersShouldReturnResultsFilteredByJPScale(): void
    {
        $scales = [1, 2];
        $params = ['jpscale' => implode('|', $scales)];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE JPScalePGAC IN (1, 2)");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertTrue(in_array($row['JPScalePGAC'], $scales));
        }
    }

    public function testFindAllWithFiltersShouldThrowErrorIfIncorrectJPScaleValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $peopleGroup = new PeopleGroupGlobal(['jpscale' => '2|10']);
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFiltersShouldReturnResultsFilteredByPopulation(): void
    {
        $params = ['population' => '8000-9000'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE PopulationPGAC BETWEEN 8000 AND 9000");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertGreaterThanOrEqual(8000, $row['PopulationPGAC']);
            $this->assertLessThanOrEqual(9000, $row['PopulationPGAC']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByUneachedBoolean(): void
    {
        $params = ['unreached' => 'N'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE LeastReachedPGAC = 'N' OR LeastReachedPGAC IS NULL");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertTrue(in_array($row['LeastReachedPGAC'], ['N', null]));
        }
    }

    public function testFindAllWithFilterShouldThrowErrorIfIncorrectUnreachedValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $peopleGroup = new PeopleGroupGlobal(['unreached' => 'YES']);
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFiltersShouldFilterByIsFrontierBoolean(): void
    {
        $params = ['is_frontier' => 'Y'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE FrontierPGAC = 'Y'");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertEquals('Y', strtoupper($row['FrontierPGAC']));
        }
    }

    public function testFindAllWithFilterShouldThrowErrorIfIncorrectIsFrontierValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $peopleGroup = new PeopleGroupGlobal(['is_frontier' => 'NO']);
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFiltersShouldFilterByNumberOfCountriesRange(): void
    {
        $params = ['number_of_countries' => '4-5'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE CntPGIC BETWEEN 4 AND 5");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertGreaterThanOrEqual(4, $row['CntPGIC']);
            $this->assertLessThanOrEqual(5, $row['CntPGIC']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNumberOfUnreachedRange(): void
    {
        $params = ['number_of_unreached' => '5-6'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE CntUPG BETWEEN 5 AND 6");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertGreaterThanOrEqual(5, $row['CntUPG']);
            $this->assertLessThanOrEqual(6, $row['CntUPG']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNumberOfFrontierRange(): void
    {
        $params = ['number_of_frontier' => '5-6'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE CntFPG BETWEEN 5 AND 6");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertGreaterThanOrEqual(5, $row['CntFPG']);
            $this->assertLessThanOrEqual(6, $row['CntFPG']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByPrimaryLanguageOfTheGroup(): void
    {
        $params = ['languages' => 'sad|kqp'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE ROL3PGAC IN ('sad', 'kqp', 'SAD', 'KQP')");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertTrue(in_array(strtoupper(strtoupper($row['ROL3PGAC'])), ['SAD', 'KQP']));
        }
    }

    public function testFindAllWithFiltersShouldThrowErrorIfIncorrectLanguageCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $peopleGroup = new PeopleGroupGlobal(['languages' => 'language|kqp']);
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFiltersShouldFilterByThePrimaryReligions(): void
    {
        $params = ['primary_religions' => '7|9'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE RLG3PGAC IN (7, 9)");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertTrue(in_array($row['RLG3PGAC'], [7, 9]));
        }
    }

    public function testFindAllWithFiltersShouldThrowErrorIfIncorrectReligionCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $peopleGroup = new PeopleGroupGlobal(['primary_religions' => '15|1']);
        $peopleGroup->findAllWithFilters();
    }

    public function testFindAllWithFiltersShouldFilterByPercentChristianRange(): void
    {
        $params = ['pc_christian' => '0.001-0.003'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE PercentChristianPGAC BETWEEN 0.001 AND 0.003");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertGreaterThanOrEqual(0.001, $row['PercentChristianPGAC']);
            $this->assertLessThanOrEqual(0.003, $row['PercentChristianPGAC']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByPercentEvangelicalRange(): void
    {
        $params = ['pc_evangelical' => '0.001-0.003'];
        $query = $this->db->query("SELECT COUNT(*) as count FROM jppeoplesglobal WHERE PercentEvangelicalPGAC BETWEEN 0.001 AND 0.003");
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $count = $result['count'];
        $this->assertGreaterThan(0, $count, "Bad test. The results should be greater than 0.");
        // Let's bypass the limit of 250 to verify we get all the results
        $params['limit'] = $count + 100;
        $peopleGroup = new PeopleGroupGlobal($params);
        $peopleGroup->findAllWithFilters();
        $statement = $this->db->prepare($peopleGroup->preparedStatement);
        $statement->execute($peopleGroup->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($count, count($data));
        foreach ($data as $row) {
            $this->assertGreaterThanOrEqual(0.001, $row['PercentEvangelicalPGAC']);
            $this->assertLessThanOrEqual(0.003, $row['PercentEvangelicalPGAC']);
        }
    }

    public function testFindAllWithFiltersShouldSortByDefault(): void
    {
        $pg = new PeopleGroupGlobal(['limit' => 10]);
        $pg->findAllWithFilters();
        $statement = $this->db->prepare($pg->preparedStatement);
        $statement->execute($pg->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $sorted = $data;
        usort($sorted, fn ($a, $b) => $a['PeopleID3'] - $b['PeopleID3']);
        $this->assertEquals($sorted, $data);
    }

    public function testFindAllWithFiltersShouldBeSortedByProvidedParams(): void
    {
        $pg = new PeopleGroupGlobal(['limit' => 10, 'sort_field' => 'PopulationPGAC', 'sort_direction' => 'desc']);
        $pg->findAllWithFilters();
        $statement = $this->db->prepare($pg->preparedStatement);
        $statement->execute($pg->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $sorted = $data;
        usort($sorted, fn ($a, $b) => $b['PopulationPGAC'] - $a['PopulationPGAC']);
        $this->assertEquals($sorted, $data);
    }

    public function testFindAllWithFiltersShouldThrowErrorIfUnWhitelistedSortField(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $pg = new PeopleGroupGlobal(['limit' => 10, 'sort_field' => 'WRONG', 'sort_direction' => 'DESC']);
        $pg->findAllWithFilters();
    }

    public function testFindAllWithFiltersShouldThrowErrorIFWrongSortDirection(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $pg = new PeopleGroupGlobal(['limit' => 10, 'sort_field' => 'PopulationPGAC', 'sort_direction' => 'WRONG']);
        $pg->findAllWithFilters();
    }
}
