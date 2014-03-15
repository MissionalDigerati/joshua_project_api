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
 * @copyright Copyright 2013 Missional Digerati
 * 
 */
namespace QueryGenerators;

/**
 * A class that creates the prepared statement, and sets up the variables for a PDO prepared statement query.
 * These queries specifically work with the Continent data.
 *
 * @package default
 * @author Johnathan Pulos
 */
class Continent extends QueryGenerator
{
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
     * The table to pull the data from
     *
     * @var string
     * @access protected
     */
    protected $tableName = "jpcontinentsum";
    /**
     * A string that will hold the default order by for the Select statement
     *
     * @var string
     * @access protected
     */
    protected $defaultOrderByStatement = "ORDER BY Continent ASC";
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
     * find a continent by an id. [1] Africa [2] Asia [3] Australia [4] Europe [5] North America [6] Oceania [7] South America
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function findById()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id'));
        $id = intval($this->providedParams['id']);
        $this->validator->integerInRange($id, 1, 7);
        $continentISO = array(
            1   =>  'AFR',
            2   =>  'ASI',
            3   =>  'AUS',
            4   =>  'EUR',
            5   =>  'NAR',
            6   =>  'SOP',
            7   =>  'LAM'
        );
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName . " WHERE ROG2 = :id LIMIT 1";
        $this->preparedVariables = array('id' => $continentISO[$id]);
    }
}
