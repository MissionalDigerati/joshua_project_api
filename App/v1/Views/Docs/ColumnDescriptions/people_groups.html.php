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
      <h2>People Group Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
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
                    <td>Gospel audio recordings exist in this language?</td>
                </tr>
                <tr>
                    <td>BibleStatus</td>
                    <td>Bible status</td>
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
                    <td>EthnolinguisticMap</td>
                    <td>Ethnolinguistic map URL</td>
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
                    <td>Global Status of Evangelical Christianity, see http://www.joshuaproject.net/definitions.php</td>
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
                    <td>Does the Jesus film exists in this language?</td>
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
                    <td>PCDblyProfessing</td>
                    <td>Percent of people in group who are Doubly Professing.</td>
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
                    <td>PhotoHeight</td>
                    <td>Photo height</td>
                </tr>
                <tr>
                    <td>PhotoPermission</td>
                    <td>Does Joshua Project have permission to use this photo?</td>
                </tr>
                <tr>
                    <td>PhotoWidth</td>
                    <td>Photo width</td>
                </tr>
                <tr>
                    <td>Population</td>
                    <td>Population of people group in the given country</td>
                </tr>
                <tr>
                    <td>PopulationPercentUN</td>
                    <td>Population percent of UN country population</td>
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
                    <td>RaceCode</td>
                    <td>Ethnicity code from WCD data</td>
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
                    <td>ROL3OfficialLanguage</td>
                    <td>Official language code</td>
                </tr>
                <tr>
                    <td>ROL4</td>
                    <td>Dialect code</td>
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
