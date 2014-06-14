<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12-6-14
 * Time: 13:48
 */

class Button
{
    public function __construct($name, $type, $postValue)
    {
        $spaced = explode("_", $name);
        $title = "";

        foreach($spaced as $string) {
            $title = $title." ".$string;
        }

        echo    "<form action=\"/index.php\" method=\"post\">";
        echo    "<input type=\"hidden\" name=".$type." value=".$postValue.">";
        echo    "<input class=\"nav\" type=\"submit\" value='".$title."'>";
        echo    "</form>";
    }
} 