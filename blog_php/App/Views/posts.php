<h1>ceci sont les posts</h1>
<?php



var_dump($_COOKIE);
foreach ($posts as $post) {
    ?><p ><?echo $post->getTitle();?></p>
    <p><?echo $post->getContent();?></p>
    <p><?echo $post->getAuthor()->getUsername();?></p>
    <a href= <?echo "/post?id=" . $post->getId()?>> view </a><?
    
}

// la variable $post est définie dans le fichier PostController.php
// $posts = []