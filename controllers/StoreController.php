<?php
require 'function.php';
date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8');
const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (object)array();
header('Content-Type: json;charset=UTF-8');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {

        case "getStoreCategory":
            http_response_code(200);
            if($res->result = getStoreCategory()){
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "카테고리 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
            }
            else{
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "카테고리 조회 실패";
            }
            break;
        case "getStoreCategoryDetail":
            http_response_code(200);
            $categoryName = $_GET['categoryName'];
            if(!isVaildCategoryName($categoryName)){
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "categoryName이 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result = getStoreCategoryDetail($categoryName);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "카테고리 상세 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        case "getStoreCategoryDetail2":
            http_response_code(200);


            $categoryName = $_GET['categoryName'];
            $categoryDetail = $_GET['categoryDetail'];
            if(!isVaildCategoryName($categoryName)){
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "categoryName이 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!isVaildCategoryDetail($categoryDetail)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "categoryDetail이 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getStoreCategoryDetail2($categoryName,$categoryDetail);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "카테고리 상세 2 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getStoreCategoryDetail3":
            http_response_code(200);
            $categoryName = $_GET['categoryName'];
            $categoryDetail = $_GET['categoryDetail'];
            $categoryDetail2 = $_GET['categoryDetail2'];

            if(!isVaildCategoryName($categoryName)){
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "categoryName이 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!isVaildCategoryDetail($categoryDetail)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "categoryDetail이 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!isVaildCategoryDetail2($categoryDetail2)){
                $res->isSuccess = FALSE;
                $res->code = 102;
                $res->message = "categoryDetail2이 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getStoreCategoryDetail3($categoryName, $categoryDetail, $categoryDetail2);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "카테고리 상세 3 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getStoreCategoryProduct":
            http_response_code(200);
            $categoryName = $_GET['categoryName'];
            $categoryDetail = $_GET['categoryDetail'];
            $categoryDetail2 = $_GET['categoryDetail2'];
            $categoryDetail3 = $_GET['categoryDetail3'];

            $res->count = getStoreCategoryProductCount($categoryName,$categoryDetail,$categoryDetail2,$categoryDetail3);
            $res->result =getStoreCategoryProduct($categoryName,$categoryDetail,$categoryDetail2,$categoryDetail3);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "카테고리별 상품 조회 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getStorePopProduct":
            http_response_code(200);
//            $pageNum=$vars['pageNum'];
//            $categoryName = $_GET['categoryName'];
//            $categoryDetail = $_GET['categoryDetail'];
//            $categoryDetail2 = $_GET['categoryDetail2'];
//            $categoryDetail3 = $_GET['categoryDetail3'];

            $res->result =getStorePopProduct();
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "인기 상품 조회 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getStoreSaleProduct":
            http_response_code(200);
            $categoryName = $_GET['categoryName'];
//            $pageNum=$_GET['pageNum'];
            if(!isVaildCategoryName($categoryName)){
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "categoryName이 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result =getStoreSaleProduct($categoryName);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "#지금은 할인 중 상품 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getStoreTodayDeal":
            http_response_code(200);
//            $pageNum=$vars['pageNum'];
            $res->result =getStoreTodayDeal();
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "오늘의 딜 상품 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



        case "getStoreProductDetail":
                http_response_code(200);
                $productIdx=$vars['productIdx'];
                if(is_null(getStoreProductDetail($productIdx))){

                    $res->isSuccess = FALSE;
                    $res->code = 100;
                    $res->message = "없는 상품입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
                else {


                    $res->result = getStoreProductDetail($productIdx);
                    $res->isSuccess = TRUE;
                    $res->code = 200;
                    $res->message = "상품 세부 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
        case "getStoreProductInfo":
            http_response_code(200);
            $productIdx=$vars['productIdx'];
            if(is_null(getStoreProductInfo($productIdx))){

                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "없는 상품입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else {


                $res->result = getStoreProductInfo($productIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "상품 세부 정보 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        case "getStoreTodayDealProductDetail":
            http_response_code(200);
            $productIdx=$vars['productIdx'];
            if(is_null(getStoreTodayDealProductDetail($productIdx))){

                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "오늘의 딜 상품이 아니거나 없는 상품입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else {


                $res->result = getStoreTodayDealProductDetail($productIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "오늘의 딜 상품 세부 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        case "getProductReview":
            http_response_code(200);
            $productIdx=$vars['productIdx'];
            if(is_null(getProductReview($productIdx))){

                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "상품에 리뷰가 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else {


                $res->result = getProductReview($productIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "상품 리뷰 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        case "createProductReview":
            http_response_code(200);

            $productIdx=$vars['productIdx'];
            // 1. JWT 유효성검사
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            // 2. Payload 에서 userIdx 추출
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $email = $data->userEmail;
            $userIdx =getUseridByEmail($email);

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            elseif (isValidReview($productIdx,$userIdx)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "이미 이 상품에 대한 리뷰를 작성하셨습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                break;
            }
            else{
            createProductReview($productIdx,$userIdx,$req->reviewText,$req->reviewImg,$req->stargazer);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "리뷰등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;}



        case "updateProductReview":
            http_response_code(200);
            $productIdx=$vars['productIdx'];
            // 1. JWT 유효성검사
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            // 2. Payload 에서 userIdx 추출
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $email = $data->userEmail;
            $userIdx =getUseridByEmail($email);

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            updateProductReview($req->reviewText,$req->reviewImg,$req->stargazer,$productIdx,$userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "상품 리뷰 변경에 성공하였습니다";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        case "createProductView":
            http_response_code(200);

//            $productIdx=$vars['productIdx'];
            // 1. JWT 유효성검사
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            // 2. Payload 에서 userIdx 추출
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $email = $data->userEmail;
            $userIdx =getUseridByEmail($email);

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            elseif (isVaildRecentView($userIdx,$req->productIdx)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "이미 등록된 최근 본 상품입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            else{
                createProductView($userIdx,$req->productIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "최근 본 상품 등록 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;}

        case "getProductView":
            http_response_code(200);


            // 1. JWT 유효성검사
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            // 2. Payload 에서 userIdx 추출
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $email = $data->userEmail;
            $userIdx =getUseridByEmail($email);

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            else{
                $res->result=getProductView($userIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "최근 본 상품 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;}

        case "createProductQuestion":
            http_response_code(200);

//            $productIdx=$vars['productIdx'];
            // 1. JWT 유효성검사
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            // 2. Payload 에서 userIdx 추출
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $email = $data->userEmail;
            $userIdx =getUseridByEmail($email);

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            elseif(!isValidProduct($req->productIdx)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "없는 상품입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            else{
                createProductQuestion($userIdx,$req->productIdx,$req->questionType,$req->questionText);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "상품 문의 등록 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;}

        case "getProductQuestion":
            http_response_code(200);
            $productIdx=$vars['productIdx'];
            if(is_null(getProductQuestion($productIdx))){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "없는 상품입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            else{

            $res->result =getProductQuestion($productIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "상품 문의 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;}
        case "createProductBasket":
            http_response_code(200);

//            $productIdx=$vars['productIdx'];
            // 1. JWT 유효성검사
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            // 2. Payload 에서 userIdx 추출
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $email = $data->userEmail;
            $userIdx =getUseridByEmail($email);

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            elseif(!isValidProduct($req->productIdx)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "없는 상품입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            else{
                createProductBasket($userIdx,$req->productIdx,$req->productCount);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "장바구니 등록 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;}

        case "createUserPurchase":
            http_response_code(200);

//            $productIdx=$vars['productIdx'];
            // 1. JWT 유효성검사
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            // 2. Payload 에서 userIdx 추출
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $email = $data->userEmail;
            $userIdx =getUseridByEmail($email);

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 100;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
//            elseif(!isValidProduct($req->productIdx)){
//                $res->isSuccess = FALSE;
//                $res->code = 101;
//                $res->message = "없는 상품입니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                addErrorLogs($errorLogs, $res, $req);
//                return;
//            }
            else{
                createUserPurchase($req->productIdx,$userIdx,$req->phoneNumber,$req->address);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "구매 등록 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;}


    }
}catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
