<?php
namespace Anax\View;

?>

<h3>Profile for <?= $username ?>.</h3>

<img src="<?= $grav; ?>" alt="" />

<ul>
    <li><?= $email ?> <a href="user/changeEmail">Change Email</a></li>
    <li><a href="user/changePassword">Change Password</a></li>
    <li><a href="user/logout">Log out</a></li>
</ul>
