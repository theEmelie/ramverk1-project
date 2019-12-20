<?php
namespace Anax\View;

?>
<h2>Tags</h2>
<?php foreach ($tags as $tag) {
    echo "<a href='questions/viewTag/". $tag->tag . "'><span class='tagName'>" . $tag->tag . "</span></a>" ;
}?>
