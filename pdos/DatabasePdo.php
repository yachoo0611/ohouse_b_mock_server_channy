<?php

//DB 정보
function pdoSqlConnect()
{
    try {
        $DB_HOST = "ohouse.cg5kpkttqhuk.ap-northeast-2.rds.amazonaws.com";
        $DB_NAME = "Ohouse";
        $DB_USER = "root";
        $DB_PW = "q1w2e3r4t5";
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PW);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}