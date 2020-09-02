<?php

//READ
function getStoreCategory()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT categoryName,categoryImg FROM storeCategory where categoryIdx='1' or categoryIdx='10';";
    $st = $pdo->prepare($query);
//    $st->execute([$testNo]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}