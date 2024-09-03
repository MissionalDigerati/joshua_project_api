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

class TotalTest extends TestCase
{
    private $db;

    public function setUp(): void
    {
        $this->db = getDatabaseInstance();
    }

    public function testAllShouldReturnAllTotals(): void
    {
        $totals = new \QueryGenerators\Total([]);
        $totals->all();
        $this->assertNotEmpty($totals->preparedStatement);
        $this->assertEmpty($totals->preparedVariables);
        $statement = $this->db->prepare($totals->preparedStatement);
        $statement->execute($totals->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertNotEmpty($data);
        $this->assertEquals(34, count($data));
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertNotEmpty($data[0]['id']);
        $this->assertArrayHasKey('Value', $data[0]);
        $this->assertNotEmpty($data[0]['Value']);
        $this->assertArrayHasKey('RoundPrecision', $data[0]);
    }

    public function testFindByIdShouldRequireId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $totals = new \QueryGenerators\Total([]);
        $totals->findById();
    }

    public function testFindByIdShouldReturnTotalById(): void
    {
        $totals = new \QueryGenerators\Total(['id' => 'CntContinents']);
        $totals->findById();
        $this->assertNotEmpty($totals->preparedStatement);
        $this->assertNotEmpty($totals->preparedVariables);
        $statement = $this->db->prepare($totals->preparedStatement);
        $statement->execute($totals->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertEquals('CntContinents', $data[0]['id']);
        $this->assertArrayHasKey('Value', $data[0]);
        $this->assertEquals(7, $data[0]['Value']);
        $this->assertArrayHasKey('RoundPrecision', $data[0]);
        $this->assertEquals(0, $data[0]['RoundPrecision']);
    }

    public function testIndexShouldNotReturnRestrictedIds(): void
    {
        $totals = new \QueryGenerators\Total([]);
        $totals->all();
        $this->assertNotEmpty($totals->preparedStatement);
        $this->assertEmpty($totals->preparedVariables);
        $statement = $this->db->prepare($totals->preparedStatement);
        $statement->execute($totals->preparedVariables);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertNotEmpty($data);
        $ids = array_column($data, 'id');
        $this->assertNotContains('CntPeopCtryLess10K', $ids);
        $this->assertNotContains('CntPeopCtryLess10KLR', $ids);
        $this->assertNotContains('CntPeopCtryLRNo5PctAdherents', $ids);
        $this->assertNotContains('CntPeopCtryNoPopl', $ids);
        $this->assertNotContains('CntPeopCtryNoPoplLR', $ids);
        $this->assertNotContains('CntTotalSubgroups', $ids);
        $this->assertNotContains('PoplCtryUN', $ids);
    }

    public function testShowShouldNotReturnRestrictedIds(): void
    {
        $restricted = [
            'CntPeopCtryLess10K', 'CntPeopCtryLess10KLR', 'CntPeopCtryLRNo5PctAdherents',
            'CntPeopCtryNoPopl', 'CntPeopCtryNoPoplLR', 'CntTotalSubgroups', 'PoplCtryUN'
        ];
        foreach ($restricted as $id) {
            $totals = new \QueryGenerators\Total(['id' => $id]);
            $totals->findById();
            $this->assertNotEmpty($totals->preparedStatement);
            $this->assertNotEmpty($totals->preparedVariables);
            $statement = $this->db->prepare($totals->preparedStatement);
            $statement->execute($totals->preparedVariables);
            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $this->assertEmpty($data);
        }
    }
}
