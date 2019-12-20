<?php
namespace Anax\View;


// var_dump($questions);

?>
<button class="ask" onClick="window.location.href='../../questions/ask'">Ask a Question</button>
<h2>FurQuestions Forum</h2>
<?php
foreach ($questions as $que) { ?>
    <div class="question">
        <h3 class="queTitle"> <?= $que->title ?> </h3>
        <span class="author">Asked by: <?= $que->username ?></span>
        <span class="date">Last updated: <?= $que->updated ?></span>
        <p class ="queText"> <?= substr($que->text, 0, 95) ?>...
        <br><a class="readMore" href="../../questions/view/<?= $que->id ?>">Read more</a></p>
        <span class="tags">
            <?php foreach ($que->tagNames as $tag) {
                echo "<a href='../../questions/viewTag/". $tag . "'><span class='tagName'>" . $tag . "</span></a>" ;
            }?>
        </span>
    </div>
    <div class="line"></div>
<?php }
?>
