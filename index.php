<?php
//połaczenie do bazy danych (host, login, hasło, baza)
$db = new mysqli('localhost','root','','shoppingList');

//ręcznie szykujemy kwerendę
$q = "SELECT * FROM list";

//pobieramy qynik działania kwerendy $q
$result = $db->query($q);

//wyciągamy jeden wiersz z wyniku
$row = $result->fetch_assoc();

//wyświetl pozycję z listy zakupów
echo $row['thing'];


//debug, testy
echo '<pre>';
var_dump($row);
?>