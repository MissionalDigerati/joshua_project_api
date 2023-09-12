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
 * The parent class for all Query Generators.  This class holds common functionality between all Query Generators.
 *
 * @package default
 * @author Johnathan Pulos
 */
/**
 * The parent class for all Query Generators.
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
class QueryGenerator
{
    /**
     * The prepared statement generated by the class to be used with PDO.
     *
     * @var     string
     * @access  public
     */
    public $preparedStatement = "";
    /**
     * The prepared variables for the PDO prepared statement.
     *
     * @var     array
     * @access  public
     */
    public $preparedVariables = array();
    /**
     * The Sanitizer class for sanitizing incoming GET data.
     *
     * @var     \Utilities\Sanitizer
     * @access  protected
     */
    protected $sanitizer;
    /**
     * The Validator class for validating the incoming GET data.
     *
     * @var     \Utilities\Validator
     * @access  protected
     */
    protected $validator;
    /**
     * The provided parameters ($getParams) passed into each child's __construct() method.
     *
     * @var     array
     * @access  protected
     */
    protected $providedParams = array();
    /**
     * An array of column names for this database table that we want to select in searches.
     * Simply remove fields you do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = array();
    /**
     * A string that will hold the fields for the Select statement.
     *
     * @var     string
     * @access  protected
     */
    protected $selectFieldsStatement = '';
    /**
     * The database table to pull the data from.
     *
     * @var     string
     * @access  protected
     */
    protected $tableName = '';
    /**
     * A string that will hold the default MySQL ORDER BY for the Select statement.
     *
     * @var     string
     * @access  protected
     */
    protected $defaultOrderByStatement = '';
    /**
     * The MySQL CASE statement for generating the JPScaleText.
     *
     * @var     string
     * @access  protected
     */
    protected $JPScaleTextSelectStatement = "";
    /**
     * The MySQL CONCAT statement for generating the JPScaleImageURL.
     *
     * @var     string
     * @access  protected
     */
    protected $JPScaleImageURLSelectStatement =
        "CONCAT('https://joshuaproject.net/images/scale', ROUND(JPScale), '.jpg')";
    /**
     * An array of table columns (key) and their alias (value).
     *
     * @var     array
     * @access  protected
     **/
    protected $aliasFields = array();
    /**
     * Construct the QueryGenerator class.
     *
     * During construction,  the $getParams are checked and inserted in the $providedParams class variable.
     * Some of the methods in the child classes require certain keys to be set, or it will throw an error.
     * The comments will state the required keys.
     *
     * @param   array   $getParams  The GET params to use for the query.
     * @return  void
     * @access  public
     * @author  Johnathan Pulos
     */
    public function __construct($getParams)
    {
        $this->JPScaleTextSelectStatement = "CASE  WHEN JPScale = 1 THEN 'Unreached' WHEN JPScale = 2 THEN" .
        " 'Minimally Reached' WHEN JPScale = 3 THEN 'Superficially Reached' WHEN JPScale = 4 THEN" .
        " 'Partially Reached' ELSE 'Significantly Reached' END";
        $this->validator = new \Utilities\Validator();
        $this->sanitizer = new \Utilities\Sanitizer();
        $this->providedParams = $this->sanitizer->cleanArrayValues($getParams);
    }
    /**
     * Check if a key exists in the $providedParams class variable.
     *
     * A shorter method for checking if an array key exists in the $providedParams class variable.
     *
     * @param   string  $paramName  The key your looking for in the $providedParams class variable.
     * @return  boolean
     * @access  protected
     * @author  Johnathan Pulos
     */
    protected function paramExists($paramName)
    {
        return array_key_exists($paramName, $this->providedParams);
    }
    /**
     * Add a MySQL LIMIT based on the $providedParams class variable.
     *
     * Checks the given $providedParams class variable for keys <strong>limit</strong> and <strong>page</strong>.
     * If they exist, then this method will generate the appropriate MySQL syntax for the LIMIT attribute.
     * It appends the LIMIT string to the $preparedStatement class variable.
     *
     * @return void
     * @access protected
     * @author Johnathan Pulos
     */
    protected function addLimitFilter()
    {
        if (($this->paramExists('limit')) && intval($this->providedParams['limit']) > 0) {
            $this->preparedVariables['limit'] = intval($this->providedParams['limit']);
        } else {
            $this->preparedVariables['limit'] = 250;
        }
        if (($this->paramExists('page')) && intval($this->providedParams['page']) > 0) {
            // since page starts with 1, but the database starts with 0, we need to minus 1 from page.
            $page = intval($this->providedParams['page']) - 1;
            $starting = $page * $this->preparedVariables['limit'];
            $this->preparedVariables['starting'] = $starting;
        } else {
            $this->preparedVariables['starting'] = 0;
        }
        // print_r($this->preparedVariables);
        // exit;
        $this->preparedStatement .= "LIMIT :starting, :limit";
    }
    /**
     * Generates an IN () statement from a piped string.
     *
     * Generates the MySQL IN() syntax from a supplied a pipe (bar) seperated string.  So a $str of 17|23|12 will
     * return $columnName IN (17, 23, 12).
     *
     * @param   string  $str        The piped/barred string.
     * @param   string  $columnName The MySQL column name that you want to search.
     * @return  string  The MySQL statement.
     * @access  protected
     * @author  Johnathan Pulos
     */
    protected function generateInStatementFromPipedString($str, $columnName)
    {
        $preparedInVars = array();
        $i = 0;
        $stringParts = explode("|", $str);
        foreach ($stringParts as $element) {
            $preparedParamName = str_replace(' ', '', strtolower($columnName)) . '_' . $i;
            array_push($preparedInVars, ':' . $preparedParamName);
            $this->preparedVariables[$preparedParamName] = $element;
            $i = $i+1;
        }
        return $columnName . " IN (" . join(", ", $preparedInVars) . ")";
    }
    /**
     * Generates a BETWEEN statement using a dash separated string.
     *
     * Generates the MySQL BETWEEN statement from a dash seperated string.  If $str is a single integer, it will
     * generate an EQUALITY MySQL statement.  If you supply 2 integers seperated by a dash,  it will generate a MySQL
     * BETWEEN statement.  For example, 10-20, will return $columnName BETWEEN 10 AND 20.
     *
     * @param   string  $str        Either a single integer or a dash separated string (min-max).
     * @param   string  $columnName The name of the MySQL table column to search.
     * @param   string  $suffix     A suffix to be appended to the prepared variable name. (No Spaces Allowed)
     * @return  string  The MySQL statement.
     * @throws  \InvalidArgumentException   If $str has more than 2 integers.
     * @throws  \InvalidArgumentException   If $str first integer is greater than the second integer.
     * @access  protected
     * @author  Johnathan Pulos
     */
    protected function generateBetweenStatementFromDashSeperatedString($str, $columnName, $suffix)
    {
        $stringValues = explode('-', $str);
        $stringValuesLength = count($stringValues);
        if ($stringValuesLength == 2) {
            $min = floatval($stringValues[0]);
            $max = floatval($stringValues[1]);
            if ($min >= $max) {
                throw new \InvalidArgumentException("A dashed parameter has a minimum greater than it's maximum.");
            }
            $this->preparedVariables["min_" . $suffix] = $min;
            $this->preparedVariables["max_" . $suffix] = $max;
            return $columnName . " BETWEEN :min_" . $suffix . " AND :max_" . $suffix;
        } elseif ($stringValuesLength == 1) {
            $this->preparedVariables["total_" . $suffix] = floatval($stringValues[0]);
            return $columnName . " = :total_" . $suffix;
        } else {
            throw new \InvalidArgumentException("A dashed parameter has too many values.");
        }
    }
    /**
     * Generates the WHERE statement for a boolean, if the column uses the string Y for true.
     *
     * Generates a MySQL WHERE statement for a boolean value check.  If $str equals Y,  it looks for a value of Y.
     * If $str equals N, it looks for a NULL or EMPTY value.
     *
     * @param   string  $str            The value your looking for. (Y or N)
     * @param   string  $columnName     The name of the MySQL table column to search.
     * @param   string  $suffix         A suffix to be appended to the prepared variable name. (No Spaces Allowed)
     * @return  string  The MySQL statement.
     * @throws  \InvalidArgumentException   If $str value is not Y or N. (Case Insensitive)
     * @access  protected
     * @author  Johnathan Pulos
     */
    protected function generateWhereStatementForBoolean($str, $columnName, $suffix)
    {
        $val = strtoupper($str);
        if ($val == 'Y') {
            $this->preparedVariables[$suffix] = $val;
            return $columnName . " = :" . $suffix;
        } elseif ($val == 'N') {
            return "(" . $columnName . " IS NULL OR " . $columnName . " = '' OR " . $columnName . " = 'N')";
        } else {
            throw new \InvalidArgumentException("A boolean was set with the wrong value.");
        }
    }
    /**
     * Generates the WHERE statement for a boolean based on wether the column has content or not.
     *
     * Generates a MySQL WHERE statement for a boolean value check.  If $str equals Y,  it looks for a value that is
     * NOT NULL or EMPTY.  If $str equals N, it looks for a NULL or EMPTY value.
     *
     * @param   string  $str            The value your looking for. (Y or N)
     * @param   string  $columnName     The name of the MySQL table column to search.
     * @return  string  The MySQL statement.
     * @throws  \InvalidArgumentException   If $str value is not Y or N. (Case Insensitive)
     * @access  protected
     * @author  Johnathan Pulos
     */
    protected function generateWhereStatementForBooleanBasedOnIfFieldHasContentOrNot($str, $columnName)
    {
        $val = strtoupper($str);
        if ($val == 'Y') {
            return "(" . $columnName . " IS NOT NULL OR " . $columnName . " != '')";
        } elseif ($val == 'N') {
            return "(" . $columnName . " IS NULL OR " . $columnName . " = '')";
        } else {
            throw new \InvalidArgumentException("A boolean was set with the wrong value.");
        }
    }
    /**
     * Generates column name aliases based on the key and values of the $aliasFields class variable.
     *
     * Using the key and values of the $aliasFields class variable, it generates the column names to SELECT using the
     * following format: key AS value.  So with multiple values, you will receive key AS value, key AS value, key AS
     * value etc.
     *
     * @return string   The MySQL statement.
     * @access protected
     * @author Johnathan Pulos
     **/
    protected function generateAliasSelectStatement()
    {
        $statementArray = array();
        foreach ($this->aliasFields as $key => $value) {
            $statementArray[] = $key . " AS " . $value;
        }
        return join(', ', $statementArray);
    }
}
