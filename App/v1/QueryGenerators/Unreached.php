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
 * Generates the PDO prepared statements and variables for Unreached data.
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
class Unreached extends PeopleGroup
{
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you
     * do not want to expose.
     *
     * @var     array
     * @access  protected
     */
    protected $fieldsToSelectArray = [
        'jpupgotd.PeopleID3ROG3', 'jpupgotd.ROG3', 'jpupgotd.PeopleID3', 'jpupgotd.PeopNameInCountry', 'jpupgotd.ROG2',
        'jpupgotd.Continent', 'jpupgotd.RegionName', 'jpupgotd.PeopleID1', 'jpupgotd.AffinityBloc',
        'jpupgotd.PeopleID2', 'jpupgotd.PeopleCluster', 'jpupgotd.PeopNameAcrossCountries', 'jpupgotd.Population',
        'jpupgotd.ROL3', 'jpupgotd.PrimaryLanguageName', 'jpupgotd.BibleYear', 'jpupgotd.NTYear',
        'jpupgotd.PortionsYear', 'jpupgotd.JPScale', 'jpupgotd.LeastReached', 'jpupgotd.JF AS HasJesusFilm',
        'jpupgotd.AudioRecordings AS HasAudioRecordings', 'jpupgotd.NTOnline', 'jpupgotd.RLG3',
        'jpupgotd.PrimaryReligion', 'jpupgotd.LRofTheDayMonth', 'jpupgotd.LRofTheDaySet', 'jpupgotd.LRofTheDayDay',
        'jpupgotd.PhotoAddress', 'jpupgotd.PhotoCredits', 'jpupgotd.PhotoCreditURL',
        'jpupgotd.PhotoCreativeCommons', 'jpupgotd.PhotoCopyright', 'jpupgotd.PhotoPermission',
        'jpupgotd.CountOfCountries', 'jpupgotd.Longitude', 'jpupgotd.Latitude', 'jpupgotd.Ctry', 'jpupgotd.ROL3',
        'jpupgotd.PercentAdherents', 'jpupgotd.PercentEvangelical', 'jpupgotd.RegionCode', 'jppeoples.ROP3',
        'jppeoples.ISO3', 'jppeoples.LocationInCountry', 'jppeoples.ROP1', 'jppeoples.ROP2', 'jppeoples.Category',
        'jppeoples.PrimaryLanguageDialect', 'jppeoples.NumberLanguagesSpoken', 'jppeoples.OfficialLang',
        'jppeoples.SpeakNationalLang', 'jppeoples.BibleStatus', 'jppeoples.TranslationNeedQuestionable',
        'jppeoples.JPScalePC', 'jppeoples.JPScalePGAC', 'jppeoples.LeastReachedPC', 'jppeoples.LeastReachedPGAC',
        'jppeoples.GSEC', 'jppeoples.RLG3PC', 'jppeoples.RLG3PGAC', 'jppeoples.PrimaryReligionPC',
        'jppeoples.PrimaryReligionPGAC', 'jppeoples.RLG4', 'jppeoples.ReligionSubdivision', 'jppeoples.PCIslam',
        'jppeoples.PCNonReligious', 'jppeoples.PCUnknown', 'jppeoples.SecurityLevel', 'jppeoples.LRTop100',
        'jppeoples.ProfileTextExists', 'jppeoples.CountOfProvinces', 'jppeoples.IndigenousCode',
        'jppeoples.PercentChristianPC', 'jppeoples.PercentChristianPGAC', 'jppeoples.PercentEvangelicalPC',
        'jppeoples.PercentEvangelicalPGAC', 'jppeoples.PCBuddhism', 'jppeoples.PCEthnicReligions',
        'jppeoples.PCHinduism', 'jppeoples.PCOtherSmall', 'jppeoples.PopulationPGAC', 'jppeoples.Frontier',
        'jppeoples.MapAddress',
        'COALESCE(jppeoples.MapCreditURL, "") AS MapCreditURL', 'COALESCE(jppeoples.MapCopyright, "") AS MapCopyright',
        'COALESCE(jppeoples.MapCredits, "") AS MapCredits',
        'COALESCE(jppeoples.MapCCVersionText, "") AS MapCCVersionText',
        'COALESCE(jppeoples.MapCCVersionURL, "") AS MapCCVersionURL',
        'COALESCE(jppeoples.PhotoCCVersionText, "") AS PhotoCCVersionText',
        'COALESCE(jppeoples.PhotoCCVersionURL, "") AS PhotoCCVersionURL',
        // @deprecated These fields have been replaced by the above fields.
        'jpupgotd.JF', 'jpupgotd.AudioRecordings',
    ];
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
    protected $defaultOrderByStatement = 'ORDER BY jpupgotd.PeopleID1 ASC';
    /**
     * An array of table columns (key) and their alias (value).
     *
     * @var     array
     * @access  protected
     **/
    protected $aliasFields = ['jpupgotd.10_40Window'    =>  'Window1040'];
    /**
     * Construct the Unreached class.
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
        $this->selectFieldsStatement .= ", " .
        str_replace(
            'MapAddress',
            'jpupgotd.MapAddress',
            $this->peopleGroupMapURLSelect
        ) . " as PeopleGroupMapURL";
        $this->selectFieldsStatement .= ", " .
        str_replace(
            'MapAddressExpanded',
            'jpupgotd.MapAddressExpanded',
            $this->peopleGroupMapExpandedURLSelect
        ) . " as PeopleGroupMapExpandedURL";
        $this->selectFieldsStatement .= ", " .
        str_replace(
            ['PeopleID3', 'ROG3'],
            ['jpupgotd.PeopleID3', 'jpupgotd.ROG3'],
            $this->peopleGroupURLSelect
        ) . ' as PeopleGroupURL';
        $this->selectFieldsStatement .= ", " .
        str_replace(
            'PhotoAddress',
            'jpupgotd.PhotoAddress',
            $this->peopleGroupPhotoURLSelect
        ) . " as PeopleGroupPhotoURL";
        $this->selectFieldsStatement .= ", " .
        str_replace(
            'ROG3',
            'jpupgotd.ROG3',
            $this->countryURLSelect
        ) . " as CountryURL";
        $scaleTextStatement = $this->getScaleTextStatement('jpupgotd.JPScale');
        $scaleImageStatement = $this->getScaleImageURLStatement('jpupgotd.JPScale');
        $this->selectFieldsStatement .= ", $scaleTextStatement as JPScaleText";
        $this->selectFieldsStatement .= ", $scaleImageStatement as JPScaleImageURL";
    }
    /**
     * Find the daily unreached People Group.
     *
     * Find the daily unreached people Group based on the month and day that you specify.
     * <br><br><strong>Requires $providedParams['month']:</strong> The specific month of the unreached.
     * <br><strong>Requires $providedParams['day']:</strong> The specific month of the unreached.
     *
     * @return  void
     * @access  public
     * @throws  \InvalidArgumentException   If the 'month' key is not set or it is not between 1-12.
     * @throws  \InvalidArgumentException   If the 'day' key is not set or it is not between 1-31.
     * @author  Johnathan Pulos
     */
    public function daily(): void
    {
        $this->validator->providedRequiredParams($this->providedParams, ['month', 'day']);
        $set = 1;
        $month = intval($this->providedParams['month']);
        $day = intval($this->providedParams['day']);
        $this->validator->integerInRange($month, 1, 12);
        $this->validator->integerInRange($day, 1, 31);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM jpupgotd AS jpupgotd JOIN " .
            "jppeoples AS jppeoples ON jpupgotd.PeopleID3 = jppeoples.PeopleID3 WHERE " .
            "jpupgotd.LRofTheDayMonth = :month AND jpupgotd.LRofTheDayDay = :day AND ROL3Profile = 'eng' LIMIT 1";
        $this->preparedVariables = ['month' => $month, 'day' => $day];
    }
}
