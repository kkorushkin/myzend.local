<?php
/*
* Так как мы находимся в режиме разработке у нас нет необходимости указывать карту классов(classmap),
 * оставим просто пустой массив.
*/
return array();
/*
*Так как массив пуст, то автозагрузчик «вернется» и вызовет СтандартныйАвтозагрузчик
*(StandardAutoloader).
*/
?>