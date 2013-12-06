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
    protected $fieldsToSelectArray = array('ROG3','ISO3','ISO2','ROG2','RegionCode','RegionName','AltName','Capital','Population','PopulationSource','PoplGrowthRate','AreaSquareMiles','AreaSquareKilometers','PopulationPerSquareMile','CountryPhoneCode','SecurityLevel','LibraryCongressReportExists','IsCountry','BJMFocusCountry','StonyGround','USAPostalSystem','ROL3OfficialLanguage','OfficialLang','ROL3SecondaryLanguage','SecondLang','RLG3Primary','ReligionPrimary','RLG4Primary','ReligionSubdivision','ReligionDataYear','LiteracyCategory','LiteracyRate','LiteracyRange','LiteracySource','CountryNotes','NSMMissionArticles','EthnolinguisticMap','PercentPopulationDifference','PercentChristianity','PercentEvangelical','PercentBuddhism','PercentDoublyProfessing','PercentEthnicReligions','PercentHinduism','PercentIslam','PercentNonReligious','PercentOtherSmall','PercentUnknown','PercentAnglican','PercentIndependent','PercentProtestant','PercentOrthodox','PercentOther','PercentRomanCatholic','CntPeoples','PoplPeoples','CntPeoplesLR','PoplPeoplesLR','JPScaleCtry','HDIYear','HDIValue','HDIRank','StateDeptReligiousFreedom','Source','EditName','EditDate','V59Country','EthnologueCountryCode','EthnologueMapExists','UNMap','PersecutionRankingOD','InternetCtryCode','PercentUrbanized','PrayercastVideo','PrayercastBlipTVCode','WINCountryProfile');
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
        $this->selectFieldsStatement .= ", Ctry as Country, Ctry_id as Country_ID";
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
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM " . $this->tableName . " " . $this->defaultOrderByStatement . " LIMIT 100";
        $this->preparedVariables = array();
    }
}
