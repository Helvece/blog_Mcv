<h1>ceci est un post </h1>

<?php

echo $post->getContent();
foreach($post->getComments() as $comment){
    ?><p><?echo $comment->getContent();?></p>
    <p><?echo $comment->getAuthor()->getUsername();?></p><?
    comments($comment);
}

function comments($comment, int $level = 0){
    foreach($comment->getComments() as $sub){
        ?><p ><?echo "subComment niveau $level";?></p>
        <p><?echo $sub->getContent();?></p>
        <p><?echo $sub->getAuthor()->getUsername();?></p><?
        comments($sub, ++$level);
    } 
}