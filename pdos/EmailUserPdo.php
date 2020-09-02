<?php

//READ
function createEmailUser($userEmail,$userPw,$nickName)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO user (userEmail,userPw,nickName) VALUES (?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$userEmail,$userPw,$nickName]);

    $st = null;
    $pdo = null;

}

function loginEmailUser($userEmail,$userPw)
{
    $pdo = pdoSqlConnect();
    $query = "select nickName from user where userEmail=? and userPw=?;";

    $st = $pdo->prepare($query);
    $st->execute([$userEmail,$userPw]);

    $st = null;
    $pdo = null;

}
