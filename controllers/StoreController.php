<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {



        case "getStoreCategory":
            http_response_code(200);
            $res->result = getStoreCategory();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "카테고리 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
}catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
