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

namespace QueryGenerators;

/**
 * Generates the PDO prepared statements and variables for Totals.
 *
 * A class that creates the prepared statement, and sets up the variables for a PDO prepared statement query.
 * Once you call a method like findById,  you can get the prepared statement by reading the class variable
 * $preparedStatement.  You can retrieve the prepared variables by reading the class variable $preparedVariables.
 * So here is an example using the Continents Query Generator to find a continent by id:
 * <pre><code>
 * &lt;?php
 * // Initialize the class, and pass in the id.
 * $totals = new \QueryGenerators\Total([]);
 * // Call the method you want.
 * $totals->findById();
 * // Using PDO prepare the statement.
 * $statement = $db->prepare($totals->preparedStatement);
 * // Execute the query with the prepared params.
 * $statement->execute($totals->preparedVariables);
 * // Fetch the final results.
 * $data = $statement->fetchAll(PDO::FETCH_ASSOC);
 * ?&gt;
 * </code></pre>
 *
 * @author Johnathan Pulos
 * @package QueryGenerators
 */
class Total extends QueryGenerator
{
    /**
     * The database table to pull the data from.
     *
     * @var     string
     * @access  protected
     */
    protected $tableName = 'jptotals';
    /**
     * A string that will hold the default MySQL ORDER BY for the Select statement.
     *
     * @var     string
     * @access  protected
     */
    protected $defaultOrderByStatement = 'ORDER BY ID ASC';
    /**
     * An array of column names for this database table that we want to select in searches.
     * Simply remove fields you do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = ['ID AS id', 'IDValue AS Value', 'RoundPrecision'];
    /**
     * A list of ids that we do not want to return in the results.
     *
     * @var array
     */
    protected $restrictedIds = [
        'CntPeopCtryLess10K', 'CntPeopCtryLess10KLR', 'CntPeopCtryLRNo5PctAdherents', 'CntPeopCtryNoPopl',
        'CntPeopCtryNoPoplLR', 'CntTotalSubgroups', 'PoplCtryUN'
    ];
    /**
     * Construct the Totals class.
     *
     * During construction,  the $getParams are checked and inserted in the $providedParams class variable.
     * Some of the methods in this class require certain keys to be set, or it will throw an error.  The comments will
     * state the required keys.
     *
     * @param   array   $getParams  The GET params to use for the query.
     * @return  void
     * @access  public
     */
    public function __construct(array $getParams)
    {
        parent::__construct($getParams);
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray);
    }

    /**
     * Find all the totals in the table
     *
     * @return void
     * @access  public
     */
    public function all(): void
    {
        $where = $this->createWhereForRestrictedIds();
        $this->preparedStatement = "SELECT {$this->selectFieldsStatement} FROM {$this->tableName} " .
                                   "WHERE {$where} {$this->defaultOrderByStatement}";
        $this->preparedVariables = [];
    }

    /**
     * Find the total for the specified ID
     *
     * @return void
     * @access  public
     */
    public function findById(): void
    {
        $where = "WHERE ID = :id AND {$this->createWhereForRestrictedIds()}";
        $this->validator->providedRequiredParams($this->providedParams, ['id']);
        $id = $this->providedParams['id'];
        $this->preparedStatement = "SELECT {$this->selectFieldsStatement} FROM {$this->tableName} {$where}";
        $this->preparedVariables = ['id' => $id];
    }

    /**
     * Create the where statement for hiding the restricted ids.
     *
     * @return string
     */
    protected function createWhereForRestrictedIds(): string
    {
        if (!empty($this->restrictedIds)) {
            $quotedIds = array_map(function ($id) {
                return "'{$id}'";
            }, $this->restrictedIds);
            $ids = implode(',', $quotedIds);
            $where = "ID NOT IN ({$ids})";
        } else {
            $where = '';
        }

        return $where;
    }
}
