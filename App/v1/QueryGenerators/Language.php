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
 * Generates the PDO prepared statements and variables for Languages.
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
class Language extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.
     * Simply remove fields you do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = [
        'ROL3', 'Language', 'WebLangText', 'Status', 'ROG3', 'HubCountry', 'BibleStatus',
        'GRN_URL', 'TranslationNeedQuestionable', 'BibleYear', 'NTYear', 'PortionsYear',
        'PercentAdherents', 'PercentEvangelical', 'JF AS HasJesusFilm', 'JF_URL',
        'AudioRecordings AS HasAudioRecordings', 'JPScale', 'LeastReached', 'RLG3',
        'PrimaryReligion', 'FCBH_URL', 'NbrPGICs', 'NbrCountries'
    ];
    /**
     * The Database table to pull the data from.
     *
     * @var     string
     * @access  protected
     */
    protected $tableName = "jplanguages";
    /**
     * A string that will hold the default MySQL ORDER BY for the Select statement.
     *
     * @var     string
     * @access  protected
     */
    protected $defaultOrderByStatement = "ORDER BY Language ASC";
    /**
     * Construct the Language class.
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
    }
    /**
     * Find a Language by it's id.
     *
     * Find a language using it's 3 letter ISO code, or Joshua Projects ROL3 code.  You can find a list of codes at
     * <a href='http://goo.gl/gbkgo4' target='_blank'>this website</a>.<br><br><strong>Requires $providedParams['id']:
     * </strong> The three letter ISO code or Joshua Projects ROL3 code.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException If the 'id' key is not set on the $providedParams class variable.
     * @author  Johnathan Pulos
     **/
    public function findById(): void
    {
        $id = strtoupper($this->providedParams['id']);
        $this->validator->providedRequiredParams($this->providedParams, ['id']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement .
            " FROM " . $this->tableName . " WHERE ROL3 = :id LIMIT 1";
        $this->preparedVariables = ['id' => $id];
    }
    /**
     * Find all languages using specific filters.
     *
     * Find all languages using a wide range of filters.  To see the types of filters, checkout the Swagger
     * documentation of the API.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException When you set a filter, but fail to provide a valid parameter
     * @author  Johnathan Pulos
     **/
    public function findAllWithFilters(): void
    {
        $where = "";
        $appendAndOnWhere = false;
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName;
        if ($this->paramExists('countries')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['countries'], 2);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['countries'], 'ROG3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('ids')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['ids'], 3);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['ids'], 'ROL3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_audio')) {
            $this->validator->stringLength(
                $this->providedParams['has_audio'],
                1
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['has_audio'],
                'AudioRecordings',
                'has_audio'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_completed_bible')) {
            $this->validator->stringLength($this->providedParams['has_completed_bible'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBooleanBasedOnIfFieldHasContentOrNot(
                $this->providedParams['has_completed_bible'],
                'BibleYear'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_jesus_film')) {
            $this->validator->stringLength(
                $this->providedParams['has_jesus_film'],
                1
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['has_jesus_film'],
                'JF',
                'has_jesus_film'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_new_testament')) {
            $this->validator->stringLength($this->providedParams['has_new_testament'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBooleanBasedOnIfFieldHasContentOrNot(
                $this->providedParams['has_new_testament'],
                'NTYear'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('has_portions')) {
            $this->validator->stringLength($this->providedParams['has_portions'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBooleanBasedOnIfFieldHasContentOrNot(
                $this->providedParams['has_portions'],
                'PortionsYear'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('jpscale')) {
            $this->validator->barSeperatedStringProvidesAcceptableValues(
                $this->providedParams['jpscale'],
                ['1', '2', '3', '4', '5']
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['jpscale'], 'JPScale');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('least_reached')) {
            $this->validator->stringLength($this->providedParams['least_reached'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['least_reached'],
                'LeastReached',
                'least_reached'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('needs_translation_questionable')) {
            $this->validator->stringLength(
                $this->providedParams['needs_translation_questionable'],
                1
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['needs_translation_questionable'],
                'TranslationNeedQuestionable',
                'questionable_need'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_adherent')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_adherent'],
                'PercentAdherents',
                'pc_adherent'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_evangelical')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_evangelical'],
                'PercentEvangelical',
                'pc_evangelical'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('primary_religions')) {
            $religions = explode('|', $this->providedParams['primary_religions']);
            foreach ($religions as $religion) {
                $this->validator->integerInRange(intval($religion), 1, 9, [3]);
            }
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['primary_religions'], 'RLG3');
            $appendAndOnWhere = true;
        }
        if ($where != "") {
            $this->preparedStatement .= " WHERE " . $where;
        }
        $this->preparedStatement .= " " . $this->defaultOrderByStatement . " ";
        $this->addLimitFilter();
    }
}
