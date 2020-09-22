<?php

//READ

function isValidUserEmail($userEmail){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM user WHERE userEmail= ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userEmail]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function isValidUserNickName($nickName){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM user WHERE nickName= ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$nickName]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function isValidUser($userEmail, $userPw){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM user WHERE userEmail= ? AND userPw = ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userEmail, $userPw]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}


function isValidUserPw( $userPw){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM user WHERE userPw = ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([ $userPw]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}


function isVaildCategoryName($categoryName){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT categoryName FROM storeCategory WHERE categoryName = ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$categoryName]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function isVaildCategoryDetail($categoryDetail){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT categoryDetail FROM storeCategory WHERE categoryDetail = ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$categoryDetail]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function isVaildCategoryDetail2($categoryDetail2){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT categoryDetail2 FROM storeCategory WHERE categoryDetail2 = ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$categoryDetail2]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function isVaildCategoryDetail3($categoryDetail3){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT categoryDetail3 FROM storeCategory WHERE categoryDetail3 = ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$categoryDetail3]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}


function validProductIdx($userIdx,$productIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT productIdx FROM userScrap WHERE userIdx = ? and productIdx=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdx,$productIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function isValidReview($productIdx,$userIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT reviewIdx FROM review WHERE productIdx=? AND userIdx = ? ) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$productIdx,$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function isVaildRecentView($userIdx,$productIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM userRecentView WHERE userIdx= ? and productIdx=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdx,$productIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function isValidProduct($productIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM product WHERE productIdx=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$productIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);



}
function isValidProductQuantity($productIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT productQuantity as exist FROM product WHERE productIdx=?;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$productIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
function isValidProductCount($productCount){
    $pdo = pdoSqlConnect();
    $query = "SELECT productQuantity as exist FROM product WHERE productIdx=?;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$productCount]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function isValidbasket($userIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT userIdx as exist FROM userBasket WHERE userIdx=?;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}


function isValidbasket2($userIdx,$productIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT productIdx as exist FROM userBasket WHERE userIdx=? and productIdx=?;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdx,$productIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}