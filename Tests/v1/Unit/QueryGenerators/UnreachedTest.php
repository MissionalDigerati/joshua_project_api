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

use PHPToolbox\PDODatabase\PDODatabaseConnect;
use PHPUnit\Framework\TestCase;

/**
 * Test the Query Generator for the People Group Data
 *
 * @author Johnathan Pulos
 */
class UnreachedTest extends TestCase
{
    /**
     * The PDO database connection object
     *
     * @var PDODatabaseConnect
     */
    private $db;
    /**
     * Setup the test methods
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp(): void
    {
        $this->db = getDatabaseInstance();
    }
    /**
     * Test that we get back the right query for unreached of the day
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testDailyUnreachedRequestsShouldReturnCorrectResults(): void
    {
        $expected = array('month' => 1, 'day' => 11);
        $unreached = new \QueryGenerators\Unreached($expected);
        $unreached->daily();
        $statement = $this->db->prepare($unreached->preparedStatement);
        $statement->execute($unreached->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expected['month'], $data[0]['LRofTheDayMonth']);
        $this->assertEquals($expected['day'], $data[0]['LRofTheDayDay']);
    }
    /**
     * We should throw an InvalidArgumentException if I do not send the month to dailyUnreached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testDailyUnreachedRequestsShouldThrowErrorIfMissingMonth(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $getVars = array('day' => 11);
        $unreached = new \QueryGenerators\Unreached($getVars);
        $unreached->daily();
    }
    /**
     * We should throw an InvalidArgumentException if I do not send the day to dailyUnreached
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testDailyUnreachedRequestsShouldThrowErrorIfMissingDay(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $getVars = array('month' => 11);
        $unreached = new \QueryGenerators\Unreached($getVars);
        $unreached->daily();
    }
    /**
     * We should throw an InvalidArgumentException if I do not send a month in range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testDailyUnreachedRequestsShouldThrowErrorIfMonthIsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $getVars = array('month' => 13, 'day' => 1);
        $unreached = new \QueryGenerators\Unreached($getVars);
        $unreached->daily();
    }
    /**
     * We should throw an InvalidArgumentException if I do not send a day in range
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     *
     * @expectedException InvalidArgumentException
     */
    public function testDailyUnreachedRequestsShouldThrowErrorIfDayIsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $getVars = array('month' => 12, 'day' => 32);
        $unreached = new \QueryGenerators\Unreached($getVars);
        $unreached->daily();
    }
}
