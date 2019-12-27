<?php
namespace Anax\View;

?>

<h2>All Questions by <?= $username ?></h2>

<ul>
    <?php foreach ($questions as $que) { ?>
    <li><a href="../../questions/view/<?= $que->id ?>"><?= $que->title ?></a><span class="date">(<?= $que->updated ?>) </span></li>
    <?php } ?>
</ul>


<h2>All Questions Answered by <?= $username ?></h2>
<ul>
    <?php foreach ($answers as $ans) { ?>
    <li><a href="../../questions/view/<?= $ans->questionId ?>"><?= $ans->questionTitle ?></a><span class="date">(<?= $ans->updated ?>) </span></li>
    <?php } ?>
</ul>
