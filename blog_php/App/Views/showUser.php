<h1>Voici les user</h1>
<?php




foreach ($Users as $users) {
    ?><a><?echo $users->getUsername(); ?> </a><a> <? echo $users->getMail(); ?> </a><a> <? echo $users->getRank(); ?> </a><form action ="<?php echo "/delUser"?>" method="post">
    <input name= 'test' type="submit" value=<? echo $users->getId()?>>
</form></br><?
}
