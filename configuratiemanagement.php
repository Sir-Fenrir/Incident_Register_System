<?php
/**
 * Created by PhpStorm.
 * User: gebruiker
 * Date: 12-6-14
 * Time: 11:52
 */

$message= "";

//dummycode for configuratiemagement
// to do change name and remove some code

function displayContentConfig($postData) {
    switch($postData) {
        case "displayHardware" : displayHardware($postData); break;
        case "displayEditHardware" : displayEditHardware(); break;
        case "displayAddHardware" : displayAddHardware(); break;

        case "displaySoftware" : displaySoftware($postData); break;
        case "displayEditSoftware" : displayEditSoftware(); break;
        case "displayAddSoftware" : displayAddSoftware(); break;

        case "displayUsers" : displayUsers($postData); break;
        case "displayEditUser" : displayEditUser(); break;
        case "displayAddUser" : displayAddUser(); break;
        case "displaySearch" : displaySearchConfig($postData); break;

        case "displayHardwareAndSoftware" : displayHardwareAndSoftware($postData); break;
        default : displayLandingConfig();
    }
}

function displayMenuConfig() {
    new Button("Hardware", "display", "displayHardware");
    new Button("Software", "display", "displaySoftware");
    new Button("Gebruikers", "display", "displayUsers");
    new Button("Hardware toevoegen", "display", "displayAddHardware");
    new Button("Software toevoegen", "display", "displayAddSoftware");
    new Button("Gebruiker toevoegen", "display", "displayAddUser");
}

function processEventConfig($eventID)
{
    switch($eventID) {
        case "deleteHardware" : deleteHardware(); break;
        case "deleteSoftware" : deleteSoftware(); break;
        case "addSoftware" : addSoftware(); break;
        case "addHardware" : addHardware(); break;
        case "addSoftware": addSoftware(); break;
        case "editHardware" : editHardware(); break;
        case "editSoftware" : editSoftware(); break;
        case "addUser"  : addUser(); break;
        case "deleteUser" : deleteUser(); break;
        case "editUser" : editUser(); break;
    }
}
/*
 * Functie die de zoek opdracht uitvoert en aan de hard ervan resultaten in tabel laat zien.
 */
function displaySearchConfig($postData)
{
    new HelpdeskTable("Software", makeSearchSoftware($_POST['search']), null,
                      "displayEditSoftware", "deleteSoftware", "id_software", $_POST['search'], null);

    new HelpdeskTable("Hardware", makeSearchHardware($_POST['search']), null,
                      "displayEditHardware", "deleteHardware", "id_hardware", $_POST['search'], null);
}


/*
 * Builds the query to search for the given search String
 * @param $searchString: Value to search for
 */
function makeSearchHardware($searchString)
{
    //Haalt de zoekopdracht op spaties uit elkaar
    $search = explode(" ", $searchString);

    /**
     * Geeft de gewilde velden aan en combineert de tabellen mbv een outer join, dit zorgt ervoor dat geen
     * rijen verloren gaan als je vergelijkt met een WHERE statement reeks.
     */
    $hardwareSearch = "SELECT hardware.id_hardware, hardware.soort, hardware.locatie, hardware.os,
                              hardware.merk, hardware.leverancier, hardware.aanschaf_jaar
                       FROM hardware
                       LEFT OUTER JOIN hardware_software ON hardware.id_hardware = hardware_software.id_hardware
                       LEFT OUTER JOIN software ON software.id_software = hardware_software.id_software";

    /*
     * Controleerd of elk van de opgegeven woorden in minimaal een van de aangegeven velden zitten, dit gebruikt
     * AND dus alle woorden moeten minimaal 1x gevonden zijn. Kan in OR veranderd worden voor alle resultaten met
     * minstens 1 woord gevonden.
     */
    for($x=0; $x<count($search); $x++) {
        if($x==0){$hardwareSearch=$hardwareSearch." WHERE";} else {$hardwareSearch=$hardwareSearch." AND";}
        $hardwareSearch = $hardwareSearch."(hardware.id_hardware LIKE '%".$search[$x]."%' OR hardware.soort LIKE '%".$search[$x]."%' OR hardware.locatie LIKE '%".$search[$x]."%'
        OR hardware.os LIKE '%".$search[$x]."%' OR hardware.merk LIKE '%".$search[$x]."%' OR hardware.leverancier LIKE '%".$search[$x]."%'
        OR hardware.aanschaf_jaar LIKE '%".$search[$x]."%' OR hardware.status LIKE '%".$search[$x]."%' OR software.naam LIKE '%".$search[$x]."%')";
    }

    //Groepeerd het zodat maar 1 item per vonst word getoont
    $hardwareSearch = $hardwareSearch." GROUP BY hardware.id_hardware";
    return $hardwareSearch;
}



