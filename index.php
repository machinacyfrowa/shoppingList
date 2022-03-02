<?php
//połaczenie do bazy danych (host, login, hasło, baza)
$db = new mysqli('localhost','root','','shoppingList');

//sprawdź czy otrzymałeś dane z formularza
if(isset($_REQUEST['newThing']) && $_REQUEST['newThing'] != "" ) {
    //wysłano nową rzecz do dodania
    echo "Dodaje do listy";
    //składnia 1: "INSERT INTO list (id, thing) VALUES (NULL, 'ser')";
    //składnia 2: "INSERT INTO list VALUES (NULL, ser)"; <- koniecznie podać
    //                                                      wszystkie kolumny
    
    //tak nie robimy bo sql injection
    //$thing = $_REQUEST['newThing'];
    //$q = "INSERT INTO list VALUES (NULL, \'$thing\')";


    //tworzy kwerendę
    $q = $db->prepare("INSERT INTO list VALUES (NULL, ?)");
    //podstawia wartości zamiast znaków zapytania
    $q->bind_param('s', $_REQUEST['newThing']);
    // 's' oznacza element tekstowy typu 'string'
    //wywołaj kwerendę
    $q->execute();
}

//ręcznie szykujemy kwerendę
$q = "SELECT * FROM list";

//pobieramy qynik działania kwerendy $q
$result = $db->query($q);

//wyciągamy jeden wiersz z wyniku
//$row = $result->fetch_assoc();

//wyświetl pozycję z listy zakupów
//echo $row['thing'];

//start unordered list
echo '<ul>';
//loop  thru database result
while($row = $result->fetch_assoc()) {
    //start list item
    echo '<li>';
    //put list item name
    echo $row['thing'];
    //end list item
    echo '</li>';
}
//end unordered list
echo '</ul>';


?>
<form action="index.php" method="get">
<label for="newThing">Dodaj do listy:</label>
<input type="text" name="newThing" id="newThing">
<input type="submit" value="Dodaj">
</form>

<?php
//debug, testy
echo '<pre>';
var_dump($_REQUEST);
?>