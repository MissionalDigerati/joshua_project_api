<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
<?php
    include($VIEW_DIRECTORY . '/Partials/site_wide_css_meta.html');
?>
  </head>
<body>
<?php
    include($VIEW_DIRECTORY . '/Partials/nav.html');
?>
  <div class="container">
    <div class="page-header">
      <h2>Country Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
    </div>
    <div id="table-column-descriptions" class="table-responsive">
        <table class='table table-hover table-bordered'>
            <tbody>
                <tr>
                    <td>AltName</td>
                    <td>Alternate Name for Country</td>
                </tr>
                <tr>
                    <td>AreaSquareKilometers</td>
                    <td>Area of Country in Square Kilometres</td>
                </tr>
                <tr>
                    <td>AreaSquareMiles</td>
                    <td>Area of Country in Square Miles</td>
                </tr>
                <tr>
                    <td>Capital</td>
                    <td>Name of Capital City</td>
                </tr>
                <tr>
                    <td>Continent</td>
                    <td>The Continent code [AFR - Africa, ASI - Asia, AUS - Australia, EUR - Europe, NAR - North America, SOP - Oceania (South Pacific), LAM - South America]</td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>Name of Country</td>
                </tr>
                <tr>
                    <td>CountryNotes</td>
                    <td>Country Notes</td>
                </tr>
                <tr>
                    <td>CountryPhoneCode</td>
                    <td>Country Phone Code</td>
                </tr>
                <tr>
                    <td>HDIRank</td>
                    <td>Human Development Index - ranking (2003)</td>
                </tr>
                <tr>
                    <td>HDIValue</td>
                    <td>Human Development Index - composite value: combination of LifeExpectancy, Education and GPD Indexs (2003)</td>
                </tr>
                <tr>
                    <td>HDIYear</td>
                    <td>Human Development Index - year (2003)</td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td>2 digit ISO Country Code</td>
                </tr>
                <tr>
                    <td>InternetCountryCode</td>
                    <td>Internet Country Code</td>
                </tr>
                <tr>
                    <td>IsCountry</td>
                    <td>Is this  a country?</td>
                </tr>
                <tr>
                    <td>ISO2</td>
                    <td>ISO 2 character code for country</td>
                </tr>
                <tr>
                    <td>ISO3</td>
                    <td>ISO 3 character code for country</td>
                </tr>
                <tr>
                    <td>JPScale</td>
                    <td>Joshua Project Progress Scale</td>
                </tr>
                <tr>
                    <td>LibraryCongressReportExists</td>
                    <td>Library of Congress Country Study exists for this country</td>
                </tr>
                <tr>
                    <td>LiteracyCategory</td>
                    <td>LT1=0-20%, LT2=21-40%, LT3=41-60%, LT4=61-80%, LT5=81-100%</td>
                </tr>
                <tr>
                    <td>LiteracyRange</td>
                    <td>Literacy range</td>
                </tr>
                <tr>
                    <td>LiteracyRate</td>
                    <td>One percentage. Midpoint if a range was supplied.</td>
                </tr>
                <tr>
                    <td>LiteracySource</td>
                    <td>Literacy source</td>
                </tr>
                <tr>
                    <td>PCAnglican</td>
                    <td>Percent of people in group who are Christian Anglican</td>
                </tr>
                <tr>
                    <td>PCBuddhist</td>
                    <td>Percent of people in group who are Buddhist</td>
                </tr>
                <tr>
                    <td>PCChristianity</td>
                    <td>Percent of people in group who are Christians</td>
                </tr>
                <tr>
                    <td>PCEthnicReligion</td>
                    <td>Percent of people in group who practice Ethnic Religions</td>
                </tr>
                <tr>
                    <td>PCEvangelical</td>
                    <td>Percent of people in group who are Evangelical</td>
                </tr>
                <tr>
                    <td>PCHindu</td>
                    <td>Percent of people in group who are Hindu</td>
                </tr>
                <tr>
                    <td>PCIndependent</td>
                    <td>Percent of people in group who are Independent</td>
                </tr>
                <tr>
                    <td>PCIslam</td>
                    <td>Percent of people in group who are Muslim</td>
                </tr>
                <tr>
                    <td>PCNonReligious</td>
                    <td>Percent of people in group who are Non-Religious</td>
                </tr>
                <tr>
                    <td>PCOrthodox</td>
                    <td>Percent of people in group who are Orthodox</td>
                </tr>
                <tr>
                    <td>PCOtherChristian</td>
                    <td>Percent of people in group who are another forms of Christian</td>
                </tr>
                <tr>
                    <td>PCOtherReligion</td>
                    <td>Percent of people in group who practice Other or Smaller Religions</td>
                </tr>
                <tr>
                    <td>PCProtestant</td>
                    <td>Percent of people in group who are Protestant</td>
                </tr>
                <tr>
                    <td>PCRCatholic</td>
                    <td>Percent of people in group who are Roman Catholic</td>
                </tr>
                <tr>
                    <td>PctChristianDoublyProfessing</td>
                    <td>Percent of people in group who are Doubly Professing Christians</td>
                </tr>
                <tr>
                    <td>PCUnknown</td>
                    <td>Percent of people in group who's religious background is unknown</td>
                </tr>
                <tr>
                    <td>PercentUrbanized</td>
                    <td>Percent of urbanized population</td>
                </tr>
                <tr>
                    <td>PoplGrowthRate</td>
                    <td>Population Annual Growth Rate</td>
                </tr>
                <tr>
                    <td>Population</td>
                    <td>Country Population</td>
                </tr>
                <tr>
                    <td>PopulationPerSquareMile</td>
                    <td>Population Per Square Mile</td>
                </tr>
                <tr>
                    <td>PopulationSource</td>
                    <td>Population Source</td>
                </tr>
                <tr>
                    <td>PrayercastVideo</td>
                    <td>Prayercast URL (Prayercast website)</td>
                </tr>
                <tr>
                    <td>PrimaryLanguage</td>
                    <td>The ISO 3 Letter Code for the Official language of country</td>
                </tr>
                <tr>
                    <td>PrimaryReligion</td>
                    <td>Primary religion of the people group in this country [1 - Christianity, 2 - Buddhism, 4 - Ethnic Religions, 5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small, 9 - Unknown]</td>
                </tr>
                <tr>
                    <td>Region</td>
                    <td>Region code for this country [1 - South Pacific, 2 - Southeast Asia, 3 - Northeast Asia, 4 - South Asia, 5 - Central Asia, 6 - Middle East and North Africa, 7 - East and Southern Africa, 8 - West and Central Africa, 9 - Eastern Europe and Eurasia, 10 - Western Europe, 11 - Central and South America, 12 - North America and Caribbean]</td>
                </tr>
                <tr>
                    <td>RegionName</td>
                    <td>The Region's Name</td>
                </tr>
                <tr>
                    <td>ReligionDataYear</td>
                    <td>Religion Data Year</td>
                </tr>
                <tr>
                    <td>PrimaryReligionText</td>
                    <td>The text of the primary religion</td>
                </tr>
                <tr>
                    <td>RLG3</td>
                    <td>Primary religion of the people group in this country [1 - Christianity, 2 - Buddhism, 4 - Ethnic Religions, 5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small, 9 - Unknown]</td>
                </tr>
                <tr>
                    <td>RLG4</td>
                    <td>Primary Religion sub-division for this country</td>
                </tr>
                <tr>
                    <td>ROG2</td>
                    <td>Continent code</td>
                </tr>
                <tr>
                    <td>ROG3</td>
                    <td>2 letter ISO country code</td>
                </tr>
                <tr>
                    <td>SecondaryLanguage</td>
                    <td>The ISO 3 Letter Code for the Secondary language of country</td>
                </tr>
                <tr>
                    <td>SecurityLevel</td>
                    <td>0 = no security concerns;  1 = moderate security concerns, 2 = high security concerns</td>
                </tr>
                <tr>
                    <td>Source</td>
                    <td>Original Source of Information</td>
                </tr>
                <tr>
                    <td>StateDeptReligiousFreedom</td>
                    <td>Code for URL link to current Report on International Religious Freedom</td>
                </tr>
                <tr>
                    <td>StonyGround</td>
                    <td>Is this country stony ground for evangelism? If "Y" the default for all people groups in this country is "Least-Reached"</td>
                </tr>
                <tr>
                    <td>UNMap</td>
                    <td>Link to UN Political map in PDF format</td>
                </tr>
                <tr>
                    <td>USAPostalSystem</td>
                    <td>Is this country covered by the U.S. Postal Service?</td>
                </tr>
                <tr>
                    <td>WINCountryProfile</td>
                    <td>Window Internationial Network country profile link</td>
                </tr>
                <tr>
                    <td>Window1040</td>
                    <td>Is this country in the 1040 Window?</td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
<?php
    include($VIEW_DIRECTORY . '/Partials/footer.html');
    include($VIEW_DIRECTORY . '/Partials/site_wide_footer_js.html');
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('li.documentation-nav, li.countries-column-desc-nav').addClass('active');
            });
        </script>
</body>
</html>