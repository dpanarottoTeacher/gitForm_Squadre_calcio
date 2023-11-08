<?php
session_start();

if (!isset($_SESSION['formData'])) {
    $_SESSION['formData'] = array();  //array che permane fino a che non si chiude il browser
                                    //variabile di sessione, memorizzata lato server
}


$teamName = "";
$teamWins = $teamDraws = $teamLosses = 0;
$errors = "";

function teamPoint($t) {
    return intval($t["wins"]) * 3 + intval($t["draws"]);
}

function sortByPoints($t1, $t2) {
    $p1 = teamPoint($t1);
    $p2 = teamPoint($t2);
    if ($p1 == $p2) {
        return 0;
    }
    return ($p1 > $p2) ? -1 : 1;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    function validateField($fieldValue) {
        $fieldValue = trim($fieldValue);
        $fieldValue = stripslashes($fieldValue);
        $fieldValue = htmlspecialchars($fieldValue);
        return $fieldValue;
    }

// Utilizza la funzione per validare tutti i campi
$teamName = validateField($_POST["teamName"]);
$teamWins = intval($_POST["teamWins"]);
$teamDraws = intval($_POST["teamDraws"]);
$teamLosses = intval($_POST["teamLosses"]);

    $teamName   = validateField($_POST["teamName"]);
    $teamWins   = intval($_POST["teamWins"]);
    $teamDraws  = intval($_POST["teamDraws"]);
    $teamLosses = intval($_POST["teamLosses"]);

    // Se non ci sono errori, elabora i dati
    if (empty($teamName) || $teamWins < 0 || $teamDraws < 0 || $teamLosses < 0) {
        $errors = "Dati inseriti in modo errato";
    } else {
        $team = array(
            "name" => $teamName,
            "wins" => $teamWins,
            "draws" => $teamDraws,
            "losses" => $teamLosses
        );

        $_SESSION['formData'][$teamName]= $team;  //aggiungo la squadra all'array
        
        //print_r($_SESSION);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inserisci Dati Squadre</title>
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
    <h2>Inserisci Dati Squadre</h2>
    <p>Tutti i campi sono obbligatori.</p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="choose">
            <label for="teamName">Nome Squadra:</label>
            <input type="text" name="teamName" id="teamName" required><br>

            <label for="teamWins">Partite vinte:</label>
            <input type="number" name="teamWins" id="teamWins" required><br>

            <label for="teamDraws">Partite con pareggio:</label>
            <input type="number" name="teamDraws" id="teamDraws" required><br>

            <label for="teamLosses">Partite perse:</label>
            <input type="number" name="teamLosses" id="teamLosses" required><br>
        </div>

        <div class="btncont">
            <input class="button" type="submit" value="Invia">
        </div>
    </form>

    <?php
    if (!empty($errors)) {
        echo "<h3>Correggi i seguenti errori:</h3>";
        echo "<p>$errors</p>";
    } 
    else {
        if (isset($_SESSION['formData'])) {
            usort($_SESSION['formData'], 'sortByPoints');
        }
    }
        
    ?>
    <?php
    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
   

    echo '<div>';
    echo    '<h2>Classifica</h2>';
    echo    '<table>';
    echo        '<tr>';
    echo            '<th>Squadra</th>';
    echo            '<th>Punti</th>';
    echo        '</tr>';
            
                foreach ($_SESSION['formData'] as $teamData) {
                    $point = teamPoint($teamData);
                    echo "<tr><td>" . $teamData["name"] . "</td><td>" . $point . "</td></tr>";
                }
            }
            
           
    echo '</table>';
    echo '</div>';
    ?>
</body>
</html>