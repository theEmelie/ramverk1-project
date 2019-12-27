<?php
namespace Anax\View;

?>

<div class="recentQuestions">
<h2>Most Recent Questions</h2>

<?php
foreach ($questions as $que) { ?>
    <div class="question">
        <a href="questions/view/<?=$que->id ?>"><h3 class="queTitle"> <?= $que->title ?> </h3></a>
        <span class="author">Asked by: <a href="user/view/<?=$que->username?>"><?= $que->username ?></a></span>
        <span class="date">Last updated: <?= $que->updated ?></span>
        <span class="tags">
            <?php foreach ($que->tagNames as $tag) {
                echo "<a href='questions/viewTag/". $tag . "'><span class='tagName'>" . $tag . "</span></a>" ;
            }?>
        </span>
    </div>
    <div class="line"></div>
<?php }
?>
</div>

<div class="sidebar">
    <div class="popularTags">
        <h2>Popular Tags</h2>
        <?php foreach ($popularTags as $tag) {
            echo "<span class='tagName'><a href='questions/viewTag/". $tag->tag . "'>" . $tag->tag . "</a> (". $tag->TagCount . ") </span>" ;
        }?>
    </div>

    <div class="activeUsers">
        <h2>Active Users</h2>
        <?php foreach ($userActivity as $user) {
            echo "<span class='tagName'><a href='user/view/". $user->acronym . "'>" . $user->acronym . "</a> (". $user->activityCount . ") </span>" ;
        }?>
    </div>
</div>
