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

use SebastianBergmann\Type\VoidType;

/**
 * Generates the PDO prepared statements and variables for People Groups globally.
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
class PeopleGroupGlobal extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you
     * do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = [
        'PeopleID3', 'PeopName', 'PeopleID1', 'PeopleID2', 'AffinityBloc', 'PeopleCluster', 'ROP3',
        'ROP25', 'ROP25Name', 'JPScalePGAC', 'PopulationPGAC', 'LeastReachedPGAC', 'FrontierPGAC',
        'CntPGIC', 'CntUPG', 'CntFPG', 'ROG3Largest', 'CtryLargest', 'ROL3PGAC', 'PrimaryLanguagePGAC',
        'RLG3PGAC', 'PrimaryReligionPGAC', 'PercentChristianPGAC', 'PercentEvangelicalPGAC'
    ];
    /**
     * The database table to pull the data from.
     *
     * @var     string
     * @access  protected
     */
    protected $tableName = "jppeoplesglobal";
    /**
     * A string that will hold the default MySQL ORDER BY for the Select statement.
     *
     * @var     string
     * @access  protected
     */
    protected $defaultOrderByStatement = "ORDER BY PeopleID3 ASC";
    /**
     * An array of table columns (key) and their alias (value).
     *
     * @var     array
     * @access  protected
     **/
    protected $aliasFields = [];
        /**
     * Construct the People Group Global class.
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
        $scaleTextStatement = $this->getScaleTextStatement('JPScalePGAC');
        $scaleImageStatement = $this->getScaleImageURLStatement('JPScalePGAC');
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray);
        $this->selectFieldsStatement .= ", $scaleTextStatement as JPScaleText";
        $this->selectFieldsStatement .= ", $scaleImageStatement as JPScaleImageURL";
    }

    /**
     * Find a specific people group by the id. (PeopleID3)
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException   If the 'id' key is not set on the $providedParams class variable.
     * @author  Johnathan Pulos
     */
    public function findById(): void
    {
        $this->validator->providedRequiredParams($this->providedParams, ['id']);
        $id = intval($this->providedParams['id']);
        $this->preparedStatement = "SELECT $this->selectFieldsStatement FROM $this->tableName WHERE PeopleID3 = :id";
        $this->preparedVariables = ['id' => $id];
    }

    /**
     * Find all the people groups globally with provided filters. To see the filters
     * check out the API documentation.
     *
     * @return void
     * @access  public
     * @throws  \InvalidArgumentException   When you set a filter, but fail to provide a valid parameter
     */
    public function findAllWithFilters(): void
    {
        $where = "";
        $appendAndOnWhere = false;
        $this->preparedStatement = "SELECT $this->selectFieldsStatement FROM $this->tableName";
        if ($this->paramExists('people_id1')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['people_id1'], 'PeopleID1');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('people_id2')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['people_id2'], 'PeopleID2');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('people_id3')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['people_id3'], 'PeopleID3');
            $appendAndOnWhere = true;
        }
        if ($where != "") {
            $this->preparedStatement .= " WHERE $where";
        }
        $this->preparedStatement .= " $this->defaultOrderByStatement ";
        $this->addLimitFilter();
    }
}
