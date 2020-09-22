<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {

        case "createEmailUser":
            http_response_code(200);


            $validEmail = '/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/';
//            $validNickName='';
            $validPw = '/^(?=.*[a-zA-Z])(?=.*[0-9]).{8,16}$/';

            if (empty($req->userEmail) || empty($req->nickName) || empty($req->userPw)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "입력을 확인해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if (isValidUserEmail($req->userEmail)) {
                    $res->isSuccess = FALSE;
                    $res->code = 201;
                    $res->message = "이미 '이메일 로그인'으로 가입하신 이메일입니다.'이메일 로그인'으로 로그인해주세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
                else if (!preg_match($validEmail, "$req->userEmail")) {
                    $res->isSucces = FALSE;
                    $res->code = 202;
                    $res->message = "이메일 형식이 올바르지 않습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
                elseif (isValidUserNickName($req->nickName)) {
                    $res->isSuccess = FALSE;
                    $res->code = 203;
                    $res->message = "사용 중인 별명입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
                else if (!preg_match($validPw, "$req->userPw")) {
                    $res->isSucces = FALSE;
                    $res->code = 204;
                    $res->message = "비밀번호는 숫자,영문 포함 8자 이상 16자 이하입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
                else {
                    createEmailUser($req->userEmail, $req->userPw, $req->nickName);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "user 생성 완료";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                $res->isSucces = FALSE;
                $res->code = 205;
                $res->message = "회원가입에 실패하였습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }


        case "loginEmailUser":
            http_response_code(200);



            if(!isValidUserEmail($req->userEmail)or empty($req->userEmail) ){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "존재하지 않는 이메일 입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(!isValidUserPw($req->userPw)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "비밀번호를 틀렸습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else{
                $jwt = getJWToken($req->userEmail, $req->userPw, JWT_SECRET_KEY);
                $res->jwt = $jwt;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "user 로그인 성공 & jwt 토큰 발급 완료";
                echo json_encode($res, JSON_NUMERIC_CHECK);}
            break;



        case "loginEmailUserNickName":
            http_response_code(200);
//            $nickName=$vars['nickName'];
//            $nickName = $_GET['nickName'];
            if(isValidUserNickName($req->nickName)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "사용 중인 별명입니다.";
                $res->result=loginEmailUserNickName($req->nickName,$req->nickName);
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            elseif(is_null($req->nickName)){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "닉네임을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else{

                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "적합한 닉네임입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);}
            break;



        case "updateUserProfile":
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

            updateUserProfile($req->userIntro,$req->userSite,$userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "유저 프로필 정보 변경에 성공하였습니다";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getUserScrap":
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
            elseif (getUserScrap($userIdx)==null){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "스크랩한 게시글이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }


            $res->result=getUserScrap($userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "유저 스크랩 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        case "createUserScrap":
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

            elseif(!isValidProduct($req->productIdx)or !is_numeric($req->productIdx)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "유효하지 않은 productIdx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;

            }
            elseif(validProductIdx($userIdx,$req->productIdx)){
                deleteUserScrap($userIdx,$req->productIdx);
                $res->isSuccess = FALSE;
                $res->code = 102;
                $res->message = "이미 저장된 productIdx이므로 삭제하였습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;

            }

            createUserScrap($userIdx,$req->productIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "스크랩 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;






        case "getUserPw":
//            $content=showPw($req->userEmail);
            http_response_code(200);
            include_once('/var/www/html/api-server/pdos/mailer.lib.php');
            if(!isValidUserEmail($req->userEmail)or empty($req->userEmail) ){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "등록되지 않은 이메일 입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }else {
                $pw=getPwByEmail($req->userEmail);
                $jbstr=implode(' ',$pw);
                mailer("오늘의 집", "dpcksdl3@naver.com", $req->userEmail, "[오늘의집] 비밀번호 재설정 안내","<h1>회원님, 안녕하세요.<br>비밀번호 안내 메일입니다</h1><br>
<h1>회원님의 비밀번호는----> $jbstr <----입니다.</h1><br><h2>*만약 본인이 비밀번호 재설정 신청을 한 것이 아니라면,
      본 메일을 무시해 주세요.<br> 회원님이 비밀번호를 변경하기 전에는 계정의 비밀번호는 바뀌지 않습니다.<br>
      *스마트폰에서 비밀번호 찾기가 잘 안되실 경우,<br>PC로 메일을 확인하시면 정상적으로 변경하실 수 있습니다.</h2>", 1);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "메일 전송 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        case "getProductBasket":
            http_response_code(200);

//              $productIdx=$vars['productIdx'];
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
            elseif(!is_numeric($userIdx) or !isValidbasket($userIdx)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "장바구니가 비어있습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            else{
                $res->result =getProductBasket($userIdx);
                $res->price =getBasketPrice($userIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "유저 장바구니 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;}

        case "updateUserBasket":
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
            elseif(!is_numeric($req->productIdx)){
                $res->isSuccess = FALSE;
                $res->code = 102;
                $res->message = "유효한 상품idx가 아닙니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            elseif(!is_numeric($req->productCount)){
                $res->isSuccess = FALSE;
                $res->code = 103;
                $res->message = "유효한 상품count가 아닙니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            elseif(!isValidbasket2($userIdx,$req->productIdx)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "장바구니에 저장되어있지 않은 상품idx입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            else{
            updateUserBasket($req->productCount,$userIdx,$req->productIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "장바구니 변경에 성공하였습니다";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;}


        case "deleteUserBasket":
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
            elseif(!is_numeric($userIdx) or !isValidbasket($userIdx)){
                $res->isSuccess = FALSE;
                $res->code = 101;
                $res->message = "장바구니가 비어있습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            else{

            deleteUserBasket($userIdx,$req->productIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "장바구니 삭제에 성공하였습니다";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;}
    }
}catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
