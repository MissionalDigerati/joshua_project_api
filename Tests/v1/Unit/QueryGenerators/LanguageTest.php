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

/**
 * Test the Query Generator for the Language Data
 *
 * @author Johnathan Pulos
 */
class LanguageTest extends TestCase
{

    private $db;

    public function setUp(): void
    {
        $this->db = getDatabaseInstance();
    }

    public function testShouldSanitizeProvidedDataOnInitializing(): void
    {
        $data = array('language' => 'HORSE#%', 'test' => 'CA%$');
        $expected = array('language' => 'HORSE', 'test' => 'CA');
        $reflectionOfCountry = new \ReflectionClass('\QueryGenerators\Language');
        $providedParams = $reflectionOfCountry->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new \QueryGenerators\Language($data));
        $this->assertEquals($expected, $result);
    }

    public function testFindByIdShouldReturnCorrectLanguage(): void
    {
        $expected = array('id'  =>  'amx');
        $expectedLanguage = 'anmatyerre';
        $expectedHubCountry = 'australia';
        $language = new \QueryGenerators\Language($expected);
        $language->findById();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['id'], strtolower($data[0]['ROL3']));
        $this->assertEquals($expectedLanguage, strtolower($data[0]['Language']));
        $this->assertEquals($expectedHubCountry, strtolower($data[0]['HubCountry']));
    }

    public function testFindAllWithFiltersShouldReturnAllLanguagesWithoutFilters(): void
    {
        $expectedCount = 250;
        $expectedFirstLanguage = "a'ou";
        $language = new \QueryGenerators\Language(array());
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        $this->assertEquals($expectedCount, count($data));
        $this->assertEquals($expectedFirstLanguage, strtolower($data[0]['Language']));
    }

    public function testFindAllWithFiltersShouldFilterTheResult(): void
    {
        $expected = array('limit'   =>  5);
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        $this->assertEquals($expected['limit'], count($data));
    }

    public function testFindAllWithFiltersShouldFilterByASetOfIds(): void
    {
        $expected = array('ids'   =>  'ace|boj|smf');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertTrue(in_array(strtolower($lang['ROL3']), explode("|", $expected['ids'])));
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingANewTestament(): void
    {
        $expected = array('has_new_testament'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['NTYear']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingPortions(): void
    {
        $expected = array('has_portions'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['PortionsYear']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingCompletedBible(): void
    {
        $expected = array('has_completed_bible'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['BibleYear']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingQuestionableTranslationNeed(): void
    {
        $expected = array('needs_translation_questionable'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertContains($lang['TranslationNeedQuestionable'], ['N', '', null]);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingAudioResources(): void
    {
        $expected = array('has_audio'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals('N', $lang['AudioRecordings']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingJesusFilm(): void
    {
        $expected = array('has_jesus_film'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals('N', $lang['JF']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByCountry(): void
    {
        $expected = array('countries'   =>  'af|ni');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        $countries = explode("|", $expected['countries']);
        foreach ($data as $lang) {
            $this->assertTrue(in_array(strtolower($lang['ROG3']), $countries));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPrimaryReligion(): void
    {
        $expected = array('primary_religions'   =>  '6');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals('islam', strtolower($lang['PrimaryReligion']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByJPScale(): void
    {
        $expected = array('jpscale'   =>  '3');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals(3, floatval($lang['JPScale']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByLeastReached(): void
    {
        $expected = array('least_reached'   =>  'y');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals('y', strtolower($lang['LeastReached']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByAdherent(): void
    {
        $expected = array('pc_adherent'   =>  '60');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals(60, floatval($lang['PercentAdherents']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByEvangelical(): void
    {
        $expected = array('pc_evangelical'   =>  '10');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals(10, floatval($lang['PercentEvangelical']));
        }
    }

}
