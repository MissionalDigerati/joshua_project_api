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
namespace QueryGenerators;

/**
 * Generates the PDO prepared statements and variables for Continents.
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
 */
class Continent extends QueryGenerator
{
    /**
     * An array of table columns (key) and their alias (value).
     *
     * @var array
     * @access protected
     **/
    protected $aliasFields = array();
    /**
     * A string that will hold the default ORDER BY for the Select statement.
     *
     * @var string
     * @access protected
     */
    protected $defaultOrderByStatement = "ORDER BY Continent ASC";
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you do not want to expose.
     *
     * @var array
     * @access protected
     */
    protected $fieldsToSelectArray = array(
        'ROG2', 'Continent', 'NbrCountries', 'NbrPGIC', 'NbrLR', 'SumContinent', 'PercentLR', 'SumContinentLR', 'PercentPoplLR', 'PercentUrbanized'
    );
    /**
     * The Database table to pull the data from.
     *
     * @var string
     * @access protected
     */
    protected $tableName = "jpcontinentsum";
    /**
     * Construct the Continent class.
     *
     * During construction,  the $getParams are checked and inserted in the $providedParams class variable.  Some of the methods in this class require
     * certain keys to be set, or it will throw an error.  The comments will state the required keys.
     *
     * @param   array   $getParams  The GET params to use for the query.
     * @return  void
     * @access  public
     * @author  Johnathan Pulos
     */
    public function __construct($getParams)
    {
        parent::__construct($getParams);
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray);
    }
    /**
     * Find a continent by an id.
     *
     * Finds a continent using a three letter ISO code.  The codes are:
     * <ul>
     *  <li>AFR - Africa</li>
     *  <li>ASI - Asia</li>
     *  <li>AUS - Australia</li>
     *  <li>EUR - Europe</li>
     *  <li>NAR - North America</li>
     *  <li>SOP - Oceania</li>
     *  <li>LAM - South America</li>
     * </ul>
     * <strong>Requires $providedParams['id']:</strong> The three letter ISO code.
     *
     * @return void
     * @access public
     * @throws \InvalidArgumentException If the 'id' key is not set on the $providedParams class variable.
     * @author Johnathan Pulos
     **/
    public function findById()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id'));
        $id = strtolower(strip_tags($this->providedParams['id']));
        $this->validator->stringLength($id, 3);
        if (!in_array($id, array('afr', 'asi', 'aus', 'eur', 'nar', 'sop', 'lam'))) {
            throw new \InvalidArgumentException("The id you provided is incorrect.  It must be 'afr', 'asi', 'aus', 'eur', 'nar', 'sop', or 'lam'.");
        }
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName . " WHERE ROG2 = :id LIMIT 1";
        $this->preparedVariables = array('id' => $id);
    }
}
