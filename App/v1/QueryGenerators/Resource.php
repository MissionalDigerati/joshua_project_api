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

use DataObjects\SortData;

/**
 * Generates the PDO prepared statements and variables for Resources.
 *
 * A class that creates the prepared statement, and sets up the variables for a PDO prepared statement query.
 * Once you call a method like findById,  you can get the prepared statement by reading the class variable
 * $preparedStatement.  You can retrieve the prepared variables by reading the class variable $preparedVariables.
 * So here is an example using the Continents Query Generator to find a continent by id:
 * <pre><code>
 * &lt;?php
 * // Initialize the class, and pass in the id.
 * $continent = new \QueryGenerators\Continent(array('id' => 'AFR'));
 * // Call the method you want.
 * $continent->findById();
 * // Using PDO prepare the statement.
 * $statement = $db->prepare($continent->preparedStatement);
 * // Execute the query with the prepared params.
 * $statement->execute($continent->preparedVariables);
 * // Fetch the final results.
 * $data = $statement->fetchAll(PDO::FETCH_ASSOC);
 * ?&gt;
 * </code></pre>
 *
 * @author Johnathan Pulos
 * @package QueryGenerators
 */
class Resource extends QueryGenerator
{
    /**
     * The database table to pull the data from.
     *
     * @var     string
     * @access  protected
     */
    protected $tableName = 'jpresources';
    /**
     * An array of column names for this database table that we want to select in searches.
     * Simply remove fields you do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = ['ROL3', 'Category', 'WebText', 'URL'];
    /**
     * An array of fields that are allowed to be sorted by.
     *
     * @var     array
     * @access  protected
     */
    protected $sortingFieldWhitelist = ['DisplaySeq'];
    /**
     * Construct the Resource class.
     *
     * During construction,  the $getParams are checked and inserted in the $providedParams class variable.
     * Some of the methods in this class require certain keys to be set, or it will throw an error.  The comments will
     * state the required keys.
     *
     * @param   array   $getParams  The GET params to use for the query.
     * @return  void
     * @access  public
     * @author  Johnathan Pulos
     */
    public function __construct(array $getParams)
    {
        parent::__construct($getParams);
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray);
        $this->sortData = new SortData('DisplaySeq', 'DESC');
    }
    /**
     * Find Resources for a specific Language.
     *
     * Find all Resources associated with a specific language by providing it's
     * <a href='http://goo.gl/gbkgo4' target='_blank'>3 Letter ISO code</a> or Joshua Projects ROL3 code.
     * <br><br><strong>Requires $providedParams['id']:</strong> The three letter ISO code or Joshua Projects ROL3 code.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException If the 'id' key is not set on the $providedParams class variable.
     * @author  Johnathan Pulos
     */
    public function findAllByLanguageId(): void
    {
        $this->validator->providedRequiredParams($this->providedParams, ['id']);
        $id = strtolower($this->providedParams['id']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName .
            " WHERE ROL3 = :id";
        $this->addOrderStatement();
        $this->preparedVariables = ['id' => $id];
    }
}
