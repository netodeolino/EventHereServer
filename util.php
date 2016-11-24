
<?php

function hey($l, $s = ""){
    echo($s.$l."<br>");
}

function usuario($id){
    $dao = new UserDAO();
    $res = $dao->findById($id);
    $dao->close();
    return $res;
}

function evento($id){
    $dao = new EventDAO();
    $res = $dao->findById($id);
    $dao->close();
    return $res;
}

function escrever($s, $name) {
    $f = fopen("C:\\Users\\Anderson\\Desktop\\".$name.".txt", "w");
    fwrite($f, $s);
    fclose($f);
}

function find_block($lista, $pos, $len){
    if($pos<=0) return [];
    $ini = ($pos-1)*$len;
    $tam = count($lista);
    $res = [];
    if($tam>$ini) 
        for($k=$ini,$i=0;$k<$tam && $i<$len;$k++,$i++) {
            $res[$i] = $lista[$k];
        }
    return $res;
}

function send_mail_retrieve_password($mail, $password){

    $myMail = "netofalso@gmail.com";
    $subject = "Password recovery Event Here";
 
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: Team Event Here! <'.$myMail.'>';
    $msg = "Your password Event Here was successfully changed!<br>".
           "This is your temporary password: <b>".$password."</b><br>".
           "You can change this password in settings.<br><br>Best regards,<br>".
           "<br><i>Team Event Here!</i></b>";
    
    mail($mail, $subject, $msg, $headers);
}

function generate_string(){
    $s = "ABCDEFGHYJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; 
    $res="";
    for($k=0;$k<10;$k++) $res .= $s{rand(0,61)};
    return $res;
}

?>
