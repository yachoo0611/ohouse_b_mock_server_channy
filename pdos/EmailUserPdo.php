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



function loginEmailUserNickName($nickName)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT concat(nickName,+'3') as nickName FROM user where(EXISTS(SELECT nickName FROM user WHERE nickName=?)
    and nickName=?) ;";
    $st = $pdo->prepare($query);

    $st->execute([$nickName,$nickName]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}


function getUseridByEmail($email){
    $pdo = pdoSqlConnect();
    $query = "select userIdx from user where userEmail=?";


    $st = $pdo->prepare($query);

    $st->execute([$email]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["userIdx"]);

}
function updateUserProfile($userIntro,$userSite,$userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "update user set userIntro=?,userSite=? where userIdx=? ;";

    $st = $pdo->prepare($query);
    $st->execute([$userIntro,$userSite,$userIdx]);

    $st = null;
    $pdo = null;

}

function createUserScrap($userIdx,$productIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO userScrap (userIdx,productIdx) VALUES (?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$productIdx]);

    $st = null;
    $pdo = null;

}

function getPwByEmail($userEmail)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT concat(LEFT(userPw,6),'****') FROM user where userEmail=? ;
";
    $st = $pdo->prepare($query);

    $st->execute([$userEmail]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function getUserScrap($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select userScrap.productIdx,p.productImg from userScrap
inner join product p on userScrap.productIdx = p.productIdx
where userIdx=?;";
    $st = $pdo->prepare($query);

    $st->execute([$userIdx]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


function deleteUserScrap($userIdx,$productIdx)
{

        $pdo = pdoSqlConnect();
//    $data = [
//        "shop_idx" => $shop_idx,
//        "menu_name" => $menu_name,
//    ];
        $query = "DELETE FROM userScrap WHERE userIdx=? and productIdx=?;";


        $st = $pdo->prepare($query);
        $st->execute([$userIdx,$productIdx]);

        $st = null;
        $pdo = null;

}


function getProductBasket($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT product.productIdx,
       product.productImg,
       product.productName,
       sum(userBasket.productCount) as productCount,
       product.freeOrder,
       format(TRUNCATE(product.productPrice * userBasket.productCount * (0.01 * (100 - product.productDiscount)), -3),
              0)                    as productPrice
FROM product
         inner join userBasket on product.productIdx = userBasket.productIdx
where userBasket.userIdx = ?
group by product.productIdx;";
    $st = $pdo->prepare($query);

    $st->execute([$userIdx]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}



function getBasketPrice($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT concat(format(sum((userBasket.productCount) *
           TRUNCATE(product.productPrice * userBasket.productCount, -3)),0),'원')                                            as totalProductPrice,
       concat(format(sum((userBasket.productCount) * TRUNCATE(product.productPrice * userBasket.productCount, -3)) -
       sum((userBasket.productCount) *
           TRUNCATE(product.productPrice * userBasket.productCount * (0.01 * (100 - product.productDiscount)),
                    -3)),0),'원')                                                                                            as totalDiscount,
       concat(format(sum((userBasket.productCount) *
           TRUNCATE(product.productPrice * userBasket.productCount * (0.01 * (100 - product.productDiscount)),
                    -3)),0),'원')                                                                                            as totalPayPrice

FROM product
         inner join userBasket on product.productIdx = userBasket.productIdx
where userBasket.userIdx = ?;";
    $st = $pdo->prepare($query);

    $st->execute([$userIdx]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}


function updateUserBasket($productCount,$userIdx,$productIdx)
{
    $pdo = pdoSqlConnect();
    $query = "update userBasket set productCount=? where userIdx=? and productIdx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$productCount,$userIdx,$productIdx]);

    $st = null;
    $pdo = null;

}


function deleteUserBasket($userIdx,$productIdx)
{

    $pdo = pdoSqlConnect();
//    $data = [
//        "shop_idx" => $shop_idx,
//        "menu_name" => $menu_name,
//    ];
    $query = "DELETE FROM userBasket WHERE userIdx=? and productIdx=?;";


    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$productIdx]);

    $st = null;
    $pdo = null;

}