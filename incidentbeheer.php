<?php
/**
 * Created by PhpStorm.
 * User: gebruiker
 * Date: 12-6-14
 * Time: 11:51
 */

function displayContentIncident($postData)
{
    switch($postData) {
        case "displayIncidenten" : new HelpdeskTable("Incidenten", "SELECT * FROM incidenten", "displayIncidenten", null, null, null); break;
        default : echo "Hello ".ucfirst($_SESSION['user']);
    }
}

function displayMenuIncident()
{
    new Button("Incidenten","display", "displayIncidenten");
}

function processEventIncident($eventID)
{
    switch($eventID){

    }
}