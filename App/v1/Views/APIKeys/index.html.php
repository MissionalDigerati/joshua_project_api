<?php
/**
 * Joshua Project API - An API for accessing Joshua Project Data.
 *
 * GNU Public License 3.0
 * Copyright (C) 2013  Missional Digerati
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Joshua Project API</title>
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
            <h2>Existing API Keys</h2>
        </div>
<?php
if ((isset($data['saving_error'])) && ($data['saving_error'] == "true")) {
    ?>
    <div class="alert alert-danger">
        Your request has had an error!  Please try again.
    </div>
    <?php
} elseif ((isset($data['saved'])) && ($data['saved'] == "true")) {
    ?>
    <div class="alert alert-success">
        The API Key has been <?php echo $data['key_state']; ?>!
    </div>
    <?php
}
?>
        <table class="table table-bordered table-condensed table-hover">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Name</th>
                    <th>Contact Info</th>
                    <th>API Key</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
<?php
foreach ($api_keys as $api_key) {
    ?>
    <tr>
        <td>
    <?php
    if ($api_key['status'] == 0) {
        echo "<span class='label label-warning'>Pending</span>";
    } elseif ($api_key['status'] == 1) {
        echo "<span class='label label-success'>Activated</span>";
    } elseif ($api_key['status'] == 2) {
        echo "<span class='label label-danger'>Suspended</span>";
    }
    ?>
        </span></td>
        <td><?php echo $api_key['name']; ?><br>
            <em><?php echo $api_key['organization']; ?></em>
        </td>
        <td><?php echo $api_key['email']; ?><br>
    <?php
    if (!empty($api_key['phone_number'])) {
        echo "(" . substr($api_key['phone_number'], 0, 3). ") " .
            substr($api_key['phone_number'], 3, 3) . "-" .
            substr($api_key['phone_number'], 6, 4);
    }
    ?>
        </td>
        <td><?php echo $api_key['api_key']; ?></td>
        <td><?php echo date("M j, Y g:i a", strtotime($api_key['created'])); ?></td>
        <td>
            <form method="post" action="/api_keys/<?php echo $api_key['id']; ?>">
                <input type="hidden" name="_METHOD" value="PUT">
    <?php
    if ($api_key['status'] == 2) {
        ?>
                <input type="hidden" value="1" name="state">
                <button type="submit" class="btn btn-success btn-mini">Reinstate</button>
        <?php
    } elseif ($api_key['status'] == 1) {
        ?>
                <input type="hidden" value="2" name="state">
                <button type="submit" class="btn btn-danger btn-mini">Suspend</button>
        <?php
    } else {
        ?>
                <input type="hidden" value="1" name="state">
                <button type="submit" class="btn btn-success btn-mini">Activate</button>
        <?php
    }
    ?>
            </form>
        </td>
    </tr>
    <tr>
        <td colspan="5"><?php echo $api_key['api_usage']; ?></td>
    </tr>
    <?php
}
?>
            </tbody>
        </table>
        <div class="clearfix"></div>
    </div>
<?php
    include($VIEW_DIRECTORY . '/Partials/footer.html');
    include($VIEW_DIRECTORY . '/Partials/site_wide_footer_js.html');
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('li.home-nav').addClass('active');
            });
        </script>
  </body>
</html>
