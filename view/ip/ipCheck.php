<?php
namespace Anax\View;

?>

<h1>Validera en IP-adress</h1>

<form method="post">
    <label>IP Adress:
        <input type="text" name="ip" value ="<?= $json["ip"] ?>"/>
        <input type="submit" name="validate" value="Validera Ip">
    </label>
</form>

<table>
    <tr>
        <th>Fält</th>
        <th>Data</th>
    </tr>
    <tr>
        <td>Ip</td>
        <td><?= $json["ip"] ?></td>
    </tr>
    <tr>
        <td>Status</td>
        <td><?= $json["status"] ?></td>
    </tr>
    <tr>
        <td>Domän</td>
        <td><?= $json["domain"] ?></td>
    </tr>
    <?php
    if (array_key_exists("ipStackData", $json)) {
        if ($json["ipStackData"] != "") {
            ?>
    <tr>
        <td>Kontinent</td>
        <td><?= $json["ipStackData"]->{"continent_name"} ?></td>
    </tr>
    <tr>
        <td>Land</td>
        <td><?= $json["ipStackData"]->{"country_code"} ?></td>
    </tr>
    <tr>
        <td>Region</td>
        <td><?= $json["ipStackData"]->{"region_name"} ?></td>
    </tr>
    <tr>
        <td>Stad</td>
        <td><?= $json["ipStackData"]->{"city"} ?></td>
    </tr>
    <tr>
        <td>Latitude</td>
        <td><?= $json["ipStackData"]->{"latitude"} ?></td>
    </tr>
    <tr>
        <td>Longitude</td>
        <td><?= $json["ipStackData"]->{"longitude"} ?></td>
    </tr>
            <?php
        }
    } ?>
</table>
