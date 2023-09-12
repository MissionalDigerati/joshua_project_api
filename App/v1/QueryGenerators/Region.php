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
 * Generates the PDO prepared statements and variables for Regions.
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
class Region extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.
     * Simply remove fields you do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = array(
        'RegionCode', 'RegionName', 'NbrCountries', 'NbrPGIC', 'NbrLR', 'SumRegion', 'PercentLR', 'SumRegionLR',
        'PercentPoplLR'
    );
    /**
     * The database table to pull the data from.
     *
     * @var     string
     * @access  protected
     */
    protected $tableName = "jpregionsum";
    /**
     * A string that will hold the default MySQL ORDER BY for the Select statement.
     *
     * @var     string
     * @access  protected
     */
    protected $defaultOrderByStatement = "ORDER BY RegionName ASC";
    /**
     * An array of table columns (key) and their alias (value).
     *
     * @var     array
     * @access  protected
     **/
    protected $aliasFields = array();
    /**
     * Construct the Region class.
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
    public function __construct($getParams)
    {
        parent::__construct($getParams);
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray);
    }
    /**
     * Find a Region by it's id.
     *
     * Find a specific region by it's numeric id.  You can see the ids below:
     *
     *  <ul>
     *      <li><strong>1 </strong> South Pacific</li>
     *      <li><strong>2 </strong> Southeast Asia</li>
     *      <li><strong>3 </strong> Northeast Asia</li>
     *      <li><strong>4 </strong> South Asia</li>
     *      <li><strong>5 </strong> Central Asia</li>
     *      <li><strong>6 </strong> Middle East and North Africa</li>
     *      <li><strong>7 </strong> East and Southern Africa</li>
     *      <li><strong>8 </strong> West and Central Africa</li>
     *      <li><strong>9 </strong> Eastern Europe and Eurasia</li>
     *      <li><strong>10</strong> Western Europe</li>
     *      <li><strong>11</strong> Central and South America</li>
     *      <li><strong>12</strong> North America and Caribbean</li>
     *  </ul>
     *
     * <br><br><strong>Requires $providedParams['id']:</strong> The numeric id for the Region.
     *
     * @return  void
     * @throws  \InvalidArgumentException   If the 'id' key is not set on the $providedParams class variable.
     * @access  public
     * @author  Johnathan Pulos
     **/
    public function findById()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id'));
        $id = intval(strip_tags($this->providedParams['id']));
        $this->validator->integerInRange($id, 1, 12);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName .
            " WHERE RegionCode = :id LIMIT 1";
        $this->preparedVariables = array('id' => $id);
    }
}
