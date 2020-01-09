<?php
namespace Anax\View;

?>
<button class="ask" onClick="window.location.href='../../questions/ask'">Ask a Question</button>
<h2>FurQuestions Forum</h2>
<?php
foreach ($questions as $que) { ?>
    <div class="question">
        <a href="../../questions/view/<?=$que->id ?>/date"><h3 class="queTitle"> <?= $que->title ?> </h3></a>
        <span class="author">Asked by: <a href="../../user/view/<?=$que->username?>"><?= $que->username ?></a></span>
        <span class="dateForum">Last updated: <?= $que->updated ?></span>
        <span class="tags">
            <?php foreach ($que->tagNames as $tag) {
                echo "<a href='../../questions/viewTag/". $tag . "'><span class='tagName'>" . $tag . "</span></a>" ;
            }?>
        </span>
    </div>
    <div class="line"></div>
<?php }
?>
