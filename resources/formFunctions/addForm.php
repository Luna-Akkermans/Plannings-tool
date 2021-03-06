<?php 
$scriptTest = "";
$verified = false;
function cleanData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$errorText = "Zorg ervoor dat het onderstaande veld correct is ingevuld. ";
$check = array(false,false,false);
$err = array("", "", "");
$data = array();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $array = $_POST["afspraakInput"];
    $counter = 0;
    foreach($array as $inputWaarde){
        $counter++;
        if(empty($inputWaarde)){
            $data[$counter] = cleanData($inputWaarde);
            $check[$counter] = true;
        }
    }
    if ((count(array_unique($check)) === 1) and in_array(false, $check) === true) {
        $scriptTest = "";
        insert($array);
        $verified = true;
    }else{
        $scriptTest = 
            "<script> 
            var form = document.getElementById('formDiv');
            var content= document.getElementById('contentDiv');
            var cancelKnop = document.getElementById('cancelKnop');
            var afspraakKnop = document.getElementById('afspraakKnop');
            cancelKnop.classList.remove('d-none');
            afspraakKnop.classList.add('d-none');
            form.classList.remove('d-none');
            content.classList.add('d-none');
            </script>";
        for ($i = 0; $i < count($check); $i++) {
            if ($check[$i]) {
                $err[$i] = $errorText;
            }
        }
    }
}















function insert($array){
    $players = explode(",", $array[2]);
    $players = implode(",", $players);
    $pdo = dbConn();
    $sth = $pdo->prepare("INSERT INTO agenda (datum_tijd, Uitlegger, Spelers, gameID)
    VALUES (:date,:uitlegger,:spelers, :gameID)");
    $sth->execute(array(
        ':date' => date("Y-m-d H:i:s",strtotime($array[0])),
        ':uitlegger' => $array[1],
        ':spelers' => $players,
        'gameID' => $_GET['id']
       ));
}