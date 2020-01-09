<?php
namespace Anax\View;

?>

<h2>Profile for <?= $username ?></h2>

<img src="<?= $grav; ?>" alt="" />

<ul>
    <li><a href="user/view/<?= $username ?>">View User Activity</a>
    <li><?= $email ?> <a href="user/changeEmail">Change Email</a></li>
    <li><a href="user/changePassword">Change Password</a></li>
    <li><a href="user/logout">Log out</a></li>
</ul>
