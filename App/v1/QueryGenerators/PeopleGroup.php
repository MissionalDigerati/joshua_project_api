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

use DataObjects\SortData;

/**
 * Generates the PDO prepared statements and variables for People Groups.
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
class PeopleGroup extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you
     * do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = [
        'PeopleID3ROG3', 'ROG3', 'PeopleID3', 'ROP3', 'PeopNameInCountry', 'ROG2', 'Continent', 'RegionName', 'ISO3',
        'LocationInCountry', 'PeopleID1', 'ROP1', 'AffinityBloc', 'PeopleID2', 'ROP2', 'PeopleCluster',
        'PeopNameAcrossCountries', 'Population', 'Category', 'ROL3', 'PrimaryLanguageName',
        'PrimaryLanguageDialect', 'NumberLanguagesSpoken', 'OfficialLang', 'SpeakNationalLang',
        'BibleStatus', 'BibleYear', 'NTYear', 'PortionsYear', 'TranslationNeedQuestionable', 'JPScale',
        'JPScalePC', 'JPScalePGAC', 'LeastReached', 'LeastReachedPC', 'LeastReachedPGAC', 'GSEC',
        'AudioRecordings as HasAudioRecordings', 'NTOnline', 'RLG3', 'RLG3PC', 'RLG3PGAC', 'PrimaryReligion',
        'PrimaryReligionPC', 'PrimaryReligionPGAC', 'RLG4', 'ReligionSubdivision', 'PCIslam', 'PCNonReligious',
        'PCUnknown', 'SecurityLevel', 'LRTop100', 'PhotoAddress', 'PhotoCredits', 'PhotoCreditURL',
        'PhotoCreativeCommons', 'PhotoCopyright', 'PhotoPermission', 'ProfileTextExists', 'CountOfCountries',
        'CountOfProvinces', 'Longitude', 'Latitude', 'Ctry', 'IndigenousCode', 'PercentAdherents',
        'PercentChristianPC', 'NaturalName', 'NaturalPronunciation', 'PercentChristianPGAC', 'PercentEvangelical',
        'PercentEvangelicalPC', 'PercentEvangelicalPGAC', 'PCBuddhism', 'PCEthnicReligions', 'PCHinduism',
        'PCOtherSmall', 'RegionCode', 'PopulationPGAC', 'Frontier', 'MapAddress', 'JF as HasJesusFilm',
        'COALESCE(PhotoCCVersionText, "") AS PhotoCCVersionText',
        'COALESCE(PhotoCCVersionURL, "") AS PhotoCCVersionURL',
        'COALESCE(MapCredits, "") AS MapCredits', 'COALESCE(MapCreditURL, "") AS MapCreditURL',
        'COALESCE(MapCopyright, "") AS MapCopyright', 'COALESCE(MapCCVersionText, "") AS MapCCVersionText',
        'COALESCE(MapCCVersionURL, "") AS MapCCVersionURL',
        // @deprecated These fields have been replaced by the above fields.
        'JF', 'AudioRecordings',
    ];
    /**
     * An array of fields that are allowed to be sorted by.
     *
     * @var     array
     * @access  protected
     */
    protected $sortingFieldWhitelist = [
        'PeopleID3ROG3', 'ROG3', 'PeopleID3', 'ROP3', 'PeopNameInCountry', 'ROG2', 'Continent', 'RegionName', 'ISO3',
        'LocationInCountry', 'PeopleID1', 'ROP1', 'AffinityBloc', 'PeopleID2', 'ROP2', 'PeopleCluster',
        'PeopNameAcrossCountries', 'Population', 'Category', 'ROL3', 'PrimaryLanguageName',
        'PrimaryLanguageDialect', 'NumberLanguagesSpoken', 'OfficialLang', 'SpeakNationalLang',
        'BibleStatus', 'BibleYear', 'NTYear', 'PortionsYear', 'TranslationNeedQuestionable', 'JPScale',
        'JPScalePC', 'JPScalePGAC', 'LeastReached', 'LeastReachedPC', 'LeastReachedPGAC', 'GSEC',
        'AudioRecordings', 'NTOnline', 'RLG3', 'RLG3PC', 'RLG3PGAC', 'PrimaryReligion',
        'PrimaryReligionPC', 'PrimaryReligionPGAC', 'RLG4', 'ReligionSubdivision', 'PCIslam', 'PCNonReligious',
        'PCUnknown', 'SecurityLevel', 'LRTop100', 'PhotoAddress', 'ProfileTextExists', 'CountOfCountries',
        'CountOfProvinces', 'Longitude', 'Latitude', 'Ctry', 'IndigenousCode', 'PercentAdherents',
        'PercentChristianPC', 'NaturalName', 'NaturalPronunciation', 'PercentChristianPGAC', 'PercentEvangelical',
        'PercentEvangelicalPC', 'PercentEvangelicalPGAC', 'PCBuddhism', 'PCEthnicReligions', 'PCHinduism',
        'PCOtherSmall', 'RegionCode', 'PopulationPGAC', 'Frontier', 'MapAddress', 'HasJesusFilm', 'MapAddress',
        'MapAddressExpanded', 'PhotoAddress', 'JF', 'Window1040', 'HasAudioRecordings'
    ];
    /**
     * The database table to pull the data from.
     *
     * @var     string
     * @access  protected
     */
    protected $tableName = "jppeoples";
    /**
     * The MySQL CONCAT statement for generating the PeopleGroupMapURL.
     *
     * @var     string
     * @access  protected
     */
    protected $peopleGroupMapURLSelect = "IF(ISNULL(MapAddress) OR MapAddress = '', " .
        "'', CONCAT('https://joshuaproject.net/assets/media/profiles/maps/', MapAddress))";
    /**
     * The MySQL CONCAT statement for generating the PeopleGroupMapExpandedURL.
     *
     * @var     string
     * @access  protected
     */
    protected $peopleGroupMapExpandedURLSelect = "IF(ISNULL(MapAddressExpanded) OR MapAddressExpanded = '', " .
        "'', CONCAT('https://joshuaproject.net/assets/media/profiles/maps/', MapAddressExpanded))";
    /**
     * The MySQL CONCAT statement for generating the PeopleGroupURL.
     *
     * @var     string
     * @access  protected
     */
    protected $peopleGroupURLSelect = "CONCAT('https://joshuaproject.net/people_groups/', PeopleID3, '/', ROG3)";
    /**
     * The MySQL CONCAT statement for generating the PeopleGroupPhotoURL.
     *
     * @var     string
     * @access  protected
     */
    protected $peopleGroupPhotoURLSelect = "IF(ISNULL(PhotoAddress) OR PhotoAddress = '', " .
        "'', CONCAT('https://joshuaproject.net/assets/media/profiles/photos/', PhotoAddress))";
    /**
     * The MySQL CONCAT statement for generating the CountryURL.
     *
     * @var     string
     * @access  protected
     */
    protected $countryURLSelect = "CONCAT('https://joshuaproject.net/countries/', ROG3)";
    /**
     * An array of table columns (key) and their alias (value).
     *
     * @var     array
     * @access  protected
     **/
    protected $aliasFields = [
        '10_40Window'           =>  'Window1040'
    ];
    /**
     * Construct the People Group class.
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
        $this->selectFieldsStatement = join(', ', $this->fieldsToSelectArray) . ", " .
        $this->generateAliasSelectStatement();
        $scaleTextStatement = $this->getScaleTextStatement('JPScale');
        $scaleImageStatement = $this->getScaleImageURLStatement('JPScale');
        $this->selectFieldsStatement .= ", $this->peopleGroupMapURLSelect as PeopleGroupMapURL";
        $this->selectFieldsStatement .= ", $this->peopleGroupMapExpandedURLSelect as PeopleGroupMapExpandedURL";
        $this->selectFieldsStatement .= ", $this->peopleGroupURLSelect as PeopleGroupURL";
        $this->selectFieldsStatement .= ", $this->peopleGroupPhotoURLSelect as PeopleGroupPhotoURL";
        $this->selectFieldsStatement .= ", $this->countryURLSelect as CountryURL";
        $this->selectFieldsStatement .= ", $scaleTextStatement as JPScaleText";
        $this->selectFieldsStatement .= ", $scaleImageStatement as JPScaleImageURL";
        $this->sortData = new SortData('PeopleID1', 'ASC');
    }
    /**
     * Find the People Group by id (PeopleID3), and refine search by the country (ROG3).
     *
     * Find all People Groups with a specified id, and then filter by the country's
     *  <a href="http://goo.gl/1dhC" target="_blank">ISO 2 Letter code</a>.
     * The id is the Joshua Projects PeopleID3.
     * <br><br><strong>Requires $providedParams['id']:</strong> The Joshua Project PeopleID3.
     * <br><strong>Requires $providedParams['country']:</strong> The country's ISO 2 letter code.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException   If the 'id' key is not set on the $providedParams class variable.
     * @throws  \InvalidArgumentException   If the 'country' key is not set on the $providedParams class variable.
     * @author  Johnathan Pulos
     */
    public function findByIdAndCountry(): void
    {
        $this->validator->providedRequiredParams($this->providedParams, ['id', 'country']);
        $id = intval($this->providedParams['id']);
        $country = strtoupper($this->providedParams['country']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName .
            " WHERE PeopleID3 = :id AND ROG3 = :country LIMIT 1";
        $this->preparedVariables = ['id' => $id, 'country' => $country];
    }
    /**
     * Find the People Group by Id (PeopleID3).
     *
     * Find a People Group based on it's id,  Joshua Projects PeopleID3.
     * <br><br><strong>Requires $providedParams['id']:</strong> The Joshua Project PeopleID3.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException   If the 'id' key is not set on the $providedParams class variable.
     * @author  Johnathan Pulos
     */
    public function findById(): void
    {
        $this->validator->providedRequiredParams($this->providedParams, ['id']);
        $id = intval($this->providedParams['id']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName .
            " WHERE PeopleID3 = :id";
        $this->preparedVariables = ['id' => $id];
    }
    /**
     * Find all People Groups using specified filters.
     *
     * Find all People Groups using a wide range of filters.  To see the types of filters, checkout the Swagger
     * documentation of the API.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException   When you set a filter, but fail to provide a valid parameter
     * @author  Johnathan Pulos
     */
    public function findAllWithFilters(): void
    {
        $where = "";
        $appendAndOnWhere = false;
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName;
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
        if ($this->paramExists('bible_status')) {
            $this->validator->barSeperatedStringProvidesAcceptableValues(
                $this->providedParams['bible_status'],
                ['0', '1', '2', '3', '4', '5']
            );
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString($this->providedParams['bible_status'], 'BibleStatus');
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('continents')) {
            $this->validator->stringLengthValuesBarSeperatedString($this->providedParams['continents'], 3);
            $this->validator->barSeperatedStringProvidesAcceptableValues(
                $this->providedParams['continents'],
                ['afr', 'asi', 'aus', 'eur', 'nar', 'sop', 'lam']
            );
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
        if ($this->paramExists('has_audio')) {
            $this->validator->stringLength($this->providedParams['has_audio'], 1);
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
        if ($this->paramExists('has_jesus_film')) {
            $this->validator->stringLength($this->providedParams['has_jesus_film'], 1);
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
        if ($this->paramExists('indigenous')) {
            $this->validator->stringLength($this->providedParams['indigenous'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['indigenous'],
                'IndigenousCode',
                'indigenous'
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
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['least_reached'],
                'LeastReached',
                'least_reached'
            );
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
        if ($this->paramExists('pc_adherent')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_adherent'],
                'PercentAdherents',
                'pc_adherents'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_buddhist')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_buddhist'],
                'PCBuddhism',
                'pc_buddhist'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_ethnic_religion')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_ethnic_religion'],
                'PCEthnicReligions',
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
                'PCHinduism',
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
                'PCIslam',
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
                'PCNonReligious',
                'pc_non_religious'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_other_religion')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_other_religion'],
                'PCOtherSmall',
                'pc_other_religion'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('pc_unknown')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['pc_unknown'],
                'PCUnknown',
                'pc_unknown'
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
        if ($this->paramExists('primary_religions')) {
            $religions = explode('|', $this->providedParams['primary_religions']);
            foreach ($religions as $religion) {
                $this->validator->integerInRange(intval($religion), 1, 9, [3]);
            }
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateInStatementFromPipedString(
                $this->providedParams['primary_religions'],
                'RLG3'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('regions')) {
            $regions = explode('|', $this->providedParams['regions']);
            foreach ($regions as $region) {
                $this->validator->integerInRange(intval($region), 1, 12);
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
        if ($this->paramExists('is_frontier')) {
            $this->validator->stringLength($this->providedParams['is_frontier'], 1);
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateWhereStatementForBoolean(
                $this->providedParams['is_frontier'],
                'Frontier',
                'is_frontier'
            );
            $appendAndOnWhere = true;
        }
        if ($this->paramExists('population_pgac')) {
            if ($appendAndOnWhere === true) {
                $where .= " AND ";
            }
            $where .= $this->generateBetweenStatementFromDashSeperatedString(
                $this->providedParams['population_pgac'],
                'PopulationPGAC',
                'pop_pgac'
            );
            $appendAndOnWhere = true;
        }
        if ($where != "") {
            $this->preparedStatement .= " WHERE " . $where;
        }
        $this->addOrderStatement();
        $this->addLimitFilter();
    }

    /**
     * Find a list of countries where a specific People Group is located. This is an internal function
     * and does not provide access through the API directly. It does not use the providedParams.
     *
     * @param int $id   The PeopleID3 of the People Group.
     *
     * @return void
     * @access public
     */
    public function findCountryList(int $id): void
    {
        $this->preparedStatement = "SELECT ROG3, Ctry, Population, JPScale FROM " . $this->tableName .
        " WHERE PeopleID3 = :id";
        $this->preparedVariables = ['id' => $id];
    }
}
