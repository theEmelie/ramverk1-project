<?php
namespace Anax\View;

?>
<h2 class="reputationTitle">Reputation for <?= $username ?> is <?= $reputation ?></h2>

<h2>All Questions by <?= $username ?></h2>

<ul>
    <?php foreach ($questions as $que) { ?>
    <li><a href="../../questions/view/<?= $que->id ?>/date"><?= $que->title ?></a><span class="date">(<?= $que->updated ?>) </span></li>
    <?php } ?>
</ul>


<h2>All Questions Answered by <?= $username ?></h2>
<ul>
    <?php foreach ($answers as $ans) { ?>
    <li><a href="../../questions/view/<?= $ans->questionId ?>/date"><?= $ans->questionTitle ?></a><span class="date">(<?= $ans->updated ?>) </span></li>
    <?php } ?>
</ul>

<h2>All Questions with Comments by <?= $username ?></h2>
<ul>
    <?php foreach ($qComments as $com) { ?>
    <li><a href="../../questions/view/<?= $com->questionId ?>/date"><?= $com->questionTitle ?></a><span class="date">(<?= $com->updated ?>) </span></li>
    <?php } ?>
</ul>

<h2>All Questions with Answers Commented by <?= $username ?></h2>
<ul>
    <?php foreach ($aComments as $com) { ?>
    <li><a href="../../questions/view/<?= $com->questionId ?>/date"><?= $com->questionTitle ?></a><span class="date">(<?= $com->updated ?>) </span></li>
    <?php } ?>
</ul>
