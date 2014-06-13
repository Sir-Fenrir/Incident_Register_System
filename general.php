<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 11-6-14
 * Time: 16:04
 */

$con = mysqli_connect("localhost","helpdesk","Sku53_u3","helpdesk") or die('Unable to connect');

function logoutKnop(){
    global $perm;
    echo $perm;

    echo "<form action='".htmlspecialchars($_SERVER['PHP_SELF'])."' method=\"post\">";
    echo "<input type=\"hidden\" name=\"logout\" value=\"1\">";
    echo "<input class=\"logout\" type=\"submit\" value=\"Logout\">";
    echo "</form>";
}

/*
 *  Deze functie controleerd of iemand op uitloggen heeft geklikt, zo ja worden sessie variabelen verwijderd en de sessie beeindigd
 */
function checkLogin(){
    session_start();
    if(isset($_POST['logout']))
    {
        logOut();
    }
}

/*
 *Deze functie eindigd de sessie en vernietigd de sessie variabelen
 */
function logOut(){
    session_unset();
    session_destroy();
}

/*
 * This function removes possible malicious input
 */
function removeMaliciousInput($input){
    $input = strip_tags($input);
    return $input;
}

/*
 * Function to validate the entered date
 */
function validateDate($day, $month, $year){
    if(($day < 1) || $month < 1 || $month > 12 || $year < 1900 || $year > 2100){
        return false;
    }
    //Check february
    if($month == 2){
        if((($year % 4 == 0) && ($year % 100 > 0) || ($year % 400 == 0)) && $day < 30){
            return true;
        } else if( $day < 29 ){
            return true;
        } else {
            return false;
        }
    }
    // Check the months with 31 days
    if(($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12)){
        if($day > 31){
            return false;
        }
        // The remaining months all have 30 days
    } else if($day > 30){
        return false;
    }
    // Since we've gotten here, it must be true.
    return true;
}