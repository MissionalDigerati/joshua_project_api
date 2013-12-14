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
 * These queries specifically work with the Country data.
 *
 * @package default
 * @author Johnathan Pulos
 */
class Country extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you do not want to expose.
     *
     * @var array
     * @access protected
     */
    protected $fieldsToSelectArray = array('ROG3','ISO3','ISO2','ROG2','RegionCode','RegionName','AltName','Capital','Population','PopulationSource','PoplGrowthRate','AreaSquareMiles','AreaSquareKilometers','PopulationPerSquareMile','CountryPhoneCode','SecurityLevel','LibraryCongressReportExists','IsCountry','BJMFocusCountry','StonyGround','USAPostalSystem','ROL3OfficialLanguage','OfficialLang','ROL3SecondaryLanguage','SecondLang','RLG4Primary','ReligionSubdivision','ReligionDataYear','LiteracyCategory','LiteracyRate','LiteracyRange','LiteracySource','CountryNotes','NSMMissionArticles','EthnolinguisticMap','PercentPopulationDifference','PercentChristianity','PercentEvangelical','PercentBuddhism','PercentDoublyProfessing','PercentEthnicReligions','PercentHinduism','PercentIslam','PercentNonReligious','PercentOtherSmall','PercentUnknown','PercentAnglican','PercentIndependent','PercentProtestant','PercentOrthodox','PercentOther','PercentRomanCatholic','CntPeoples','PoplPeoples','CntPeoplesLR','PoplPeoplesLR','JPScaleCtry','HDIYear','HDIValue','HDIRank','StateDeptReligiousFreedom','Source','EditName','EditDate','V59Country','EthnologueCountryCode','EthnologueMapExists','UNMap','PersecutionRankingOD','InternetCtryCode','PercentUrbanized','PrayercastVideo','PrayercastBlipTVCode','WINCountryProfile');
    /**
     * The table to pull the data from
     *
     * @var string
     * @access protected
     */
    protected $tableName = "jpcountries";
    /**
     * A string that will hold the default order by for the Select statement
     *
     * @var string
     * @access protected
     */
    protected $defaultOrderByStatement = "ORDER BY Country ASC";
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
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray) . ", 10_40Window as Window10_40";
        $this->selectFieldsStatement .= ", 10_40WindowOriginal as Window10_40Original";
        $this->selectFieldsStatement .= ", Ctry as Country, Ctry_id as Country_ID, ReligionPrimary as PrimaryReligion, RLG3Primary as RLG3";
    }
    /**
     * Find the Country by it's ID (ROG3) or ISO2
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function findById()
    {
        $id = strtoupper($this->providedParams['id']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName . " WHERE ROG3 = :id LIMIT 1";
        $this->preparedVariables = array('id' => $id);
    }
    /**
     * Find all countries using the provided filters
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
        if ($this->paramExists('continents')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['continents'], 3);
            $this->validator->barSeperatedStringProvidesAcceptableValues($this->providedParams['continents'], array('afr', 'asi', 'aus', 'eur', 'nar', 'sop', 'lam'));
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['continents'], 'ROG2');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('ids')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['ids'], 'ROG3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('jpscale')) {
            $this->validator->barSeperatedStringProvidesAcceptableValues($this->providedParams['jpscale'], array('1.1', '1.2', '2.1', '2.2', '3.1', '3.2'));
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['jpscale'], 'JPScaleCtry');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_anglican')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_anglican'], 'PercentAnglican', 'pc_anglican');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_buddhist')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_buddhist'], 'PercentBuddhism', 'pc_buddhist');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_christianity')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_christianity'], 'PercentChristianity', 'pc_christianity');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_ethnic_religion')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_ethnic_religion'], 'PercentEthnicReligions', 'pc_ethnic_religion');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_evangelical')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_evangelical'], 'PercentEvangelical', 'pc_evangelical');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_hindu')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_hindu'], 'PercentHinduism', 'pc_hindu');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_independent')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_independent'], 'PercentIndependent', 'pc_independent');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_islam')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_islam'], 'PercentIslam', 'pc_islam');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_non_religious')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_non_religious'], 'PercentNonReligious', 'pc_non_religious');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_other_religion')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_other_religion'], 'PercentOtherSmall', 'pc_other_religion');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_orthodox')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_orthodox'], 'PercentOrthodox', 'pc_orthodox');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_other_christian')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_other_christian'], 'PercentOther', 'pc_other_christian');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_protestant')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_protestant'], 'PercentProtestant', 'pc_protestant');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_rcatholic')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_rcatholic'], 'PercentRomanCatholic', 'pc_rcatholic');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_unknown')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_unknown'], 'PercentUnknown', 'pc_unknown');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('population')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['population'], 'Population', 'pop');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('primary_languages')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['primary_languages'], 3);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['primary_languages'], 'ROL3OfficialLanguage');
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
            $where .= $this->generateInStatementFromPipedString($this->providedParams['primary_religions'], 'RLG3Primary');
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
            $where .= $this->generateWhereStatementForBoolean($this->providedParams['window1040'], '10_40Window', 'window_10_40');
            $appendAndOnWhere = true;
        }
        if ($where != "") {
            $this->preparedStatement .= " WHERE " . $where;
        }
        $this->preparedStatement .= " " . $this->defaultOrderByStatement . " ";
        $this->addLimitFilter();
    }
}
