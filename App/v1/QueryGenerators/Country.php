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
 * Generates the PDO prepared statements and variables for Countries.
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
class Country extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.
     * Simply remove fields you do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = array('JPScaleCtry', 'Ctry', 'PercentBuddhism', 'PercentChristianity',
        'PercentEthnicReligions', 'PercentEvangelical', 'PercentHinduism', 'PercentIslam', 'PercentNonReligious',
        'ReligionPrimary', 'RLG3Primary', 'ROG2', 'ROG3', 'ROL3OfficialLanguage', 'RLG3Primary', 'RegionCode',
        'ROG3', 'ISO3', 'ISO2', 'ROG2', 'RegionName', 'Capital', 'Population', 'SecurityLevel', 'CntPeoples',
        'CntPeoplesLR', 'CntPrimaryLanguages', 'TranslationUnspecified', 'TranslationNeeded',
        'TranslationStarted', 'BiblePortions', 'BibleNewTestament', 'BibleComplete', 'PoplPeoplesLR',
        'PoplPeoplesFPG'
    );
    /**
     * The Database table to pull the data from.
     *
     * @var     string
     * @access  protected
     */
    protected $tableName = "jpcountries";
    /**
     * A string that will hold the default MySQL ORDER BY for the Select statement.
     *
     * @var     string
     * @access  protected
     */
    protected $defaultOrderByStatement = "ORDER BY Ctry ASC";
    /**
     * An array of table columns (key) and their alias (value).
     *
     * @var     array
     * @access  protected
     **/
    protected $aliasFields = array('10_40Window'    =>  'Window1040');
    /**
     * Construct the Country class.
     *
     * During construction,  the $getParams are checked and inserted in the $providedParams class variable.
     * Some of the methods in this class require certain keys to be set, or it will throw an error.
     * The comments will state the required keys.
     *
     * @param   array   $getParams  The GET params to use for the query.
     * @return  void
     * @access  public
     * @author  Johnathan Pulos
     */
    public function __construct($getParams)
    {
        parent::__construct($getParams);
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray) .
            ", " . $this->generateAliasSelectStatement();
        $this->selectFieldsStatement .= ", " .
            str_replace('JPScale', 'JPScaleCtry', $this->JPScaleTextSelectStatement) . " as JPScaleText";
        $this->selectFieldsStatement .= ", " .
            str_replace('JPScale', 'JPScaleCtry', $this->JPScaleImageURLSelectStatement) . " as JPScaleImageURL";
    }
    /**
     * Find a country by it's id.
     *
     * Find a country using it's <a href="http://goo.gl/1dhC" target="_blank">ISO 2 Letter code</a>,
     * or the Joshua Projects ROG3 id.
     * <br><br><strong>Requires $providedParams['id']:</strong> The 2 letter ISO code.
     *
     * @return  void
     * @throws  \InvalidArgumentException If the 'id' key is not set on the $providedParams class variable.
     * @access  public
     * @author  Johnathan Pulos
     */
    public function findById()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id'));
        $id = strtoupper($this->providedParams['id']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement .
            " FROM " . $this->tableName . " WHERE ROG3 = :id LIMIT 1";
        $this->preparedVariables = array('id' => $id);
    }
    /**
     * Find all countries using specified filters.
     *
     * Find all countries using a wide range of filters.  To see the types of filters, checkout the Swagger
     * documentation of the API.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException When you set a filter, but fail to provide a valid parameter
     * @author  Johnathan Pulos
     **/
    public function findAllWithFilters()
    {
        $where = "";
        $appendAndOnWhere = false;
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName;
        if ($this->paramExists('continents')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['continents'], 3);
            $this->validator->barSeperatedStringProvidesAcceptableValues(
                $this->providedParams['continents'],
                array('afr', 'asi', 'aus', 'eur', 'nar', 'sop', 'lam')
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['continents'], 'ROG2');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('ids')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['ids'], 2);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['ids'], 'ROG3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('jpscale')) {
            $this->validator->barSeperatedStringProvidesAcceptableValues(
                $this->providedParams['jpscale'],
                array('1', '2', '3', '4', '5')
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['jpscale'], 'JPScaleCtry');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_buddhist')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_buddhist'],
                'PercentBuddhism',
                'pc_buddhist'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_christianity')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_christianity'],
                'PercentChristianity',
                'pc_christianity'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_ethnic_religion')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_ethnic_religion'],
                'PercentEthnicReligions',
                'pc_ethnic_religion'
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
        if ($this->paramExists('pc_hindu')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_hindu'],
                'PercentHinduism',
                'pc_hindu'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_islam')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_islam'],
                'PercentIslam',
                'pc_islam'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_non_religious')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_non_religious'],
                'PercentNonReligious',
                'pc_non_religious'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('population')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['population'],
                'Population',
                'pop'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pop_in_frontier')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pop_in_frontier'],
                'PoplPeoplesFPG',
                'pop_frontier'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pop_in_unreached')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pop_in_unreached'],
                'PoplPeoplesLR',
                'pop_unreached'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('primary_languages')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['primary_languages'], 3);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString(
                $this->providedParams['primary_languages'],
                'ROL3OfficialLanguage'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('primary_religions')) {
            $religions = explode('|', $this->providedParams['primary_religions']);
            foreach ($religions as $religion) {
                $this->validator->integerInRange($religion, 1, 9, array(3));
            }
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString(
                $this->providedParams['primary_religions'],
                'RLG3Primary'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('regions')) {
            $regions = explode('|', $this->providedParams['regions']);
            foreach ($regions as $region) {
                $this->validator->integerInRange($region, 1, 12);
            }
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['regions'], 'RegionCode');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('window1040')) {
            $this->validator->stringLength($this->providedParams['window1040'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['window1040'],
                '10_40Window',
                'window_10_40'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('cnt_primary_languages')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['cnt_primary_languages'],
                'CntPrimaryLanguages',
                'cnt_primary_languages'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('translation_unspecified')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['translation_unspecified'],
                'TranslationUnspecified',
                'translation_unspecified'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('translation_needed')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['translation_needed'],
                'TranslationNeeded',
                'translation_needed'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('translation_started')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['translation_started'],
                'TranslationStarted',
                'translation_started'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('bible_portions')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['bible_portions'],
                'BiblePortions',
                'bible_portions'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('bible_new_testament')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['bible_new_testament'],
                'BibleNewTestament',
                'bible_new_testament'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('bible_complete')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['bible_complete'],
                'BibleComplete',
                'bible_complete'
            );
            $appendAndOnWhere = true;
        }
        if ($where != "") {
            $this->preparedStatement .= " WHERE " . $where;
        }
        $this->preparedStatement .= " " . $this->defaultOrderByStatement . " ";
        $this->addLimitFilter();
    }
}
