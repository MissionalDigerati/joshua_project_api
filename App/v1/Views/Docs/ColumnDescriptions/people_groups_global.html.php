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
      <h2>People Group Across Countries (PGAC) Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
      <p>To learn more about People Groups Across Countries (PGAC) and People Groups in Countries (PGIC), please read <a href="https://joshuaproject.net/people_groups/counts" target="_blank">the following article</a>.</p>
    </div>
    <div id="table-column-descriptions" class="table-responsive">
        <table  class='table table-hover table-bordered'>
            <tbody>
                <tr>
                    <td>AffinityBloc</td>
                    <td>Affinity bloc name.</td>
                </tr>
                <tr>
                    <td>CntFPG</td>
                    <td>The total number of People Groups in Countries (PGIC) that are frontier unreached.</td>
                </tr>
                <tr>
                    <td>CntPGIC</td>
                    <td>The total number of countries where People Groups in Countries (PGIC) reside.</td>
                </tr>
                <tr>
                    <td>CntUPG</td>
                    <td>The total number of People Groups in Countries (PGIC) that are unreached.</td>
                </tr>
                <tr>
                    <td>CtryLargest</td>
                    <td>Country name for country with the largest population of this ethnicity.</td>
                </tr>
                <tr>
                    <td>FrontierPGAC</td>
                    <td>Is this ethnicity as a whole considered <a href="https://joshuaproject.net/frontier" target="_blank">frontier unreached</a>?</td>
                </tr>
                <tr>
                    <td>JPScalePGAC</td>
                    <td>Joshua Project Progress Scale at People Groups Across Countries (PGAC) level.</td>
                </tr>
                <tr>
                    <td>LeastReachedPGAC</td>
                    <td>Is this ethnicity as a whole considered unreached?</td>
                </tr>
                <tr>
                    <td>PeopleCluster</td>
                    <td>People Cluster name.</td>
                </tr>
                <tr>
                    <td>PeopleID1</td>
                    <td>Affinity bloc code.</td>
                </tr>
                <tr>
                    <td>PeopleID2</td>
                    <td>People cluster code.</td>
                </tr>
                <tr>
                    <td>PeopleID3</td>
                    <td>Unique ethnicity code.</td>
                </tr>
                <tr>
                    <td>PeopleName</td>
                    <td>General people name across all countries.</td>
                </tr>
                <tr>
                    <td>PercentChristianPGAC</td>
                    <td>Percent Christian Adherent of this ethnicity.</td>
                </tr>
                <tr>
                    <td>PercentEvangelicalPGAC</td>
                    <td>Percent Evangelical of this ethnicity.</td>
                </tr>
                <tr>
                    <td>PopulationPGAC</td>
                    <td>Total ethnicity population.</td>
                </tr>
                <tr>
                    <td>PrimaryLanguagePGAC</td>
                    <td>Primary language name of this ethnicity.</td>
                </tr>
                <tr>
                    <td>PrimaryReligionPGAC</td>
                    <td>Primary religion name of this ethnicity.</td>
                </tr>
                <tr>
                    <td>RLG3PGAC</td>
                    <td>Primary religion code of this ethnicity. [1 - Christianity, 2 - Buddhism,
                        4 - Ethnic Religions, 5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small,
                        9 - Unknown]</td>
                </tr>
                <tr>
                    <td>ROG3Largest</td>
                    <td>Country code for country with the largest population of this ethnicity.</td>
                </tr>
                <tr>
                    <td>ROL3PGAC</td>
                    <td>Primary language code of this ethnicity.</td>
                </tr>
                <tr>
                    <td>ROP25</td>
                    <td>Ethnic Kinship code.</td>
                </tr>
                <tr>
                    <td>ROP25Name</td>
                    <td>Ethnic Kinship name.</td>
                </tr>
                <tr>
                    <td>ROP3</td>
                    <td>Registry of Peoples ID.</td>
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
