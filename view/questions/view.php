<?php
namespace Anax\View;

// var_dump($question);
?>
<button class="ask" onClick="window.location.href='../../../questions/answer/<?= $question->id ?>'">Answer question</button>
<div class="question">
    <h3 class="queTitle"> <?= $question->title ?> </h3>
    <span class="author">Asked by: <a href="../../../user/view/<?=$question->username?>"><?= $question->username ?></a></span>
    <span class="date">Last updated: <?= $question->updated ?></span>

    <div class="questionVotes">
        <button class="upVote" onClick="window.location.href='../../../questions/upVote/<?= $question->id ?>'">+</button>
        <span class="voteCount"><?= $question->voteCount ?></span>
        <button class="downVote" onClick="window.location.href='../../../questions/downVote/<?= $question->id ?>'">-</button>
    </div>

    <div class="questionText">
        <p class="queText"> <?= $question->text ?></p>
        <span class="tags">
            <?php foreach ($question->tagNames as $tag) {
                echo "<a href='../../../questions/viewTag/". $tag . "'><span class='tagName'>" . $tag . "</span></a>" ;
            } ?>
        </span>
    </div>

    <div class="allComments">
    <?php foreach ($question->comments as $com) { ?>
        <div class="qComment">
            <span class="commentAuthor">Commented by: <a href="../../../user/view/<?= $com->username ?>"><?= $com->username ?></a></span>
            <span class="commentDate">at <?= $com->updated ?></span>

            <div class="commentVotes">
                <button class="upVote" onClick="window.location.href='../../../questions/upVoteQuestionComment/<?= $question->id ?>/<?= $com->id ?>'">+</button>
                <span class="voteCount"><?= $com->voteCount ?></span>
                <button class="downVote" onClick="window.location.href='../../../questions/downVoteQuestionComment/<?= $question->id ?>/<?= $com->id ?>'">-</button>
            </div>

            <div class="textComment">
                <span class="commentText"> <?= $com->text ?></span>
            </div>

            <div class="commentLine"></div>
        </div>
    <?php }?>
    <button class="makeComment" onClick="window.location.href='../../../questions/comment/<?= $question->id ?>'">Comment</button>
    </div>
</div>

<div class="line"></div>
<?php
$numAnswers = count($answers);
if ($numAnswers > 1) {
    echo "<h2>" . $numAnswers . " Answers </h2>";
    ?>
    <div class="sort">
        Sort by:
        <span class="sortDate">
            <a href="../../../questions/view/<?=$question->id?>/date">Date</a>
        </span>
        <span class="sortRank">
            <a href="../../../questions/view/<?=$question->id?>/rank">Rank</a>
        </span>
    </div>
<?php } else if ($numAnswers == 1) {
    echo "<h2>" . $numAnswers . " Answer </h2>";
    ?>
    <div class="sort">
        Sort by:
        <span class="sortDate">
            <a href="../../../questions/view/<?=$question->id?>/date">Date</a>
        </span>
        <span class="sortRank">
            <a href="../../../questions/view/<?=$question->id?>/rank">Rank</a>
        </span>
    </div>
<?php }
?>

<div class="answers">
    <?php foreach ($answers as $ans) { ?>
        <div class="answer">
            <span class="author">Answered by: <a href="../../../user/view/<?=$ans->username?>"><?= $ans->username ?></a></span>
            <span class="date">Last updated: <?= $ans->updated ?></span>

            <?php if ($ans->accepted == 1) { ?>
                <span class="accepted">This answer has been marked as an accepted answer</span>
            <?php } else {
                if ($isAuthor) { ?>
                    <button class="markAccepted" onClick="window.location.href='../../../questions/markAcceptedAnswer/<?= $question->id ?>/<?= $ans->id ?>'">Mark as Accepted</button>
                <?php }
            } ?>

            <div class="answerVotes">
                <button class="upVote" onClick="window.location.href='../../../questions/upVoteAnswer/<?= $question->id ?>/<?= $ans->id ?>'">+</button>
                <span class="voteCount"><?= $ans->voteCount ?></span>
                <button class="downVote" onClick="window.location.href='../../../questions/downVoteAnswer/<?= $question->id ?>/<?= $ans->id ?>'">-</button>
            </div>

            <div class="textAnswer">
                <p class="answerText"> <?= $ans->text ?></p>
            </div>

            <div class="allComments">
            <?php foreach ($ans->comments as $com) { ?>
                <div class="qComment">
                    <span class="commentAuthor">Commented by: <a href="../../../user/view/<?= $com->username ?>"><?= $com->username ?></a></span>
                    <span class="commentDate">at <?= $com->updated ?></span>

                    <div class="commentVotes">
                        <button class="upVote" onClick="window.location.href='../../../questions/upVoteAnswerComment/<?= $question->id ?>/<?= $com->id ?>'">+</button>
                        <span class="voteCount"><?= $com->voteCount ?></span>
                        <button class="downVote" onClick="window.location.href='../../../questions/downVoteAnswerComment/<?= $question->id ?>/<?= $com->id ?>'">-</button>
                    </div>

                    <div class="textComment">
                        <span class="commentText"> <?= $com->text ?></span>
                    </div>

                    <div class="commentLine"></div>
                </div>
            <?php }?>
            </div>
            <button class="makeComment" onClick="window.location.href='../../../questions/answerComment/<?= $question->id ?>/<?= $ans->id ?>'">Comment</button>
        </div>
        <div class="line"></div>
    <?php }?>
</div>
