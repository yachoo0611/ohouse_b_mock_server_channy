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



            if(isValidUserEmail($req->userEmail)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "이미 '이메일 로그인'으로 가입하신 이메일입니다.'이메일 로그인'으로 로그인해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif(isValidUserNickName($req->nickName)){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "사용 중인 별명입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else{

                $jwt = getJWToken($req->userEmail, $req->userPw, JWT_SECRET_KEY);
                $res->jwt = $jwt;
                createEmailUser($req->userEmail,$req->userPw,$req->nickName,$jwt);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "user 생성 완료 & jwt 토큰 발급 완료";

                echo json_encode($res, JSON_NUMERIC_CHECK);}
            break;



        case "loginEmailUser":
            http_response_code(200);


            // 1. JWT 유효성검사
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            // 2. Payload 에서 user_idx



            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            elseif(!isValidUserEmail($req->userEmail)){
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
                loginEmailUser($req->userEmail,$req->userPw);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "user 로그인 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);}
            break;
    }
}catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
