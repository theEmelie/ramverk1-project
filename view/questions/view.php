<?php
namespace Anax\View;

?>

<div class="question">
    <h3 class="queTitle"> <?= $question->title ?> </h3>
    <span class="author">Asked by: <?= $question->username ?></span>
    <span class="date">Last updated: <?= $question->updated ?></span>
    <p class ="queText"> <?= $question->text ?></p>
    <span class="tags">
        <?php foreach ($question->tagNames as $tag) {
            echo "<a href='questions/viewTag/". $tag . "'><span class='tagName'>" . $tag . "</span></a>" ;
        }?>
    </span>
</div>
<div class="line"></div>
