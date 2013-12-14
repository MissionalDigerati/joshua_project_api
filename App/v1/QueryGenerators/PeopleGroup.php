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
 * These queries specifically work with the PeopleGroup data.
 *
 * @package default
 * @author Johnathan Pulos
 */
class PeopleGroup extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you do not want to expose.
     *
     * @var array
     * @access protected
     */
    protected $fieldsToSelectArray = array('ROG3', 'Ctry', 'PeopleID3', 'ROP3', 'PeopNameInCountry', 'ROG2', 'Continent', 'RegionCode', 'RegionName', 'ISO3', 'LocationInCountry', 'PeopleID1', 'ROP1', 'AffinityBloc', 'PeopleID2', 'ROP2', 'PeopleCluster', 'PeopNameAcrossCountries', 'Population', 'PopulationPercentUN', 'Category', 'ROL3', 'PrimaryLanguageName', 'ROL4', 'PrimaryLanguageDialect', 'NumberLanguagesSpoken', 'ROL3OfficialLanguage', 'OfficialLang', 'SpeakNationalLang', 'BibleStatus', 'BibleYear', 'NTYear', 'PortionsYear', 'TranslationNeedQuestionable', 'JPScale', 'LeastReached', 'LeastReachedBasis', 'GSEC', 'Unengaged', 'JF', 'AudioRecordings', 'NTOnline', 'GospelRadio', 'RLG3', 'PrimaryReligion', 'RLG4', 'ReligionSubdivision', 'PercentAdherents', 'PercentEvangelical', 'PCBuddhism', 'PCDblyProfessing', 'PCEthnicReligions', 'PCHinduism', 'PCIslam', 'PCNonReligious', 'PCOtherSmall', 'PCUnknown', 'PCAnglican', 'PCIndependent', 'PCProtestant', 'PCOrthodox', 'PCOtherChristian', 'PCRomanCatholic', 'StonyGround', 'SecurityLevel', 'OriginalJPL', 'RaceCode', 'IndigenousCode', 'LRWebProfile', 'LRofTheDayMonth', 'LRofTheDayDay', 'LRTop100', 'PhotoAddress', 'PhotoWidth', 'PhotoHeight', 'PhotoAddressExpanded', 'PhotoCredits', 'PhotoCreditURL', 'PhotoCreativeCommons', 'PhotoCopyright', 'PhotoPermission', 'MapAddress', 'MapAddressExpanded', 'MapCredits', 'MapCreditURL', 'MapCopyright', 'MapPermission', 'ProfileTextExists', 'FileAddress', 'FileAddressExpanded', 'FileCredits', 'FileCreditURL', 'FileCopyright', 'FilePermission', 'Top10Ranking', 'RankOverall', 'RankProgress', 'RankPopulation', 'RankLocation', 'RankMinistryTools', 'CountOfCountries', 'CountOfProvinces', 'EthnolinguisticMap', 'MapID', 'V59Country', 'MegablocPC', 'LargeSouthAsianLanguageROL3', 'Longitude', 'Latitude');
    /**
     * The table to pull the data from
     *
     * @var string
     * @access protected
     */
    protected $tableName = "jppeoples";
    /**
     * A string that will hold the default order by for the Select statement
     *
     * @var string
     * @access protected
     */
    protected $defaultOrderByStatement = "ORDER BY PeopleID1 ASC";
    /**
     * The CONCAT statement for generating the PeopleGroupURL
     *
     * @var string
     * @access private
     */
    private $peopleGroupURLSelect = "CONCAT('http://www.joshuaproject.net/people-profile.php?peo3=', PeopleID3, '&amp;rog3=', ROG3)";
    /**
     * The CONCAT statement for generating the PeopleGroupPhotoURL
     *
     * @var string
     * @access private
     */
    private $peopleGroupPhotoURLSelect = "CONCAT('http://www.joshuaproject.net/profiles/photos/', PhotoAddress)";
    /**
     * The CONCAT statement for generating the CountryURL
     *
     * @var string
     * @access private
     */
    private $countryURLSelect = "CONCAT('http://www.joshuaproject.net/countries.php?rog3=', ROG3)";
    /**
     * The CONCAT statement for generating the JPScaleImageURL
     *
     * @var string
     * @access private
     */
    private $JPScaleImageURLSelect = "CONCAT('http://www.joshuaproject.net/images/scale', ROUND(JPScale), '.jpg')";
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
        $this->selectFieldsStatement .= ", " . $this->peopleGroupURLSelect . " as PeopleGroupURL";
        $this->selectFieldsStatement .= ", " . $this->peopleGroupPhotoURLSelect . " as PeopleGroupPhotoURL";
        $this->selectFieldsStatement .= ", " . $this->countryURLSelect . " as CountryURL";
        $this->selectFieldsStatement .= ", " . $this->JPScaleTextSelectStatement . " as JPScaleText";
        $this->selectFieldsStatement .= ", " . $this->JPScaleImageURLSelect . " as JPScaleImageURL";
    }
    /**
     * Get the unreached of the day query statement.  Requires a month and day param in the given params.
     * REQUIRES getParams month & day
     * 
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function dailyUnreached()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('month', 'day'));
        $month = intval($this->providedParams['month']);
        $day = intval($this->providedParams['day']);
        $this->validator->integerInRange($month, 1, 12);
        $this->validator->integerInRange($day, 1, 31);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName . " WHERE LRofTheDayMonth = :month AND LRofTheDayDay = :day LIMIT 1";
        $this->preparedVariables = array('month' => $month, 'day' => $day);
    }
    /**
     * Find the People Group using the id (PeopleID3), and the country (ROG3)
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function findByIdAndCountry()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id', 'country'));
        $id = intval($this->providedParams['id']);
        $country = strtoupper($this->providedParams['country']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName . " WHERE PeopleID3 = :id AND ROG3 = :country LIMIT 1";
        $this->preparedVariables = array('id' => $id, 'country' => $country);
    }
    /**
     * Find the People Group by ID (PeopleID3)
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function findById()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id'));
        $id = intval($this->providedParams['id']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName . " WHERE PeopleID3 = :id";
        $this->preparedVariables = array('id' => $id);
    }
    /**
     * Find all the People Groups using filters passed in the GET params
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function findAllWithFilters()
    {
        $where = "";
        $appendAndOnWhere = false;
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName;
        if ($this->paramExists('window1040')) {
            $this->validator->stringLength($this->providedParams['window1040'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean($this->providedParams['window1040'], '10_40Window', 'window_10_40');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('continents')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['continents'], 3);
            $this->validator->barSeperatedStringProvidesAcceptableValues($this->providedParams['continents'], array('afr', 'asi', 'aus', 'eur', 'nar', 'sop', 'lam'));
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['continents'], 'ROG2');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('countries')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['countries'], 2);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['countries'], 'ROG3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('indigenous')) {
            $this->validator->stringLength($this->providedParams['indigenous'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean($this->providedParams['indigenous'], 'IndigenousCode', 'indigenous');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('jpscale')) {
            $this->validator->barSeperatedStringProvidesAcceptableValues($this->providedParams['jpscale'], array('1.1', '1.2', '2.1', '2.2', '3.1', '3.2'));
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['jpscale'], 'JPScale');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('languages')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['languages'], 3);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['languages'], 'ROL3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('least_reached')) {
            $this->validator->stringLength($this->providedParams['least_reached'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean($this->providedParams['least_reached'], 'LeastReached', 'least_reached');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('people_id1')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['people_id1'], 'PeopleID1');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('people_id2')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['people_id2'], 'PeopleID2');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('people_id3')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['people_id3'], 'PeopleID3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_anglican')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_anglican'], 'PCAnglican', 'pc_anglican');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_adherent')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_adherent'], 'PercentAdherents', 'pc_adherents');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_buddhist')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_buddhist'], 'PCBuddhism', 'pc_buddhist');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_ethnic_religion')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_ethnic_religion'], 'PCEthnicReligions', 'pc_ethnic_religion');
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
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_hindu'], 'PCHinduism', 'pc_hindu');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_independent')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_independent'], 'PCIndependent', 'pc_independent');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_islam')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_islam'], 'PCIslam', 'pc_islam');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_non_religious')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_non_religious'], 'PCNonReligious', 'pc_non_religious');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_orthodox')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_orthodox'], 'PCOrthodox', 'pc_orthodox');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_other_christian')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_other_christian'], 'PCOtherChristian', 'pc_other_christian');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_other_religion')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_other_religion'], 'PCOtherSmall', 'pc_other_religion');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_protestant')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_protestant'], 'PCProtestant', 'pc_protestant');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_rcatholic')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_rcatholic'], 'PCRomanCatholic', 'pc_rcatholic');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_unknown')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['pc_unknown'], 'PCUnknown', 'pc_unknown');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('population')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString($this->providedParams['population'], 'Population', 'pop');
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
            $where .= $this->generateInStatementFromPipedString($this->providedParams['primary_religions'], 'RLG3');
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
        if ($this->paramExists('rop1')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['rop1'], 'ROP1');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('rop2')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['rop2'], 'ROP2');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('rop3')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['rop3'], 'ROP3');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('unengaged')) {
            $this->validator->stringLength($this->providedParams['unengaged'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean($this->providedParams['unengaged'], 'Unengaged', 'unengaged');
            $appendAndOnWhere = true;
        }
        if ($where != "") {
            $this->preparedStatement .= " WHERE " . $where;
        }
        $this->preparedStatement .= " " . $this->defaultOrderByStatement . " ";
        $this->addLimitFilter();
    }
}
