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
                    $res->message = "비밀번호는 8자 이상 16자 이하입니다.";
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
    }
}catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
