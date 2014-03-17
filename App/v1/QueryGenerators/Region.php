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
 * A class that creates the prepared statement, and sets up the variables for a PDO prepared statement query.
 * These queries specifically work with the Region data.
 *
 * @package default
 * @author Johnathan Pulos
 */
class Region extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you do not want to expose.
     *
     * @var array
     * @access protected
     */
    protected $fieldsToSelectArray = array(
        'RegionCode', 'RegionName', 'NbrCountries', 'NbrPGIC', 'NbrLR', 'SumRegion', 'PercentLR', 'SumRegionLR', 'PercentPoplLR', 'PercentUrbanized'
    );
    /**
     * The table to pull the data from
     *
     * @var string
     * @access protected
     */
    protected $tableName = "jpregionsum";
    /**
     * A string that will hold the default order by for the Select statement
     *
     * @var string
     * @access protected
     */
    protected $defaultOrderByStatement = "ORDER BY RegionName ASC";
    /**
     * An array of table columns (key) and their alias (value)
     *
     * @var array
     * @access protected
     **/
    protected $aliasFields = array();
    /**
     * Construct the class
     *
     * @param array $getParams the params to use for the query.  Each message has required fields, and will throw error
     * if they are missing
     * 
     * @access public
     * @author Johnathan Pulos
     */
    public function __construct($getParams)
    {
        parent::__construct($getParams);
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray);
    }
    /**
     * find a region using it's region code [id]
     *
     *  [1 ] South Pacific
     *  [2 ] Southeast Asia
     *  [3 ] Northeast Asia
     *  [4 ] South Asia
     *  [5 ] Central Asia
     *  [6 ] Middle East and North Africa
     *  [7 ] East and Southern Africa
     *  [8 ] West and Central Africa
     *  [9 ] Eastern Europe and Eurasia
     *  [10] Western Europe
     *  [11] Central and South America
     *  [12] North America and Caribbean
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function findById()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id'));
        $id = intval(strip_tags($this->providedParams['id']));
        $this->validator->integerInRange($id, 1, 12);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName . " WHERE RegionCode = :id LIMIT 1";
        $this->preparedVariables = array('id' => $id);
    }
}
