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

class UnreachedTest extends TestCase
{
    private $db;

    public function setUp(): void
    {
        $this->db = getDatabaseInstance();
    }

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

    public function testDailyUnreachedRequestsShouldThrowErrorIfMissingMonth(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $getVars = array('day' => 11);
        $unreached = new \QueryGenerators\Unreached($getVars);
        $unreached->daily();
    }

    public function testDailyUnreachedRequestsShouldThrowErrorIfMissingDay(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $getVars = array('month' => 11);
        $unreached = new \QueryGenerators\Unreached($getVars);
        $unreached->daily();
    }

    public function testDailyUnreachedRequestsShouldThrowErrorIfMonthIsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $getVars = array('month' => 13, 'day' => 1);
        $unreached = new \QueryGenerators\Unreached($getVars);
        $unreached->daily();
    }

    public function testDailyUnreachedRequestsShouldThrowErrorIfDayIsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $getVars = array('month' => 12, 'day' => 32);
        $unreached = new \QueryGenerators\Unreached($getVars);
        $unreached->daily();
    }

    public function testDailyUnreachedRequestsShouldContainPeopleID3ROG3Field(): void
    {
        $expected = array('month' => 1, 'day' => 11);
        $unreached = new \QueryGenerators\Unreached($expected);
        $unreached->daily();
        $statement = $this->db->prepare($unreached->preparedStatement);
        $statement->execute($unreached->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertArrayHasKey('PeopleID3', $data[0]);
        $this->assertArrayHasKey('ROG3', $data[0]);
        $expected = $data[0]['PeopleID3'] . $data[0]['ROG3'];
        $this->assertArrayHasKey('PeopleID3ROG3', $data[0]);
        $this->assertEquals($expected, $data[0]['PeopleID3ROG3']);
    }
}
