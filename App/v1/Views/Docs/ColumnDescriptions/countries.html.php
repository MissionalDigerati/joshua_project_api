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
                    <td>AreaSquareMiles</td>
                    <td>Area of Country in Square Miles</td>
                </tr>
                <tr>
                    <td>Capital</td>
                    <td>Name of Capital City</td>
                </tr>
                <tr>
                    <td>CntPeoples</td>
                    <td>Total Number of People Groups</td>
                </tr>
                <tr>
                    <td>CntPeoplesLR</td>
                    <td>Total Number of Unreached People Groups</td>
                </tr>
                <tr>
                    <td>Ctry</td>
                    <td>Name of Country</td>
                </tr>
                <tr>
                    <td>HDIRank</td>
                    <td>Human Development Index - ranking</td>
                </tr>
                <tr>
                    <td>HDIValue</td>
                    <td>Human Development Index - composite value: combination of LifeExpectancy,
                        Education and GPD Indexs</td>
                </tr>
                <tr>
                    <td>HDIYear</td>
                    <td>Human Development Index - year</td>
                </tr>
                <tr>
                    <td>InternetCtryCode</td>
                    <td>Internet Country Code</td>
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
                    <td>JPScaleCtry</td>
                    <td>Joshua Project Progress Scale for this country, derived from the people group data in
                        this country.</td>
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
                    <td>PercentBuddhism</td>
                    <td>Percent of people in this country who are Buddhist</td>
                </tr>
                <tr>
                    <td>PercentChristianity</td>
                    <td>Percent of people in this country who are Christians</td>
                </tr>
                <tr>
                    <td>PercentEthnicReligions</td>
                    <td>Percent of people in this country who practice Ethnic Religions</td>
                </tr>
                <tr>
                    <td>PercentEvangelical</td>
                    <td>Percent of people in this country who are Evangelical</td>
                </tr>
                <tr>
                    <td>PercentHinduism</td>
                    <td>Percent of people in this country who are Hindu</td>
                </tr>
                <tr>
                    <td>PercentIndependent</td>
                    <td>Percent of people in this country who are Independent</td>
                </tr>
                <tr>
                    <td>PercentIslam</td>
                    <td>Percent of people in this country who are Muslim</td>
                </tr>
                <tr>
                    <td>PercentNonReligious</td>
                    <td>Percent of people in this country who are Non-Religious</td>
                </tr>
                <tr>
                    <td>PercentOrthodox</td>
                    <td>Percent of people in this country who are Orthodox</td>
                </tr>
                <tr>
                    <td>PercentOther</td>
                    <td>Percent of people in this country who are another forms of Christian</td>
                </tr>
                <tr>
                    <td>PercentOtherSmall</td>
                    <td>Percent of people in this country who practice Other or Smaller Religions</td>
                </tr>
                <tr>
                    <td>PercentProtestant</td>
                    <td>Percent of people in this country who are Protestant</td>
                </tr>
                <tr>
                    <td>PercentRomanCatholic</td>
                    <td>Percent of people in this country who are Roman Catholic</td>
                </tr>
                <tr>
                    <td>PctChristianDoublyProfessing</td>
                    <td>Percent of people in this country who are Doubly Professing Christians</td>
                </tr>
                <tr>
                    <td>PercentUnknown</td>
                    <td>Percent of people in this country who's religious background is unknown</td>
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
                    <td>PopulationSource</td>
                    <td>Population Source</td>
                </tr>
                <tr>
                    <td>PrayercastVideo</td>
                    <td>Prayercast URL (Prayercast website)</td>
                </tr>
                <tr>
                    <td>PrimaryReligion</td>
                    <td>Primary religion for this country [1 - Christianity, 2 - Buddhism, 4 - Ethnic Religions,
                        5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small, 9 - Unknown]</td>
                </tr>
                <tr>
                    <td>RegionCode</td>
                    <td>Region code for this country [1 - South Pacific, 2 - Southeast Asia, 3 - Northeast Asia,
                        4 - South Asia, 5 - Central Asia, 6 - Middle East and North Africa,
                        7 - East and Southern Africa, 8 - West and Central Africa, 9 - Eastern Europe and Eurasia,
                        10 - Western Europe, 11 - Central and South America, 12 - North America and Caribbean]</td>
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
                    <td>ReligionPrimary</td>
                    <td>The text of the primary religion</td>
                </tr>
                <tr>
                    <td>RLG3Primary</td>
                    <td>Primary religion of the people group in this country [1 - Christianity, 2 - Buddhism,
                        4 - Ethnic Religions, 5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small,
                        9 - Unknown]</td>
                </tr>
                <tr>
                    <td>RLG4Primary</td>
                    <td>Primary Religion sub-division for this country</td>
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
                    <td>ROL3OfficialLanguage</td>
                    <td>The ISO 3 Letter Code for the Official language of country</td>
                </tr>
                <tr>
                    <td>ROL3SecondaryLanguage</td>
                    <td>The ISO 3 Letter Code for the Secondary language of country</td>
                </tr>
                <tr>
                    <td>SecurityLevel</td>
                    <td>0 = no security concerns;  1 = moderate security concerns, 2 = high security concerns</td>
                </tr>
                <tr>
                    <td>StateDeptReligiousFreedom</td>
                    <td>Code for URL link to current Report on International Religious Freedom</td>
                </tr>
                <tr>
                    <td>UNMap</td>
                    <td>Link to UN Political map in PDF format</td>
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
