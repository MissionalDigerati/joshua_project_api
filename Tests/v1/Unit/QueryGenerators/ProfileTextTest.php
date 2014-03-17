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
 * Test the Query Generator for the people group ProfileText Data
 *
 * @author Johnathan Pulos
 */
class ProfileTextTest extends \PHPUnit_Framework_TestCase
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
        $data = array('country' => 'AZXTE#%', 'state' => 'MA%$');
        $expected = array('country' => 'AZXTE', 'state' => 'MA');
        $reflectionOfProfileText = new \ReflectionClass('\QueryGenerators\ProfileText');
        $providedParams = $reflectionOfProfileText->getProperty('providedParams');
        $providedParams->setAccessible(true);
        $result = $providedParams->getValue(new \QueryGenerators\ProfileText($data));
        $this->assertEquals($expected, $result);
    }
    /**
     * We should throw an InvalidArgumentException if I do not send the ID to find
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindAllByIdAndCountryShouldThrowErrorIfMissingIDOnFind()
    {
        $getVars = array('country' => 'CB');
        $profileText = new \QueryGenerators\ProfileText($getVars);
        $profileText->findAllByIdAndCountry();
    }
    /**
     * We should throw an InvalidArgumentException if I do not send the Country to find
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     * 
     * @expectedException InvalidArgumentException
     */
    public function testFindAllByIdAndCountryShouldThrowErrorIfMissingCountryOnFind()
    {
        $getVars = array('id' => '12662');
        $profileText = new \QueryGenerators\ProfileText($getVars);
        $profileText->findAllByIdAndCountry();
    }
    /**
     * We should receive the correct ProfileTexts when we supply the correct id and country
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testFindAllByIdAndCountryShouldReturnTheCorrectProfileText()
    {
        $expectedProfileIDs = array('1859', '3550');
        $getVars = array('id' => '12662', 'country' => 'CB');
        $profileText = new \QueryGenerators\ProfileText($getVars);
        $profileText->findAllByIdAndCountry();
        $statement = $this->db->prepare($profileText->preparedStatement);
        $statement->execute($profileText->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data));
        foreach ($data as $profileTextResult) {
            $this->assertTrue(in_array($profileTextResult['ProfileID'], $expectedProfileIDs));
        }
    }
}
