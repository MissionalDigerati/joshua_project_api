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
 * These queries specifically work with the people group ProfileText data.
 *
 * @package default
 * @author Johnathan Pulos
 **/
class ProfileText extends QueryGenerator
{
    /**
     * An array of column names for this database table that we want to select in searches.  Simply remove fields you do not want to expose.
     *
     * @var array
     * @access protected
     */
    protected $fieldsToSelectArray = array('jpprofiletext.ProfileID', 'jpprofiletext.ROL3', 'jpprofiletext.Active', 'jpprofiletext.Format', 'jpprofiletext.FileName', 'jpprofiletext.IntroductionHistory', 'jpprofiletext.WhereLocated', 'jpprofiletext.LivesLike', 'jpprofiletext.Beliefs', 'jpprofiletext.Needs', 'jpprofiletext.Prayer', 'jpprofiletext.Reference', 'jpprofiletext.Summary', 'jpprofiletext.ScriptureFocus', 'jpprofiletext.Obstacles', 'jpprofiletext.HowReach', 'jpprofiletext.PrayForChurch', 'jpprofiletext.PrayForPG', 'jpprofiletext.Identity', 'jpprofiletext.History', 'jpprofiletext.Customs', 'jpprofiletext.Religion', 'jpprofiletext.Christianity', 'jpprofiletext.Comments', 'jpprofiletext.Copyright', 'jpprofiletext.Permission', 'jpprofiletext.CreativeCommons', 'jpprofiletext.Credits', 'jpprofiletext.CreditsURL');
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
     * Find the People Group ProfileTexts using the id (PeopleID3), and the country (ROG3)
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function findAllByIdAndCountry()
    {
        $this->validator->providedRequiredParams($this->providedParams, array('id', 'country'));
        $id = intval($this->providedParams['id']);
        $country = strtoupper($this->providedParams['country']);
        $this->preparedStatement = "SELECT " . $this->selectFieldsStatement . " FROM jpprofiletopeople JOIN jpprofiletext ON jpprofiletopeople.ProfileID = jpprofiletext.ProfileID WHERE jpprofiletopeople.PeopleID3 = :id AND jpprofiletopeople.ROG3 = :country";
        $this->preparedVariables = array('id' => $id, 'country' => $country);
    }
}
