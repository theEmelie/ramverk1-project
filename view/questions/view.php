<?php
namespace Anax\View;

?>
<button class="ask" onClick="window.location.href='../../questions/answer/<?= $question->id ?>'">Answer question</button>
<div class="question">
    <h3 class="queTitle"> <?= $question->title ?> </h3>
    <span class="author">Asked by: <a href="../../user/view/<?=$question->username?>"><?= $question->username ?></a></span>
    <span class="date">Last updated: <?= $question->updated ?></span>
    <p class="queText"> <?= $question->text ?></p>
    <span class="tags">
        <?php foreach ($question->tagNames as $tag) {
            echo "<a href='../../questions/viewTag/". $tag . "'><span class='tagName'>" . $tag . "</span></a>" ;
        } ?>
    </span>

    <div class="allComments">
    <?php foreach ($question->comments as $com) { ?>
        <div class="qComment">
            <span class="commentAuthor">Commented by: <a href="../../user/view/<?= $com->username ?>"><?= $com->username ?></a></span>
            <span class="commentDate">at <?= $com->updated ?></span>
            <span class="commentText"> <?= $com->text ?></span>
            <div class="commentLine"></div>
        </div>
    <?php }?>
    <button class="makeComment" onClick="window.location.href='../../questions/comment/<?= $question->id ?>'">Comment</button>
    </div>
</div>

<div class="line"></div>
<?php
$numAnswers = count($answers);
if ($numAnswers > 1) {
    echo "<h2>" . $numAnswers . " Answers </h2>";
} else if ($numAnswers == 1) {
    echo "<h2>" . $numAnswers . " Answer </h2>";
}
?>

<div class="answers">
    <?php foreach ($answers as $ans) { ?>
        <div class="answer">
            <span class="author">Answered by: <a href="../../user/view/<?=$ans->username?>"><?= $ans->username ?></a></span>
            <span class="date">Last updated: <?= $ans->updated ?></span>
            <p class="answerText"> <?= $ans->text ?></p>

            <div class="allComments">
            <?php foreach ($ans->comments as $com) { ?>
                <div class="qComment">
                    <span class="commentAuthor">Commented by: <a href="../../user/view/<?= $com->username ?>"><?= $com->username ?></a></span>
                    <span class="commentDate">at <?= $com->updated ?></span>
                    <span class="commentText"> <?= $com->text ?></span>
                    <div class="commentLine"></div>
                </div>
            <?php }?>
            </div>
            <button class="makeComment" onClick="window.location.href='../../questions/answerComment/<?= $question->id ?>/<?= $ans->id ?>'">Comment</button>
        </div>
        <div class="line"></div>
    <?php }?>
</div>
