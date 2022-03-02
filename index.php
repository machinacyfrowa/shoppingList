<?php
//połaczenie do bazy danych (host, login, hasło, baza)
$db = new mysqli('localhost','root','','shoppingList');

//ręcznie szykujemy kwerendę
$q = "SELECT * FROM list";

//pobieramy qynik działania kwerendy $q
$result = $db->query($q);

//wyciągamy jeden wiersz z wyniku
//$row = $result->fetch_assoc();

//wyświetl pozycję z listy zakupów
//echo $row['thing'];

echo '<ul>';
while($row = $result->fetch_assoc()) {
    echo '<li>';
    echo $row['thing'];
    echo '</li>';
}
echo '</ul>';

//debug, testy
echo '<pre>';
var_dump($row);
?>