<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <style>
        .complete {
            text-decoration: line-through;
        }
    </style>

    <?php
    //połaczenie do bazy danych (host, login, hasło, baza)
    $db = new mysqli('localhost', 'root', '', 'shoppingList');

    //sprawdź czy otrzymałeś dane z formularza
    if (isset($_REQUEST['newThing']) && $_REQUEST['newThing'] != "") {
        //wysłano nową rzecz do dodania
        echo "Dodaje do listy";
        //składnia 1: "INSERT INTO list (id, thing) VALUES (NULL, 'ser')";
        //składnia 2: "INSERT INTO list VALUES (NULL, ser)"; <- koniecznie podać
        //                                                      wszystkie kolumny

        //tak nie robimy bo sql injection
        //$thing = $_REQUEST['newThing'];
        //$q = "INSERT INTO list VALUES (NULL, \'$thing\')";

        //sprawdz czy w tekscie jest przecinek
        if (strpos($_REQUEST['newThing'], ',')) {
            //wywołaj jeśli otrzymaliśmy listę po przecinku
            //zamień string na tablicę stringów dzieląc w miejscu przecinków
            $list = explode(',', $_REQUEST['newThing']);

            foreach ($list as $item) {
                //dla wszystkich elementów listy
                //tworzy kwerendę
                $q = $db->prepare("INSERT INTO list VALUES (NULL, ?, 0)");
                //podstawia wartości zamiast znaków zapytania
                $q->bind_param('s', $item);
                // 's' oznacza element tekstowy typu 'string'
                //wywołaj kwerendę
                $q->execute();
            }
        } else {
            //mamy tylko jeden element do dodania

            //tworzy kwerendę
            $q = $db->prepare("INSERT INTO list VALUES (NULL, ?, 0)");
            //podstawia wartości zamiast znaków zapytania
            $q->bind_param('s', $_REQUEST['newThing']);
            // 's' oznacza element tekstowy typu 'string'
            //wywołaj kwerendę
            $q->execute();
        }
    }

    //sprawdz czy otrzymaliśmy pozycję do usunięcia
    if (isset($_REQUEST['removeThing'])) {
        //tworzymy kwerendę
        $q = $db->prepare("DELETE FROM list WHERE id=?");
        //podstaw id jako int - stąd i
        $q->bind_param('i', $_REQUEST['removeThing']);
        $q->execute();
    }

    //sprawdz czy otrzymaliśmy pozycję do skreślenia
    if (isset($_REQUEST['completeThing'])) {
        echo "Skreślam pozycję";
        //tworzymy kwerendę
        $q = $db->prepare("UPDATE list SET complete=1 WHERE id=?");
        //podstaw id jako int - stąd i
        $q->bind_param('i', $_REQUEST['completeThing']);
        $q->execute();
    }
    //sprawdz czy otrzymaliśmy polecenia wyczyczenia listy
    if (isset($_REQUEST['clear'])) {
        echo "Czyczę listę";
        //tworzymy kwerendę
        $q = $db->prepare("TRUNCATE TABLE list");
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
    while ($row = $result->fetch_assoc()) {
        if ($row['complete']) {
            //już kupione
            echo '<li class="complete">';
        } else {
            //start list item
            echo '<li>';
        }
        //put list item name
        echo $row['thing'];
        //wygeneruj link do wywołania kodu usuwającego
        echo '<a href="index.php?removeThing=' . $row['id'] . '">
                <button>Usuń</button></a>';
        //wygeneruj link do wywołania kodu skreślającego
        echo '<a href="index.php?completeThing=' . $row['id'] . '">
                <button>Skreśl</button></a>';
        //end list item
        echo '</li>';
    }
    //end unordered list
    echo '</ul>';


    ?>
    <form action="index.php" method="get">
        <label for="newThing">Dodaj do listy:</label>
        <input type="text" name="newThing" id="newThing" required>
        <input type="submit" value="Dodaj">
    </form>
    <a href="index.php?clear">Wyczyść listę</a>
    <?php
    //debug, testy
    //echo '<pre>';
    //var_dump($_REQUEST);
    ?>
</body>

</html>