function makeSearchSoftware($searchString)
{
    //Haalt de zoekopdracht op spaties uit elkaar
    $search = explode(" ", $searchString);

    /**
     * Geeft de gewilde velden aan en combineert de tabellen mbv een outer join, dit zorgt ervoor dat geen
     * rijen verloren gaan als je vergelijkt met een WHERE statement reeks.
     */
    $softwareSearch = "SELECT *
                       FROM software";
    /**
    * Controleerd of elk van de opgegeven woorden in minimaal een van de aangegeven velden zitten, dit gebruikt
    * AND dus alle woorden moeten minimaal 1x gevonden zijn. Kan in OR veranderd worden voor alle resultaten met
    * minstens 1 woord gevonden.
    */

    for($x=0; $x<count($search); $x++) {
        if($x==0){$softwareSearch=$softwareSearch." WHERE";} else {$softwareSearch=$softwareSearch." OR";}
        $softwareSearch = $softwareSearch."(id_software LIKE '%".$search[$x]."%' OR naam LIKE '%".$search[$x]."%' OR soort LIKE '%".$search[$x]."%'
        OR producent LIKE '%".$search[$x]."%' OR leverancier LIKE '%".$search[$x]."%' OR aantal_licenties LIKE '%".$search[$x]."%'
        OR soort_licentie LIKE '%".$search[$x]."%' OR aantal_gebruikers LIKE '%".$search[$x]."%' OR status LIKE '%".$search[$x]."%')";
    }


    //Groepeerd het zodat maar 1 item per vonst word getoont
    $softwareSearch = $softwareSearch." GROUP BY software.id_software";
    return $softwareSearch;
}

    function displaySoftware($postData)
    {
        new HelpdeskTable("Software", "SELECT id_software AS ID, naam, soort,
                                              producent, leverancier, aantal_licenties AS Licenties,
                                              soort_licentie AS Licentiesoort, aantal_gebruikers AS Gebruikers,
                                              status
                                              FROM software", $postData,
                          "displayEditSoftware", "deleteSoftware", "id_software", null, null);
    }

    function displayAddSoftware()
    {
        formHeader();
        textField("ID_Software", null);
        textField("Naam", null);
        textField("Soort", null);
        textField("Producent", null);
        textField("Leverancier", null);
        textField("Aantal_Licenties", null);
        textField("Soort_Licentie", null);
        textField("Aantal_Gebruikers", null);
        textField("Status", null);
        hiddenValue("display", "displaySoftware");
        formFooter("addSoftware");
    }

    function displayEditSoftware()
    {

    global $con;

        $values = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM software WHERE id_software='".$_POST['key']."'"));


        formHeader();
        displayField("ID_Software", $values['id_software'] );
        textField("Naam", $values['naam']);
        textField("Soort", $values['soort']);
        textField("Producent", $values['producent']);
        textField("Leverancier", $values['leverancier']);
        textField("Aantal_Licenties", $values['aantal_licenties']);
        textField("Soort_Licentie", $values['soort_licentie']);
        textField("Aantal_Gebruikers", $values['aantal_gebruikers']);
        textField("Status", $values['status']);
        hiddenValue("display", "displaySoftware");
        formFooter("editSoftware");


    }

    function editSoftware()
    {
         global $con;

        $valid = emptyCheck($_POST['ID_Software']);
        $valid = emptyCheck($_POST['Naam']); $naam = removeMaliciousInput($_POST['Naam']);
        $valid = emptyCheck($_POST['Soort']); $soort = removeMaliciousInput($_POST['Soort']);
        $valid = emptyCheck($_POST['Producent']); $pro = removeMaliciousInput($_POST['Producent']);
        $valid = emptyCheck($_POST['Leverancier']); $lev = removeMaliciousInput($_POST['Leverancier']);
        $valid = emptyCheck($_POST['Aantal_Licenties']); $a_lic = removeMaliciousInput($_POST['Aantal_Licenties']);
        $s_lic = removeMaliciousInput($_POST['Soort_Licentie']);
        $a_geb = removeMaliciousInput($_POST['Aantal_Gebruikers']);
        $status = removeMaliciousInput($_POST['Status']);
        $valid = numberCheck($_POST['Aantal_Licenties']);
        $valid = numberCheck($_POST['Aantal_Gebruikers']);

        if($valid) {
            mysqli_query($con, "UPDATE software SET naam='".$naam."', soort='".$soort."', producent='".$pro."', leverancier='".$lev."', aantal_licenties='".$a_lic."', soort_licentie='".$s_lic."', aantal_gebruikers='".$a_geb."', status='".$status."'
                                WHERE id_software='".$_POST['ID_Software']."'")or die(mysqli_error($con));
        }
    }
    /**
     * This function creates a table that displays the existing users
     * @param $postData
     */
    function displayUsers($postData)
    {
        global $message;
        echo $message;
        $messagen = "";
        new HelpdeskTable("Gebruikers", "SELECT username, rechten FROM users", $postData,
            "displayEditUser", "deleteUser", "username", null, null);
    }

    function displayHardware($postData)
    {
        new HelpdeskTable("Hardware", "SELECT * FROM hardware", $postData,
            "displayEditHardware", "deleteHardware", "id_hardware", null, null);
    }

/**
 * Function to display one hardware item and the installed software
 */
function displayHardwareAndSoftware($postData){
    $hardwareID = $_POST['hardwareID'];
    $query = "SELECT * FROM hardware WHERE hardware_id = '{$hardwareID}'";
    echo("De volgende tabel toont de details van de hardware:");
    new HelpdeskTable("Hardware item", $query, null, null, null, "id_hardware", null, null);

    $query = "SELECT software.id_software AS ID, software.naam, software.soort,
                     software.producent, software.leverancier, software.aantal_licenties AS Licenties,
                     software.soort_licentie AS Licentiesoort, software.aantal_gebruikers AS Gebruikers,
                     software.status
                     FROM hardware_software, software
                     WHERE software.id_software = hardware_software.id_software
                     AND id_hardware='{$hardwareID}'";
    echo("De volgende tabel toont de software die op dit hardware item geïnstalleerd staan:");
    new HelpdeskTable("Software items", $query, null, null, null, "ID", null, "displayHardwareAndSoftware");
}

function displayAddHardware()
    {
        formHeader();
        textField("Hardware_ID", null);
        dropDown("Soort", queryToArray("SELECT soort FROM hardware GROUP BY soort"), null);
        dropDown("Locatie", queryToArray("SELECT locatie FROM hardware GROUP BY locatie"), null);
        dropDown("OS", queryToArray("SELECT naam FROM software WHERE soort LIKE '%besturingssysteem%'"), null);
        CheckBoxes("Software", queryToArray("SELECT naam FROM software WHERE soort NOT LIKE '%besturingssysteem%'"), 3, null);
        textField("Leverancier", null);
        textField("Aanschaf_jaar", null);
        textField("Status", null);
        hiddenValue("display", "displayHardware");
        formFooter("addHardware");
    }

    function displayEditHardware()
    {
        global $con;

        $values = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM hardware WHERE id_hardware='".$_POST['key']."'"));
        $os = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM software WHERE id_software='".$values['os']."'"));

        formHeader();
        displayField("Hardware_ID", $values['id_hardware']);
        dropDown("Soort", queryToArray("SELECT soort FROM hardware GROUP BY soort"), $values['soort']);
        dropDown("Locatie", queryToArray("SELECT locatie FROM hardware GROUP BY locatie"), $values['locatie']);
        dropDown("OS", queryToArray("SELECT naam FROM software WHERE soort LIKE '%besturingssysteem%'"), $os['naam']);
        CheckBoxes("Software", queryToArray("SELECT naam FROM software WHERE soort NOT LIKE '%besturingssysteem%'"), 3,
                    queryToArray("SELECT software.naam FROM hardware_software, software WHERE software.id_software = hardware_software.id_software AND id_hardware='".$_POST['key']."'"));
        textField("Leverancier", $values['leverancier']);
        textField("Aanschaf_jaar", $values['aanschaf_jaar']);
        textField("Status", $values['status']);
        hiddenValue("display", "displayHardware");
        formFooter("editHardware");
    }

    function editHardware()
    {
        global $con;

        $valid = emptyCheck($_POST['Hardware_ID']);
        $valid = emptyCheck($_POST['Soort']); $soort = removeMaliciousInput($_POST['Soort']);
        $valid = emptyCheck($_POST['Locatie']); $loc = removeMaliciousInput($_POST['Locatie']);
        $valid = emptyCheck($_POST['Leverancier']); $lev = removeMaliciousInput($_POST['Leverancier']);
        $valid = yearCheck($_POST['Aanschaf_jaar']); $jaar = removeMaliciousInput($_POST['Aanschaf_jaar']);
        $os = removeMaliciousInput($_POST['OS']);
        $status = removeMaliciousInput($_POST['Status']);

        if($valid) {
            mysqli_query($con, "UPDATE hardware SET soort='".$soort."', locatie='".$loc."', os='".$os."', leverancier='".$lev."', aanschaf_jaar='".$jaar."', status='".$status."'
                                WHERE id_hardware='".$_POST['Hardware_ID']."'");
        }

        if(!empty($_POST['Software'])) {
            mysqli_query($con, "DELETE FROM hardware_software WHERE id_hardware='".$_POST['Hardware_ID']."'");

            foreach($_POST['Software'] as $box) {
                $key = mysqli_fetch_assoc(mysqli_query($con, "SELECT id_software FROM software WHERE naam='".$box."'"));
                mysqli_query($con, "Insert INTO hardware_software (id_hardware, id_software)
                                    VALUES ('".$_POST['Hardware_ID']."','".$key['id_software']."')") or die(mysqli_error($con));
            }
        }
    }

    /**
     * This function will create a form to add a new user
     */
    function displayAddUser(){
        global $message;
        if($message != ""){
            echo($message);
            $message = "";
        }
        formHeader();
        textField("Gebruikersnaam", null);
        passwordField("password1");
        passwordField("password2");
        dropDown("Rechten", queryToArray("SELECT * FROM rechten"), null);
        hiddenValue("display", "displayAddUser");
        formFooter("addUser");
    }

    /**
     * This function adds a user and encrypts his password
     */
    function addUser(){
        global $con;
        global $message;
        $message = "";
        $username = removeMaliciousInput($_POST['Gebruikersnaam']);
        $password1 = removeMaliciousInput($_POST['password1']);
        $password2 = removeMaliciousInput($_POST['password2']);
        $rechten = $_POST['Rechten'];

        $result = mysqli_query($con, "SELECT COUNT(*) FROM users WHERE username = '{$username}'") or die("Stuff");
        $result = mysqli_fetch_row($result);

        if($result[0] > 0){
            $message .= "ERROR: Deze gebruikersnaam bestaat al!";
        }
        if($password1 != $password2){
            $message .= "ERORR: De wachtwoorden komen niet overeen!";
        }
        if($message === ""){
            $hash = password_encrypt($password1);
            mysqli_query($con, "INSERT INTO users
                                VALUES('{$username}', '{$hash}', '{$rechten}')") or die(mysqli_error($con));

            if (mysqli_connect_errno())
            {
                $message .= "Gebruiker toevoegen mislukt. Probeer het opnieuw.";
            } else {
                $message .= "Gebruiker succesvol toegevoegd.";
            }
        }
    }

/**
 * This function shows a form to edit an existing user
 */
    function displayEditUser(){
        global $con;
        global $message;
        if($message != ""){
            echo($message);
            $message = "";
        }
        $primeKey = $_POST['key'];
        $query = "SELECT * FROM users WHERE username = '{$primeKey}'";
        $result = mysqli_query($con, $query);
        $result = mysqli_fetch_assoc($result);
        echo("Je kunt nu deze gebruiker wijzigen. Om het wachtwoord te veranderen, voer een nieuw wachtwoord in. Anders zal het wachtwoord niet veranderen.");
        formHeader();
        echo $result['username'];
        textField("Gebruikersnaam", $result['username']);
        passwordField("password1");
        passwordField("password2");
        dropDown("Rechten", queryToArray("SELECT * FROM rechten"), $result['rechten']);
        hiddenValue("display", "displayUsers");
        formFooter("editUser");
    }

    function editUser(){
        global $con;
        global $message;
        $message = "";
        $username = removeMaliciousInput($_POST['Gebruikersnaam']);
        $password1 = removeMaliciousInput($_POST['password1']);
        $password2 = removeMaliciousInput($_POST['password2']);
        $rechten = $_POST['Rechten'];

        $result = mysqli_query($con, "SELECT COUNT(*) FROM users WHERE username = '{$username}'") or die("Stuff");
        $result = mysqli_fetch_row($result);

        if($result[0] > 1){
            $message .= "ERROR: Deze gebruikersnaam bestaat al!";
        }
        if($password1 != $password2){
            $message .= "ERORR: De wachtwoorden komen niet overeen!";
        }
        if($message === ""){
            if($password1 != ""){
                $hash = password_encrypt($password1);
                mysqli_query($con, "UPDATE users
                                    SET username='{$username}', password='{$hash}', rechten='{$rechten}'
                                    WHERE username = '{$username}'") or die(mysqli_error($con));

                if (mysqli_connect_errno())
                {
                    $message .= "Gebruiker wijzigen mislukt. Probeer het opnieuw.";
                } else {
                    $message .= "Gebruiker succesvol gewijzigd.";
                }
            } else {
                mysqli_query($con, "UPDATE users
                                        SET username='{$username}', rechten='{$rechten}'
                                        WHERE username = '{$username}") or die(mysqli_error($con));

                if (mysqli_connect_errno())
                {
                    $message .= "Gebruiker wijzigen mislukt. Probeer het opnieuw.";
                } else {
                    $message .= "Gebruiker succesvol gewijzigd.";
                }
            }
        }
    }

function addHardware()
    {
        global $con;

        $valid = emptyCheck($_POST['Hardware_ID']); $id = removeMaliciousInput($_POST['Hardware_ID']);
        $valid = emptyCheck($_POST['Soort']); $soort = removeMaliciousInput($_POST['Soort']);
        $valid = emptyCheck($_POST['Locatie']); $loc = removeMaliciousInput($_POST['Locatie']);
        $valid = emptyCheck($_POST['Leverancier']); $lev = removeMaliciousInput($_POST['Leverancier']);
        $valid = yearCheck($_POST['Aanschaf_jaar']); $jaar = removeMaliciousInput($_POST['Aanschaf_jaar']);
        $os = removeMaliciousInput($_POST['OS']);
        $status = removeMaliciousInput($_POST['Status']);

        if($valid) {
            mysqli_query($con, "INSERT INTO hardware (id_hardware, soort, locatie, os, leverancier, aanschaf_jaar, status)
                                VALUES('".$id."', '".$soort."', '".$loc."',
                                       '".$os."', '".$lev."', '".$jaar."',
                                       '".$status."')") or die('hw error');
        }

        if(!empty($_POST['Software'])) {
            foreach($_POST['Software'] as $box) {
                $key = mysqli_fetch_assoc(mysqli_query($con, "SELECT id_software FROM software WHERE naam='".$box."'"));
                mysqli_query($con, "Insert INTO hardware_software (id_hardware, id_software)
                                    VALUES ('".$_POST['Hardware_ID']."','".$key['id_software']."')") or die('sw error');
            }
        }
    }

function addSoftware()
    {
        global $con;

        $valid = emptyCheck($_POST['ID_Software']); $id = removeMaliciousInput($_POST['ID_Software']);
        $valid = emptyCheck($_POST['Naam']); $naam = removeMaliciousInput($_POST['Naam']);
        $valid = emptyCheck($_POST['Soort']); $soort = removeMaliciousInput($_POST['Soort']);
        $valid = emptyCheck($_POST['Producent']); $pro = removeMaliciousInput($_POST['Producent']);
        $valid = emptyCheck($_POST['Leverancier']); $lev = removeMaliciousInput($_POST['Leverancier']);
        $valid = emptyCheck($_POST['Aantal_Licenties']); $a_lic = removeMaliciousInput($_POST['Aantal_Licenties']);
        $s_lic = removeMaliciousInput($_POST['Soort_Licentie']);
        $a_geb = removeMaliciousInput($_POST['Aantal_Gebruikers']);
        $status = removeMaliciousInput($_POST['Status']);
        $valid = numberCheck($_POST['Aantal_Licenties']);
        $valid = numberCheck($_POST['Aantal_Gebruikers']);

        if($valid) {
            mysqli_query($con, "INSERT INTO software (id_software, naam, soort, producent, leverancier, aantal_licenties, soort_licentie, aantal_gebruikers, status)
                                VALUES('".$id."', '".$naam."', '".$soort."',
                                       '".$pro."', '".$lev."', '".$a_lic."', '".$s_lic."', '".$a_geb."',
                                       '".$status."')") or die(mysqli_error($con));
        }


    }




    function deleteHardware()
    {
        global $con;

        $primeKey = $_POST['key'];

        mysqli_query($con, "DELETE FROM hardware_software WHERE id_hardware='".$primeKey."'") or die('swdel error');
        mysqli_query($con, "DELETE FROM hardware WHERE id_hardware='".$primeKey."'") or die('hwdel error');
    }

    function deleteUser(){
        global $con;
        $primeKey = $_POST['key'];
        mysqli_query($con, "DELETE FROM users WHERE username='".$primeKey."'") or die('hwdel error');
    }

    function displayLandingConfig()
    {
        echo "Hello ".ucfirst($_SESSION['user']);
    }

    function deleteSoftware()
    {
        global $con;

        $primeKey = $_POST['key'];
        mysqli_query($con, "DELETE FROM software WHERE id_software ='".$primeKey."'") or die(mysqli_error($con));
    }


?>