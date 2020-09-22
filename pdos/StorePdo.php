<?php

//READ
function getStoreCategory()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT DISTINCT categoryName,categoryImg FROM storeCategory ;
";
    $st = $pdo->prepare($query);

    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
function getStoreCategoryDetail($categoryName)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT categoryIdx,categoryDetail,categoryDetailImg
 FROM storeCategory where categoryName like concat('%',?,'%') ;";
    $st = $pdo->prepare($query);

    $st->execute([$categoryName]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getStoreCategoryDetail2($categoryName,$categoryDetail)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT DISTINCT categoryDetail2, ifnull(categoryDetalimg2,'사진이 없습니다') as categoryDetailImg2 FROM storeCategory
where categoryName like concat('%',?,'%') and categoryDetail like concat('%',?,'%')
limit 10 offset 0;";
    $st = $pdo->prepare($query);

    $st->execute([$categoryName,$categoryDetail]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
 
    return $res;
}

function getStoreCategoryDetail3($categoryName,$categoryDetail,$categoryDetail2)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT DISTINCT ifnull(categoryDetail3,'마지막 카테고리 페이지입니다') as categoryDetail3
FROM storeCategory
where categoryName like concat('%',?,'%') and categoryDetail like concat('%',?,'%')
and categoryDetail2 =?;";
    $st = $pdo->prepare($query);

    $st->execute([$categoryName,$categoryDetail,$categoryDetail2]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getStoreCategoryProduct($categoryName,$categoryDetail,$categoryDetail2,$categoryDetail3)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT  product.productIdx,
        product.productImg,
        product.brandName,
        product.productName,
        concat(product.productDiscount,'%') as productDiscount,
        format(TRUNCATE(product.productPrice*(0.01*(100-product.productDiscount)),-3),0) as productPrice,
        ifnull(round(avg(r.stargazer),1),3.0) as stargazer,
        concat('리뷰 ',format(count(r.stargazer),0)) as reviewCount,
        product.freeOrder,
        product.lowPrice

FROM product
         left outer join review r on product.productIdx = r.productIdx
where categoryName like concat('%',?,'%') and categoryDetail like concat('%',?,'%')
  and categoryDetail2 like concat('%',?,'%') and categoryDetail3 like concat('%',?,'%')
group by product.productIdx
limit 10 offset 0;";
    $st = $pdo->prepare($query);

    $st->execute([$categoryName,$categoryDetail,$categoryDetail2,$categoryDetail3]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getStoreCategoryProductCount($categoryName,$categoryDetail,$categoryDetail2,$categoryDetail3)
{
    $pdo = pdoSqlConnect();
    $query = "select concat('전체 ',format(count(productIdx),0)) as count FROM product
where categoryName like concat('%',?,'%') and categoryDetail like concat('%',?,'%')
    and categoryDetail2 like concat('%',?,'%') and categoryDetail3 like concat('%',?,'%');";
    $st = $pdo->prepare($query);

    $st->execute([$categoryName,$categoryDetail,$categoryDetail2,$categoryDetail3]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}


function getStorePopProduct()
{
    $pdo = pdoSqlConnect();

    $query = "SELECT  product.productIdx,
        product.productImg,
        product.brandName,
        product.productName,
        concat(product.productDiscount,'%') as productDiscount,
        format(TRUNCATE(product.productPrice*(0.01*(100-product.productDiscount)),-3),0) as productPrice,
        ifnull(round(avg(review.stargazer),1),3.0) as stargazer ,
        concat('리뷰 ',count(review.stargazer)) as reviewCount,
        product.freeOrder,
        product.lowPrice
FROM product
         left outer join review on product.productIdx =  review.productIdx
         inner join purchase p on product.productIdx = p.productIdx
group by p.productIdx order by count(p.productIdx) desc;";
//$pageNum=($pageNum-1)*10;
    $st = $pdo->prepare($query);
//    $st->bindParam(':pageNum',$pageNum,PDO::PARAM_INT);
    $st->execute();

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getProductReview($productIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select u.nickName,review.stargazer,review.createdAt,p.productName,review.reviewImg,review.reviewText,review.createdAt from review
inner join user u on review.userIdx = u.userIdx
inner join product p on review.productIdx = p.productIdx
where p.productIdx=?
group by review.userIdx
limit 3 offset 0;";

    $st = $pdo->prepare($query);
    $st->execute([$productIdx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function updateProductReview($reviewText,$reviewImg,$stargazer,$productIdx,$userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "update review set reviewText=?,reviewImg=?,stargazer=? where productIdx=? and userIdx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$reviewText,$reviewImg,$stargazer,$productIdx,$userIdx]);

    $st = null;
    $pdo = null;

}


function getStoreSaleProduct($categoryName)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT  product.productIdx,
        product.productImg,
        product.brandName,
        product.productName,
        concat(product.productDiscount,'%') as productDiscount,
        format(TRUNCATE(product.productPrice*(0.01*(100-product.productDiscount)),-3),0) as productPrice,
        ifnull(round(avg(review.stargazer),1),3.0) as stargazer ,
        concat('리뷰 ',count(review.stargazer)) as reviewCount,
        product.freeOrder,
        product.lowPrice
FROM product
         left outer join review on product.productIdx =  review.productIdx
         inner join purchase p on product.productIdx = p.productIdx
where product.productDiscount>=1 and product.categoryName=? and rand()
group by p.productIdx order by count(p.productIdx) desc
limit 5 offset 0;";

    $st = $pdo->prepare($query);

    $st->execute([$categoryName]);
//    $st->bindParam(':pageNum',$pageNum,PDO::PARAM_INT);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getStoreTodayDeal()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT product.productIdx,
       concat(timestampdiff(DAY, '2020-09-08', product.todayDeal), '일 남음')                      as remainTime,
       product.productImg,
       product.brandName,
       product.productName,
       concat(product.productDiscount, '%')                                                     as productDiscount,
       format(TRUNCATE(product.productPrice * (0.01 * (100 - product.productDiscount)), -3), 0) as productPrice,
       ifnull(round(avg(review.stargazer), 1), 3.0)                                             as stargazer,
       concat('리뷰 ', count(review.stargazer))                                                   as reviewCount,
       product.freeOrder,
       product.lowPrice
FROM product
         left outer join review on product.productIdx = review.productIdx
         inner join purchase p on product.productIdx = p.productIdx
where product.todayDeal is not null
  and now() < product.todayDeal
group by p.productIdx order by rand();";
//    $pageNum=($pageNum-1)*4;
    $st = $pdo->prepare($query);
//    $st->bindParam(':pageNum',$pageNum,PDO::PARAM_INT);
    $st->execute();

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}



function getStoreProductDetail($productIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select product.productIdx,
       product.productName,
       product.categoryName,
       product.categoryDetail,
       product.categoryDetail2,
       product.categoryDetail3,
    product.brandName,
       ifnull(round(avg(r.stargazer),1),3.0) as stargazer,
       concat(product.productDiscount,'%') as productDiscount,
       format(TRUNCATE(product.productPrice,-3),0) as realPrice,
       format(TRUNCATE(product.productPrice*(0.01*(100-product.productDiscount)),-3),0) as productPrice,
       format(TRUNCATE(product.productPrice*(0.0001*(100-product.productDiscount)),-3),0) as point,       
       product.freeOrder,
       product.lowPrice,
       pI.productDetailImg1

from product
inner join productImg pI on product.productIdx = pI.productIdx
left join review r on pI.productIdx = r.productIdx
where product.productIdx=?
group by product.productIdx;";
    $st = $pdo->prepare($query);

    $st->execute([$productIdx]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function getStoreProductInfo($productIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select 
       productInfoImg
from productInfo where productIdx=?;";
    $st = $pdo->prepare($query);

    $st->execute([$productIdx]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getStoreTodayDealProductDetail($productIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT  product.productIdx,
        product.categoryName,
        product.categoryDetail,
        product.categoryDetail2,
        product.categoryDetail3,
        product.brandName,
        concat('[오늘의 딜 ]',product.productName) as productName,
        concat(timestampdiff(DAY, '2020-09-08', product.todayDeal), '일 남음') as todayDeal,
        concat(product.productDiscount,'%') as productDiscount ,
        format(TRUNCATE(product.productPrice,-3),0) as realPrice,
        format(TRUNCATE(product.productPrice*(0.01*(100-product.productDiscount)),-3),0) as productPrice,
        format(TRUNCATE(product.productPrice*(0.0001*(100-product.productDiscount)),-3),0) as point,
        ifnull(round(avg(review.stargazer),1),3.0) as stargazer ,
        count(review.stargazer) as reviewCount,
        product.freeOrder,
        product.lowPrice,
              pI.productDetailImg1

FROM product
         left outer join review on product.productIdx =  review.productIdx
         inner join purchase p on product.productIdx = p.productIdx
         inner join productImg pI on product.productIdx = pI.productIdx

where product.todayDeal is not null and product.productIdx=?
group by p.productIdx order by count(p.productIdx) desc;";
    $st = $pdo->prepare($query);

    $st->execute([$productIdx]);


    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function createProductReview($productIdx,$userIdx,$reviewText,$reviewImg,$stargazer)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO review (productIdx, userIdx, reviewText, reviewImg, stargazer) VALUES (?,?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$productIdx,$userIdx,$reviewText,$reviewImg,$stargazer]);

    $st = null;
    $pdo = null;

}

function createProductView($userIdx,$productIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO userRecentView (userIdx,productIdx) VALUES (?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$productIdx]);

    $st = null;
    $pdo = null;

}

function getProductView($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT  product.productIdx,
        product.productImg,
        product.brandName,
        product.productName,
        concat(product.productDiscount,'%') as productDiscount,
        format(TRUNCATE(product.productPrice*(0.01*(100-product.productDiscount)),-3),0) as productPrice,
        ifnull(round(avg(review.stargazer),1),3.0) as stargazer ,
        concat('리뷰 ',count(review.stargazer)) as reviewCount,
        product.freeOrder,
        product.lowPrice
FROM product
         left outer join review on product.productIdx =  review.productIdx
         inner join purchase p on product.productIdx = p.productIdx
         inner join userRecentView uRV on p.productIdx = uRV.productIdx
where uRV.UserIdx=? group by p.productIdx order by count(p.productIdx) desc
;
";
    $st = $pdo->prepare($query);

    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function createProductQuestion($productIdx,$userIdx,$questionType,$questionText)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO productQuestion (productIdx, userIdx, questionType, questionText) VALUES (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$productIdx,$userIdx,$questionType,$questionText]);

    $st = null;
    $pdo = null;

}

function getProductQuestion($productIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT concat(LEFT(u.nickName,3),'**') as nickName,
       productQuestion.productIdx,
       productQuestion.questionText,
       productQuestion.answerText,
       date_format(productQuestion.createdAt, '%Y.%m.%d') as createdAt,
       p.brandName,
       date_format(productQuestion.updatedAt, '%Y.%m.%d') as updatedAt
from productQuestion
inner join user u on productQuestion.UserIdx = u.userIdx
inner join product p on productQuestion.productIdx = p.productIdx
where productQuestion.productIdx=?;
";
    $st = $pdo->prepare($query);

    $st->execute([$productIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}



function createProductBasket($userIdx,$productIdx,$productCount)
{
    $pdo = pdoSqlConnect();
    try {
        $pdo->beginTransaction();


        $query = "INSERT INTO userBasket (userIdx,productIdx,productCount) VALUES (?,?,?);    ";
        $st=$pdo->prepare($query);
        $st->execute([$userIdx,$productIdx,$productCount]);

        $query="select if(product.productQuantity-uB.productCount<0,0,1) AS count from product
inner join userBasket uB on product.productIdx = uB.productIdx
where product.productIdx=? and uB.userIdx=?
  and uB.basketIdx=(select max(uB.basketIdx) from userBasket where uB.userIdx=?);
";
        $st=$pdo->prepare($query);
        $st->execute([$productIdx,$userIdx,$userIdx]);
        $pdo->commit();
        $res = (object)Array();
        $res->code=200;
        $res->message="장바구니 저장 성공";
        echo json_encode($res,JSON_NUMERIC_CHECK);
    }
    catch (Exception $e){
        $pdo->rollBack();
        $res = (object)Array();
        echo $e->getMessage();
        $res->code=100;
        $res->message="장바구니 상품의 개수가 잔여 상품 개수보다 많습니다";
        echo json_encode($res,JSON_NUMERIC_CHECK);
        return $res;

    }
    $st=null;
    $pdo=null;
}

function createUserPurchase($userIdx,$productIdx,$productCount,$phoneNumber,$address,$request)
{
    $pdo = pdoSqlConnect();
    try {
        $pdo->beginTransaction();

//구매테이블에 저장됨과 동시에 (insert)
        $query="INSERT INTO purchase (userIdx,productIdx,productCount,phoneNumber,address,request)
 VALUES (?,?,?,?,?,?);";
        $st=$pdo->prepare($query);
        $st->execute([$userIdx,$productIdx,$productCount,$phoneNumber,$address,$request]);
//상품테이블에 남은 잔고 수량이 줄고 (update)
        $query="update product inner join purchase on product.productIdx = purchase.productIdx
set product.productQuantity=product.productQuantity-purchase.productCount
where purchase.productIdx=? and purchase.userIdx=? and purchase.purchaseIdx=(select max(purchase.purchaseIdx) from purchase where purchase.userIdx=?) ;
";
        $st=$pdo->prepare($query);
        $st->execute([$productIdx,$userIdx,$userIdx]);
//장바구니에 있는 상품이 사라진다. (delete)
        $query="DELETE FROM userBasket WHERE userIdx=? and productIdx=?;";
        $st=$pdo->prepare($query);
        $st->execute([$userIdx,$productIdx]);
//유저테이블에 포인트가 쌓인다 (update)
        $query="update user inner join purchase p on user.userIdx = p.userIdx
    inner join product p2 on p.productIdx = p2.productIdx
set user.userPoint = user.userPoint + format(TRUNCATE(p2.productPrice*(0.01*(100-p2.productDiscount)),-3),0)
where user.userIdx=? and p2.productIdx=? ;";
        $st=$pdo->prepare($query);
        $st->execute([$userIdx,$productIdx]);


        $pdo->commit();
        $res = (object)Array();
        $res->code=200;
        $res->message="트랜잭션 성공";
        echo json_encode($res,JSON_NUMERIC_CHECK);
    }
    catch (Exception $e){
        $pdo->rollBack();
        $res = (object)Array();
        echo $e->getMessage();
        $res->code=100;
        $res->message="트랜잭션실패";
        echo json_encode($res,JSON_NUMERIC_CHECK);
        return $res;

    }
    $st=null;
    $pdo=null;

}