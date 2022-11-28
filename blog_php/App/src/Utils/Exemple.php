<?
use App\Utils\Config;

$config = new Config(dirname(__DIR__,2) . "/config/route.yml", Config::YAML);
$config->get('index'); //return ["path"=> "/", "controller" => "App\Controller\PostController", "action" => "home"]
$config->getNested('index.path'); // return "/"
$config->getAll(); 
/*
Permet de recuperer tous les données de la config
return
[
    "index" => [
        "path"=> "/",
         "controller" => "App\Controller\PostController",
          "action" => "home"
    ]
    ...
]
 */
$config->getAll(true); 
/*
Permet de recuperer que les keys de la config
return
[
    "index",

    ...
]
 */
$config->save(); // Permet de save le fichier