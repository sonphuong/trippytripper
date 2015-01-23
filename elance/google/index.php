<?php

$directory = './';

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

while($it->valid()) {

    if (!$it->isDot()) {

        //echo 'SubPathName: ' . $it->getSubPathName() . "\n";
        //echo 'SubPath:     ' . $it->getSubPath() . "\n";
        $ext = substr($it->key(), -3);
        if($ext =='php'){
        	echo '<a href="'.$it->key().'">'.$it->key().'</a>';	
        	echo "</br>";
        }
        
    }

    $it->next();
}

?>