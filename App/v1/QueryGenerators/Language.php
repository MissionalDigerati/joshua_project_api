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
 * These queries specifically work with the Language data.
 *
 * @package default
 * @author Johnathan Pulos
 */
class Language extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you do not want to expose.
     *
     * @var array
     * @access protected
     */
    protected $fieldsToSelectArray = array(
        'ROL3', 'Language', 'WebLangText', 'Status', 'ROG3', 'HubCountry', 'WorldSpeakers', 'BibleStatus', 'TranslationNeedQuestionable', 'BibleYear',
        'NTYear', 'PortionsYear', 'ROL3Edition14', 'ROL3Edition14Orig', 'JF', 'JF_URL', 'JF_ID', 'GRN_URL', 'AudioRecordings','GodsStory',
        'FCBH_ID', 'JPScale', 'PercentAdherents', 'PercentEvangelical', 'LeastReached', 'JPPopulation', 'RLG3', 'PrimaryReligion', 'NbrPGICs',
        'NbrCountries'
    );
    /**
     * The table to pull the data from
     *
     * @var string
     * @access protected
     */
    protected $tableName = "jplanguages";
    /**
     * A string that will hold the default order by for the Select statement
     *
     * @var string
     * @access protected
     */
    protected $defaultOrderByStatement = "ORDER BY Language ASC";
    /**
     * An array of table columns (key) and their alias (value)
     *
     * @var array
     * @access protected
     **/
    protected $aliasFields = array(
        '4Laws_URL' => 'FourLaws_URL',
        '4Laws' => 'FourLaws'
    );
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
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray) . ", " . $this->generateAliasSelectStatement();
    }
    /**
     * Find a Language by it's id (ROL3) 3 letter code
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function findById()
    {
        $id = strtoupper($this->providedParams['id']);
        $this->validator->providedRequiredParams($this->providedParams, array('id'));
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName . " WHERE ROL3 = :id LIMIT 1";
        $this->preparedVariables = array('id' => $id);
    }
    /**
     * Find all Languages by applying the supplied filters
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function findAllWithFilters()
    {
        $where = "";
        $appendAndOnWhere = false;
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName;
        if ($this->paramExists('ids')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['ids'], 3);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['ids'], 'ROL3');
            $appendAndOnWhere = true;
        }
        if ($where != "") {
            $this->preparedStatement .= " WHERE " . $where;
        }
        $this->preparedStatement .= " " . $this->defaultOrderByStatement . " ";
        $this->addLimitFilter();
    }
}
