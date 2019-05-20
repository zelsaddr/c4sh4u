<?php
/**
 * @package BOT CASH4U
 * @author Mazterin.Com <iam@zeldin.link>
**/
date_default_timezone_set("Asia/Jakarta");
class Execute {
    public $reff = 0;
    public $proxies;
    public function exe() {
        if(empty($this->reff) || empty($this->proxies)){
            echo "{~} ERROR: REFF IS EMPTY! or PROXIES FILE EMPTY\n";
            exit();
        }elseif(strlen($this->reff) > 7){
            echo "{~} ERROR: INVALID REFF!\n";
        }
        else{
            if(!file_exists($this->proxies) || !is_file($this->proxies)){
                echo "{~} PROXIES FILE NOT FOUND\n";
                exit();
            }
            $prox = explode("\n", file_get_contents($this->proxies));
            $headers = array();
            $headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
            $headers[] = "User-Agent: Dalvik/2.1.0 (Linux; U; Android 7.1.2; Redmi 4A MIUI/".rand(0,9).".".rand(0,9).".".rand(0,12).")";
            $headers[] = "Host: cash-4u.com";
            $headers[] = "Connection: Keep-Alive";
            while(1){
                $proxy = $prox[array_rand($prox)];
                $time = "[".date("d/m/Y H:i:s")."] ";
                $data = $this->generateUserData();
                $mail = str_replace("example.com", "gmail.com", $data['email']);
                $name = $data['name'];
                $pass = $data['password'];
                if(!empty($mail)){
                    $reg = $this->Curl("https://cash-4u.com//admin/app/regester.php?value=Adam", "mac_time=0c9".rand(83820, 99999)."90aR&fullName=".urlencode($name)."&tel=noTel&emailSign=".$mail."&passwordSign=".$pass."&", $headers, $proxy);
                    if(json_decode($reg)->success == true){
                        echo "[".date("d/m/Y H:i:s")."] "."{\$} SUCCESS REGISTER : ".$mail." | ".$pass."\n";
                        sleep(3);
                        $submitReff = $this->Curl("https://cash-4u.com//admin/app/send_referal.php?value=cash4u", "passwordLogIn=".$pass."&code_share_referal=".$this->reff."&emailLogIn=".$mail."&", $headers, $proxy);
                        if(json_decode($submitReff)->success == true){
                            echo "[".date("d/m/Y H:i:s")."] "."{\$} SUCCESS INPUT REFF"."\n";
                        }else{
                            echo "[".date("d/m/Y H:i:s")."] "."{\$} FAILED INPUT REFF"."\n";
                        }
                    }else{
                        echo "[".date("d/m/Y H:i:s")."] "."{~} ERROR: CANNOT REGISTER"."\n";
                        echo $proxy." | ".$reg."\n";
                    }
                }else{
                    echo "[".date("d/m/Y H:i:s")."] "."{~} ERROR: CANNOT GENERATE DATA"."\n";
                }
            }
        }
    }
    protected function generateUserData(){
        $get = $this->Curl("https://randomuser.me/api/?nat=us");
        $decode = json_decode($get, 1);
        return array(
            "name" => $decode['results'][0]['name']['first']." ".$decode['results'][0]['name']['last'],
            "password" => $decode['results'][0]['login']['password'],
            "email" => $decode['results'][0]['email']
            );
    }
    protected function Curl ($url, $post = 0, $headers = 0, $proxy = 0){
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
        );
        if($proxy){
            $options[CURLOPT_PROXY] = $proxy;
            $options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
        }
        if($post){
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $post;
        }
        if($headers){
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        curl_setopt_array($ch, $options);
        $exec = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }else{
            return $exec;
        }
    }
    
}