<?php

ob_start();//ürítünk
require_once("autoloader.php");
require_once("config.php");
Modell::Connenct();
Controller::ServRequest();
View::RenderJSON();

//ezek alapják fogja működtetni a reastApinkat.

