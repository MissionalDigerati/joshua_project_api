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
 * Generates the PDO prepared statements and variables for Profile Text.
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
class ProfileText extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.
     * Simply remove fields you do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = [
        'jpprofiletext.ProfileID', 'jpprofiletext.Summary', 'jpprofiletext.Obstacles',
        'jpprofiletext.HowReach', 'jpprofiletext.PrayForChurch', 'jpprofiletext.PrayForPG'
    ];
    /**
     * Construct the Profile Text class.
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
     * Find the Profile Text for a specific People Group in a specific Country.
     *
     * Find the Profile Text for a specific People group by suppling the People Group's id, the Joshua Project's
     * PeopleID3.  Also specify the country by supplying the country's
     * <a href="http://goo.gl/31Gf" target="_blank">ISO2 Letter code</a>. <br><br>
     * <strong>Requires $providedParams['id']:</strong> The Joshua Project PeopleID3.
     * <br><strong>Requires $providedParams['country']:</strong> The country's ISO 2 letter code.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException   If the 'id' key is not set on the $providedParams class variable.
     * @throws  \InvalidArgumentException   If the 'country' key is not set on the $providedParams class variable.
     * @author  Johnathan Pulos
     */
    public function findAllByIdAndCountry()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id', 'country'));
        $id = intval($this->providedParams['id']);
        $country = strtoupper($this->providedParams['country']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement .
            " FROM jpprofiletopeople JOIN jpprofiletext ON jpprofiletopeople.ProfileID = jpprofiletext.ProfileID " .
            "WHERE jpprofiletopeople.PeopleID3 = :id AND jpprofiletopeople.ROG3 = :country " .
            "AND jpprofiletopeople.ROL3Profile = 'eng' AND jpprofiletext.ROL3Profile = 'eng' " .
            "AND jpprofiletext.Format = 'M' ORDER BY jpprofiletopeople.EditDate DESC LIMIT 1";

        $this->preparedVariables = array('id' => $id, 'country' => $country);
    }
}
