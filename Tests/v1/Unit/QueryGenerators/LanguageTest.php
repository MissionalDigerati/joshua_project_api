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
 * Test the Query Generator for the Language Data
 *
 * @author Johnathan Pulos
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
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
        $data = array('language' => 'HORSE#%', 'test' => 'CA%$');
        $expected = array('language' => 'HORSE', 'test' => 'CA');
        $reflectionOfCountry = new \ReflectionClass('\QueryGenerators\Language');
        $providedParams = $reflectionOfCountry->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new \QueryGenerators\Language($data));
        $this->assertEquals($expected, $result);
    }
    /**
     * findById() should return the correct language
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindByIdShouldReturnCorrectLanguage()
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
    /**
     * findAllWithFilters() should return all the Languages if no filters applied
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldReturnAllLanguagesWithoutFilters()
    {
        $expectedCount = 100;
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
    /**
     * findAllWithFilters() should limit the total results
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindAllWithFiltersShouldLimitTheResult()
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
    /**
     * findAllWithFilters() should limit based on passed ids
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByASetOfIDS()
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
    /**
     * findAllWithFilters() should limit based on has_new_testament
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByNotHavingANewTestament()
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
    /**
     * findAllWithFilters() should limit based on has_portions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByNotHavingPortions()
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
    /**
     * findAllWithFilters() should limit based on has_completed_bible
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByNotHavingCompletedBible()
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
    /**
     * findAllWithFilters() should limit based on whether they have questionable translation need
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByNotHavingQuestionableTranslationNeed()
    {
        $expected = array('needs_translation_questionable'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['TranslationNeedQuestionable']);
        }
    }
    /**
     * findAllWithFilters() should limit based on whether they have audio resources
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByNotHavingAudioResources()
    {
        $expected = array('has_audio'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['AudioRecordings']);
        }
    }
    /**
     * findAllWithFilters() should limit based on whether they have 4 laws
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByNotHavingFourLaws()
    {
        $expected = array('has_four_laws'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['FourLaws']);
        }
    }
    /**
     * findAllWithFilters() should limit based on whether they have Jesus Film
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByNotHavingJesusFilm()
    {
        $expected = array('has_jesus_film'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['JF']);
        }
    }
    /**
     * findAllWithFilters() should limit based on whether they have God's Story
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByNotHavingGodsStory()
    {
        $expected = array('has_gods_story'   =>  'N');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertNull($lang['GodsStory']);
        }
    }
    /**
     * findAllWithFilters() should limit based on countries
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByCountry()
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
    /**
     * findAllWithFilters() should limit based on world speakers
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testFindWithFiltersShouldLimitByWorldSpeakers()
    {
        $expected = array('world_speakers'   =>  '1');
        $language = new \QueryGenerators\Language($expected);
        $language->findAllWithFilters();
        $statement = $this->db->prepare($language->preparedStatement);
        $statement->execute($language->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $lang) {
            $this->assertEquals(1, intval($lang['WorldSpeakers']));
        }
    }
}
