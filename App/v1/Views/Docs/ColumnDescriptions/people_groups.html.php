<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_css_meta.html');
?>
  </head>
<body>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'nav.html');
?>
  <div class="container">
    <div class="page-header">
      <h2>People Group In Countries (PGIC) Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
      <p>To learn more about People Groups Across Countries (PGAC) and People Groups in Countries (PGIC), please read <a href="https://joshuaproject.net/people_groups/counts" target="_blank">the following article</a>.</p>
    </div>
    <div id="table-column-descriptions" class="table-responsive">
        <table  class='table table-hover table-bordered'>
            <tbody>
                <tr>
                    <td>AffinityBloc</td>
                    <td>Affinity Bloc</td>
                </tr>
                <tr>
                    <td>AudioRecordings</td>
                    <td><strong>DEPRECATED</strong> You can use HasAudioRecordings field. Will be removed June 16, 2025.</td>
                </tr>
                <tr>
                    <td>HasAudioRecordings</td>
                    <td>Gospel audio recordings exist in this language?</td>
                </tr>
                <tr>
                    <td>BibleStatus</td>
                    <td>
                        The current Bible status:
                        <ul>
                            <li>0 = Unspecified</li>
                            <li>1 = Translation needed</li>
                            <li>2 = Translation started</li>
                            <li>3 = Portions</li>
                            <li>4 = New Testament</li>
                            <li>5 = Complete Bible</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>BibleYear</td>
                    <td>Year of complete Bible availability</td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>Relationship between people groups and languages. Contact Joshua Project for meaning.</td>
                </tr>
                <tr>
                    <td>Continent</td>
                    <td>Continent</td>
                </tr>
                <tr>
                    <td>CountOfCountries</td>
                    <td>Number of countries this people group live in.</td>
                </tr>
                <tr>
                    <td>CountOfProvinces</td>
                    <td>Number of provinces this people group live in.</td>
                </tr>
                <tr>
                    <td>Ctry</td>
                    <td>Country name</td>
                </tr>
                <tr>
                    <td>Frontier</td>
                    <td>
                        Is this group considered a Frontier Unreached People Group? Learn more 
                        <a href="https://joshuaproject.net/frontier" target="_blank">here</a>
                    </td>
                </tr>
                <tr>
                    <td>GSEC</td>
                    <td>Global Status of Evangelical Christianity, see https://joshuaproject.net/definitions.php</td>
                </tr>
                <tr>
                    <td>HasJesusFilm</td>
                    <td>Does the people group have access to the Jesus Film?</td>
                </tr>
                <tr>
                    <td>HowReach</td>
                    <td>Some suggestions on how to reach this people group with the Gospel.</td>
                </tr>
                <tr>
                    <td>IndigenousCode</td>
                    <td>Is this group indigenous to this country?</td>
                </tr>
                <tr>
                    <td>ISO3</td>
                    <td>International Standards Organization country code</td>
                </tr>
                <tr>
                    <td>JF</td>
                    <td><strong>DEPRECATED</strong> You can use HasJesusFilm field. Will be removed June 16, 2025.</td>
                </tr>
                <tr>
                    <td>JPScale</td>
                    <td>Joshua Project Progress Scale for this people group.</td>
                </tr>
                <tr>
                    <td>JPScalePC</td>
                    <td>Joshua Project Progress Scale for People Cluster.</td>
                </tr>
                <tr>
                    <td>JPScalePGAC</td>
                    <td>Joshua Project Progress Scale for this people group across all countries.</td>
                </tr>
                <tr>
                    <td>Latitude</td>
                    <td>Latitude value of language polygon or highest density district centroid, for Google maps
                        colored dots</td>
                </tr>
                <tr>
                    <td>LeastReached</td>
                    <td>Is this people group considered least-reached / unreached? [JPScale < 2.0]</td>
                </tr>
                <tr>
                    <td>LeastReachedPC</td>
                    <td>Is People Cluster considered least-reached / unreached? [JPScale < 2.0]</td>
                </tr>
                <tr>
                    <td>LeastReachedPGAC</td>
                    <td>Is this people group across all countries considered least-reached / unreached?
                        [JPScale < 2.0]</td>
                </tr>
                <tr>
                    <td>LocationInCountry</td>
                    <td>Location of people within the country</td>
                </tr>
                <tr>
                    <td>Longitude</td>
                    <td>Longitude value of language polygon or highest density district centroid, for Google maps
                        colored dots</td>
                </tr>
                <tr>
                    <td>LRofTheDayDay</td>
                    <td>Unreached People of the Day day 1-31</td>
                </tr>
                <tr>
                    <td>LRofTheDayMonth</td>
                    <td>Unreached People of the Day month 1-12</td>
                </tr>
                <tr>
                    <td>LRofTheDaySet</td>
                    <td>Unreached People of the Day set 1 or 2</td>
                </tr>
                <tr>
                    <td>LRTop100</td>
                    <td>Are they in top 100 of least-reached?</td>
                </tr>
                <tr>
                    <td>LRWebProfile</td>
                    <td>Does an Unreached People of the Day profile exist?</td>
                </tr>
                <tr>
                    <td>MapAddress</td>
                    <td>Map filename</td>
                </tr>
                <tr>
                    <td>MapCredits</td>
                    <td>Map source, text for credits display</td>
                </tr>
                <tr>
                    <td>MapCreditURL</td>
                    <td>Map source link, hyperlink for credits display</td>
                </tr>
                <tr>
                    <td>MapCopyright</td>
                    <td>Is the map copyrighted? NOTE: It can return an empty string.</td>
                </tr>
                <tr>
                    <td>MapCCVersionText</td>
                    <td>
                        If the map is licensed under Creative Commons,
                         this provides the name of the version of Creative Commons
                    </td>
                </tr>
                <tr>
                    <td>MapCCVersionURL</td>
                    <td>
                        If the map is licensed under Creative Commons,
                         this provides the URL for the version of Creative Commons
                    </td>
                </tr>
                <tr>
                    <td>NaturalName</td>
                    <td>The PeopNameAcrossCountries presented in a more display friendly way.</td>
                </tr>
                <tr>
                    <td>NaturalPronunciation</td>
                    <td>The pronunciation of the natural name.</td>
                </tr>
                <tr>
                    <td>NTOnline</td>
                    <td>Does Bible.is have an online NT?</td>
                </tr>
                <tr>
                    <td>NTYear</td>
                    <td>Year of New Testament availability</td>
                </tr>
                <tr>
                    <td>NumberLanguagesSpoken</td>
                    <td>Number of languages spoken by this people group in this country</td>
                </tr>
                <tr>
                    <td>Obstacles</td>
                    <td>Describes obstacles in ministry to this people group.</td>
                </tr>
                <tr>
                    <td>OfficialLang</td>
                    <td>Official language name</td>
                </tr>
                <tr>
                    <td>PercentAdherents</td>
                    <td>Percent of people in group who are Christian Adherents</td>
                </tr>
                <tr>
                    <td>PercentAdherentsPC</td>
                    <td>Percent of people in group who are Christian Adherents in this people cluster</td>
                </tr>
                <tr>
                    <td>PercentAdherentsPGAC</td>
                    <td>Percent of people in group who are Christian Adherents in all people groups across all
                        countries</td>
                </tr>
                <tr>
                    <td>PCBuddhism</td>
                    <td>Percent of people in group who are Buddhist</td>
                </tr>
                <tr>
                    <td>PCEthnicReligions</td>
                    <td>Percent of people in group who practice Ethnic Religions</td>
                </tr>
                <tr>
                    <td>PercentAdherentsPGAC</td>
                    <td>Percent of people in group who are Christian Adherents in all people groups across all
                        countries</td>
                </tr>
                <tr>
                    <td>PercentEvangelical</td>
                    <td>Percent of people in group who are Evangelical</td>
                </tr>
                <tr>
                    <td>PercentEvangelicalPC</td>
                    <td>Percent of people in group who are Evangelicals in this people cluster</td>
                </tr>
                <tr>
                    <td>PercentEvangelicalPGAC</td>
                    <td>Percent of people in group who are Evangelicals in all people groups across all
                        countries</td>
                </tr>
                <tr>
                    <td>PCHinduism</td>
                    <td>Percent of people in group who are Hindu</td>
                </tr>
                <tr>
                    <td>PCIslam</td>
                    <td>Percent of people in group who are Muslim</td>
                </tr>
                <tr>
                    <td>PCNonReligious</td>
                    <td>Percent of people in group who are  Non-religious</td>
                </tr>
                <tr>
                    <td>PCOtherSmall</td>
                    <td>Percent of people in group who practice Other or Smaller Religions</td>
                </tr>
                <tr>
                    <td>PCUnknown</td>
                    <td>Percent of people in group who's religious background is unknown</td>
                </tr>
                <tr>
                    <td>PeopleCluster</td>
                    <td>People cluster</td>
                </tr>
                <tr>
                    <td>PeopleID1</td>
                    <td>Affinity Bloc code</td>
                </tr>
                <tr>
                    <td>PeopleID2</td>
                    <td>People cluster code</td>
                </tr>
                <tr>
                    <td>PeopleID3</td>
                    <td>People-Group-Across-Countries ID number</td>
                </tr>
                <tr>
                    <td>PeopleID3ROG3</td>
                    <td>An ID created by concatenating the PeopleID3 (People code) and ROG3 (Country code). This provides an ID for a specific people group in a specific country.</td>
                </tr>
                <tr>
                    <td>PeopleGroupMapURL</td>
                    <td>The full URL for the people group's map</td>
                </tr>
                <tr>
                    <td>PeopleGroupMapExpandedURL</td>
                    <td>The full URL for the people group's expanded map (NOTE: This is normally a PDF file)</td>
                </tr>
                <tr>
                    <td>PeopleGroupPhotoURL</td>
                    <td>The full URL for the people group's photo</td>
                </tr>
                <tr>
                    <td>PeopNameAcrossCountries</td>
                    <td>Name of people group across countries of residence</td>
                </tr>
                <tr>
                    <td>PeopNameInCountry</td>
                    <td>Name of people group in this country</td>
                </tr>
                <tr>
                    <td>PCEvangelicalPC</td>
                    <td>Percent of people in group who are Evangelical in this people cluster</td>
                </tr>
                <tr>
                    <td>PCEvangelicalPGAC</td>
                    <td>Percent of people in group who are Evangelical in all people groups across all countries</td>
                </tr>
                <tr>
                    <td>PhotoAddress</td>
                    <td>Photo file name</td>
                </tr>
                <tr>
                    <td>PhotoCCVersionText</td>
                    <td>
                        If the photo is licensed under Creative Commons (see PhotoCreativeCommons),
                         this provides the name of the version of Creative Commons
                    </td>
                </tr>
                <tr>
                    <td>PhotoCCVersionURL</td>
                    <td>
                        If the photo is licensed under Creative Commons (see PhotoCreativeCommons),
                         this provides the URL for the version of Creative Commons
                    </td>
                </tr>
                <tr>
                    <td>PhotoCopyright</td>
                    <td>Is photo copyrighted?</td>
                </tr>
                <tr>
                    <td>PhotoCreativeCommons</td>
                    <td>Does photo have creative commons licensing?</td>
                </tr>
                <tr>
                    <td>PhotoCredits</td>
                    <td>Photo source, text for credits display</td>
                </tr>
                <tr>
                    <td>PhotoCreditURL</td>
                    <td>Photo source link, hyperlink for credits display</td>
                </tr>
                <tr>
                    <td>PhotoPermission</td>
                    <td>Does Joshua Project have permission to use this photo?</td>
                </tr>
                <tr>
                    <td>Population</td>
                    <td>Population of people group in the given country</td>
                </tr>
                <tr>
                    <td>PopulationPGAC</td>
                    <td>The total global population of the people group</td>
                </tr>
                <tr>
                    <td>PortionsYear</td>
                    <td>Year of scripture portions availability</td>
                </tr>
                <tr>
                    <td>PrayForChurch</td>
                    <td>Suggested prayers for the church among this people group.</td>
                </tr>
                <tr>
                    <td>PrayForPG</td>
                    <td>Suggested prayers for the specific people group.</td>
                </tr>
                <tr>
                    <td>PrimaryLanguageDialect</td>
                    <td>Primary language dialect in this country</td>
                </tr>
                <tr>
                    <td>PrimaryLanguageName</td>
                    <td>Primary language of the people group in this country</td>
                </tr>
                <tr>
                    <td>PrimaryReligion</td>
                    <td>Primary religion of the people group in this country [1 - Christianity, 2 - Buddhism,
                        4 - Ethnic Religions, 5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small,
                        9 - Unknown]</td>
                </tr>
                <tr>
                    <td>PrimaryReligionPC</td>
                    <td>Primary religion of the peole group in this people cluster</td>
                </tr>
                <tr>
                    <td>PrimaryReligionPGAC</td>
                    <td>Primary religion of the people group in all people groups across all countries</td>
                </tr>
                <tr>
                    <td>ProfileTextExists</td>
                    <td>Does profile text exist for this people group?</td>
                </tr>
                <tr>
                    <td>RegionCode</td>
                    <td>Region code for this people group [1 - South Pacific, 2 - Southeast Asia, 3 - Northeast Asia,
                        4 - South Asia, 5 - Central Asia, 6 - Middle East and North Africa,
                        7 - East and Southern Africa, 8 - West and Central Africa, 9 - Eastern Europe and Eurasia,
                        10 - Western Europe, 11 - Central and South America, 12 - North America and Caribbean]</td>
                </tr>
                <tr>
                    <td>RegionName</td>
                    <td>Region name</td>
                </tr>
                <tr>
                    <td>ReligionSubdivision</td>
                    <td>Subdivision of the primary religion</td>
                </tr>
                <tr>
                    <td>RLG3</td>
                    <td>Primary religion code</td>
                </tr>
                <tr>
                    <td>RLG3PC</td>
                    <td>Primary Religion Code of the peole group in this people cluster</td>
                </tr>
                <tr>
                    <td>RLG3PGAC</td>
                    <td>Primary Religion Code of the people group in all people groups across all countries</td>
                </tr>
                <tr>
                    <td>RLG4</td>
                    <td>Religion subdivision code</td>
                </tr>
                <tr>
                    <td>ROG2</td>
                    <td>HIS Registry of Geography (ROG) continent code</td>
                </tr>
                <tr>
                    <td>ROG3</td>
                    <td>HIS Registry of Geography (ROG) country code, similar to 2 letter ISO country code</td>
                </tr>
                <tr>
                    <td>ROL3</td>
                    <td>Ethnologue language code, 17th Edition</td>
                </tr>
                <tr>
                    <td>ROP1</td>
                    <td>Registry of Peoples - affinity bloc code</td>
                </tr>
                <tr>
                    <td>ROP2</td>
                    <td>Registry of Peoples - people cluster code</td>
                </tr>
                <tr>
                    <td>ROP3</td>
                    <td>Registry of Peoples - people group code</td>
                </tr>
                <tr>
                    <td>SecurityLevel</td>
                    <td>0=Open, 1=Moderate security concerns, 2= Significant security concerns</td>
                </tr>
                <tr>
                    <td>SpeakNationalLang</td>
                    <td>Does this group speak the national language?</td>
                </tr>
                <tr>
                    <td>Summary</td>
                    <td>
                        A brief description about the people group. If you display on your website, please add a
                         Read More link that connects back to our website.
                    </td>
                </tr>
                <tr>
                    <td>TranslationNeedQuestionable</td>
                    <td>Is the need for translation in this language questionable?</td>
                </tr>
                <tr>
                    <td>Window1040</td>
                    <td>Does this people group live in the 1040 Window?</td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'footer.html');
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_footer_js.html');
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('li.documentation-nav, li.people-groups-column-desc-nav').addClass('active');
            });
        </script>
</body>
</html>
