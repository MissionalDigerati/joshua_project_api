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

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use QueryGenerators\Language;

/**
 * Test the Query Generator for the Language Data
 *
 * @author Johnathan Pulos
 */
class LanguageTest extends TestCase
{
    private Connection $db;

    public function setUp(): void
    {
        $this->db = getDatabaseInstance();
    }

    public function testShouldSanitizeProvidedDataOnInitializing(): void
    {
        $data = ['language' => 'HORSE#%', 'test' => 'CA%$'];
        $expected = ['language' => 'HORSE', 'test' => 'CA'];
        $reflectionOfCountry = new \ReflectionClass('\QueryGenerators\Language');
        $providedParams = $reflectionOfCountry->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new Language($data));
        $this->assertEquals($expected, $result);
    }

    public function testFindByIdShouldReturnCorrectLanguage(): void
    {
        $expected = ['id'  =>  'amx'];
        $expectedLanguage = 'anmatyerr';
        $expectedHubCountry = 'australia';
        $language = new Language($expected);
        $language->findById();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertEquals($expected['id'], strtolower($data[0]['ROL3']));
        $this->assertEquals($expectedLanguage, strtolower($data[0]['Language']));
        $this->assertEquals($expectedHubCountry, strtolower($data[0]['HubCountry']));
    }

    public function testFindAllWithFiltersShouldReturnAllLanguagesWithoutFilters(): void
    {
        $expectedCount = 250;
        $expectedFirstLanguage = "a'ou";
        $language = new Language([]);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        $this->assertEquals($expectedCount, count($data));
        $this->assertEquals($expectedFirstLanguage, strtolower($data[0]['Language']));
    }

    public function testFindAllWithFiltersShouldFilterTheResult(): void
    {
        $expected = ['limit'   =>  5];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        $this->assertEquals($expected['limit'], count($data));
    }

    public function testFindAllWithFiltersShouldFilterByASetOfIds(): void
    {
        $expected = ['ids'   =>  'ace|boj|smf'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertTrue(in_array(strtolower($lang['ROL3']), explode("|", $expected['ids'])));
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingANewTestament(): void
    {
        $expected = ['has_new_testament'   =>  'N'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['NTYear']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingPortions(): void
    {
        $expected = ['has_portions'   =>  'N'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['PortionsYear']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingCompletedBible(): void
    {
        $expected = ['has_completed_bible'   =>  'N'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['BibleYear']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingQuestionableTranslationNeed(): void
    {
        $expected = ['needs_translation_questionable'   =>  'N'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertContains($lang['TranslationNeedQuestionable'], ['N', '', null]);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingAudioResources(): void
    {
        $expected = ['has_audio'   =>  'N'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals('N', $lang['HasAudioRecordings']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByNotHavingJesusFilm(): void
    {
        $expected = ['has_jesus_film'   =>  'N'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals('N', $lang['HasJesusFilm']);
        }
    }

    public function testFindAllWithFiltersShouldFilterByCountry(): void
    {
        $expected = ['countries'   =>  'af|ni'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        $countries = explode("|", $expected['countries']);
        foreach ($data as $lang) {
            $this->assertTrue(in_array(strtolower($lang['ROG3']), $countries));
        }
    }

    public function testFindAllWithFiltersShouldFilterByPrimaryReligion(): void
    {
        $expected = ['primary_religions'   =>  '6'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals('islam', strtolower($lang['PrimaryReligion']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByJPScale(): void
    {
        $expected = ['jpscale'   =>  '3'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals(3, floatval($lang['JPScale']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByLeastReached(): void
    {
        $expected = ['least_reached'   =>  'y'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals('y', strtolower($lang['LeastReached']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByAdherent(): void
    {
        $expected = ['pc_adherent'   =>  '60'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals(60, floatval($lang['PercentAdherents']));
        }
    }

    public function testFindAllWithFiltersShouldFilterByEvangelical(): void
    {
        $expected = ['pc_evangelical'   =>  '10'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals(10, floatval($lang['PercentEvangelical']));
        }
    }

    public function testFindAllWithFiltersShouldSortByLanguageAscByDefault(): void
    {
        $expected = ['limit'   =>   5];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $sorted = $data;
        usort($sorted, fn ($a, $b) => strcmp($a['Language'], $b['Language']));
        $this->assertEquals($sorted, $data);
    }

    public function testFindAllWithFiltersShouldSortByCustomSort(): void
    {
        $expected = ['limit'   =>   5, 'sort_field' => 'ROL3', 'sort_direction' => 'DESC'];
        $language = new Language($expected);
        $language->findAllWithFilters();
        $data = $this->db->fetchAllAssociative(
            $language->preparedStatement,
            $language->preparedVariables,
            $language->preparedVariableTypes
        );
        $sorted = $data;
        usort($sorted, fn ($a, $b) => strcmp($b['ROL3'], $a['ROL3']));
        $this->assertEquals($sorted, $data);
    }

    public function testFindAllWithFiltersWillThrowErrorIfSortFieldIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expected = ['limit'   =>   5, 'sort_field' => 'INVALID', 'sort_direction' => 'DESC'];
        $language = new Language($expected);
        $language->findAllWithFilters();
    }

    public function testFindAllWithFiltersWillThrowErrorIfSortDirectionIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $expected = ['limit'   =>   5, 'sort_field' => 'ROL3', 'sort_direction' => 'INVALID'];
        $language = new Language($expected);
        $language->findAllWithFilters();
    }
}
