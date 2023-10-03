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
      <h2>Country Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
    </div>
    <div id="table-column-descriptions" class="table-responsive">
        <table class='table table-hover table-bordered'>
            <tbody>
                <tr>
                    <td>BibleComplete</td>
                    <td>
                        Of the total Primary Languages (CntPrimaryLanguages), how many of them have a
                         Bible Status of <strong>Complete Bible</strong>.
                    </td>
                </tr>
                <tr>
                    <td>BibleNewTestament</td>
                    <td>
                        Of the total Primary Languages (CntPrimaryLanguages), how many of them have a
                         Bible Status of <strong>New Testament</strong>.
                    </td>
                </tr>
                <tr>
                    <td>BiblePortions</td>
                    <td>
                        Of the total Primary Languages (CntPrimaryLanguages), how many of them have a
                         Bible Status of <strong>Portions</strong>.
                    </td>
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
                    <td>CntPrimaryLanguages</td>
                    <td>The total number of primary languages in the country.</td>
                </tr>
                <tr>
                    <td>Ctry</td>
                    <td>Name of Country</td>
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
                    <td>PctChristianDoublyProfessing</td>
                    <td>Percent of people in this country who are Doubly Professing Christians</td>
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
                    <td>PercentIslam</td>
                    <td>Percent of people in this country who are Muslim</td>
                </tr>
                <tr>
                    <td>Population</td>
                    <td>Country Population</td>
                </tr>
                <tr>
                    <td>PoplPeoplesFPG</td>
                    <td>Total population living in frontier people groups of the country.</td>
                </tr>
                <tr>
                    <td>PoplPeoplesLR</td>
                    <td>Total population living in unreached people groups of the country.</td>
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
                    <td>SecurityLevel</td>
                    <td>0 = no security concerns;  1 = moderate security concerns, 2 = high security concerns</td>
                </tr>
                <tr>
                    <td>TranslationNeeded</td>
                    <td>
                        Of the total Primary Languages (CntPrimaryLanguages), how many of them have a
                         Bible Status of <strong>Translation Needed</strong>.
                    </td>
                </tr>
                <tr>
                    <td>TranslationStarted</td>
                    <td>
                        Of the total Primary Languages (CntPrimaryLanguages), how many of them have a
                         Bible Status of <strong>Translation Started</strong>.
                    </td>
                </tr>
                <tr>
                    <td>TranslationUnspecified</td>
                    <td>
                        Of the total Primary Languages (CntPrimaryLanguages), how many of them have a
                         Bible Status of <strong>Unspecified</strong>.
                    </td>
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
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'footer.html');
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_footer_js.html');
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('li.documentation-nav, li.countries-column-desc-nav').addClass('active');
            });
        </script>
</body>
</html>
