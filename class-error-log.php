<?php
#The MIT License
###############################
#Copyright (c) 2014 Adam Berger
###############################
#Permission is hereby granted, free of charge, to any person obtaining a copy
#of this software and associated documentation files (the "Software"), to deal
#in the Software without restriction, including without limitation the rights
#to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
#copies of the Software, and to permit persons to whom the Software is
#furnished to do so, subject to the following conditions:
###############################
#The above copyright notice and this permission notice shall be included in
#all copies or substantial portions of the Software.
###############################
#THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
#OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
#THE SOFTWARE.
##############################
 
/**
 * PHP 5.1
 */
   
/**
 * @version 1.00
 * error_logi
 * @author Adam Berger <ber34#o2.pl---->
 * Class allows you to save the log file error and send them to the e-mail,
 * The choice depends only on us, you can use both at once and save to a file and send an e-mail.
 * The class must specify the path to the file and assign e-mail address where you will send
 * Our logs with errors
 * Themselves we determine what we want to put there, and the class appends data
 * Such as ip, host, server, and a few others see for yourself.
 *
 * Klasa pozwala na zapisywanie błędów do plików .log i wysłaniu ich na wskazany e-mail,
 * Wybór zależy tylko od nas, można użyć obu na raz i zapis do pliku i wysłać e-mail.
 * W klasie należy podać ścieżkę do plików i przypisać e-mail gdzie nastąpi wysłanie
 * naszych logów z błędami
 * Sami ustalamy co chcemy tam umieścić, a klasa dopisuje dane
 * takie jak ip, host, serwer i jeszcze parę innych sami zobaczcie .
   
 */
 
class error_logi {
 
      const USER_DIR   = "user_errors.log";
      const GLOBAL_DIR = 'global_errors.log';
      const EMAIL      = 'example#gmail.com';
 
     private $logi;
     private $ip;
     private $serwer;
     private $data;
       
    public function __construct($SERVER) {
        if(!empty($SERVER)){
            $this->serwer[] = $SERVER;
          while(list($kkk, $jjj)=each($this->serwer[0])){
            if(array_key_exists($kkk, $this->serwer[0])){
               $this->serwer[$kkk]=$jjj;
        }else{
              $this->serwer = false;
            }
          }
        }
        $this->data = date('d-m-Y h:i:s');
    }
     
    private function global_ip(){
 if(filter_var($this->serwer['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE ) !== false){
       return  $this->ip = "Prywatny zakres ".$this->serwer['REMOTE_ADDR'];
     }else if(filter_var($this->serwer['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false){
        return  $this->ip = "IPV4 ".$this->serwer['REMOTE_ADDR'];
        }else if(filter_var($this->serwer['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false){
        return  $this->ip = "IPV6 ".$this->serwer['REMOTE_ADDR'];
     }
 }
    private function global_host(){
      if(!empty($this->serwer['REMOTE_ADDR'])){
         return gethostbyaddr($this->serwer['REMOTE_ADDR']);
      }
    }
     
     private function global_ip_server(){
      if(!empty($this->serwer['SERVER_ADDR'])){
         return $this->serwer['SERVER_ADDR'];
      }
    }
     
     private function global_agent(){
      if(!empty($this->serwer['HTTP_USER_AGENT'])){
         return $this->serwer['HTTP_USER_AGENT'];
      }
    }
     
     private function global_port(){
      if(!empty($this->serwer['REMOTE_PORT'])){
         return $this->serwer['REMOTE_PORT'];
      }
    }
     
    private function global_server_name(){
      if(!empty($this->serwer['SERVER_NAME'])){
         return $this->serwer['SERVER_NAME'];
      }
    }
      ### zapisujemy logi na serwerze w podanej ścieżce ###
 public function user_logi_save($mesage,$user){
         
    $this->logi = "|  Data:  ".$this->data."  |  User:  ".$user." | Wiadomosc: ".$mesage." | IP: ".$this->global_ip()."| Global Port: ".$this->global_port()." | Host: ".$this->global_host()."| Agent: ".$this->global_agent()."| IP Server: ".$this->global_ip_server()." \n";
    error_log($this->logi, 3, self::USER_DIR);
 }
   ### wysyłamy logi na e-mail z loginem usera ###
 public function user_logi_email($mesage, $user, $html=false){
     
     $this->logi = "
|  Logi z:  ".$this->global_server_name()."
|  User:  ".$user."
|  Data:  ".$this->data."
| Wiadomosc: ".$mesage."
| IP: ".$this->global_ip()."
| Global Port: ".$this->global_port()."
| Host: ".$this->global_host()."
| Agent: ".$this->global_agent()."
| IP Server: ".$this->global_ip_server()."";
    ### wiadomość html ###
   if($html === true){
     $html='';
     $html.='
<h2>Logi z '.$this->global_server_name().'</h2>
$html.='
<h3>Data: '.$this->data.'</h3>
 $html.='
<h3>User: '.$user.'</h3>
$html.='
<h3>IP: '.$this->global_ip().'</h3>
 $html.='
<h3>Global Port: '.$this->global_port().'</h3>
 $html.='
<h3>Host: '.$this->global_host().'</h3>
 $html.='
<h3>Agent: '.$this->global_agent().'</h3>
 $html.='
<h3>IP Server: '.$this->global_ip_server().'</h3>
$html.='
<h4>Wiadomosc: '.$mesage.'</h4>
 
 
 
 ### Wysyłamy w imieniu ber34#o2.pl z adresu ber34#o2.pl
 
error_log($html, 1, self::EMAIL,"subject :lunch\nContent-Type: text/html; charset=UTF-8; Foo\nFrom:".self::EMAIL."\n");
 
}else{
 
error_log($this->logi, 1, self::EMAIL,"subject :lunch\nContent-Type: text/html;charset=UTF-8; Foo\nFrom:".self::EMAIL."\n"); } }
 
### zapisujemy logi na serwerze w podanej ścieżce ###
 
public function global_logi_save($mesage){
 
$this->logi = "| Data: ".$this->data." | Wiadomosc: ".$mesage." | IP: ".$this->global_ip()."| Global Port: ".$this->global_port()."
 | Host: ".$this->global_host()."|
 Agent: ".$this->global_agent()."|
 IP Server: ".$this->global_ip_server()." \n";
 
error_log($this->logi, 3, self::GLOBAL_DIR); }
 
 ### wysyłamy logi na e-mail ###
 
public function global_logi_email($mesage, $html=false){ $this->logi = " | Logi z: ".$this->global_server_name()." |
 Data: ".$this->data." |
 Wiadomosc: ".$mesage." |
 IP: ".$this->global_ip()." |
 Global Port: ".$this->global_port()." |
 Host: ".$this->global_host()." |
 Agent: ".$this->global_agent()." |
 IP Server: ".$this->global_ip_server()." "; if($html === true){
 
 $html='';
 $html.='
<h2>Logi z '.$this->global_server_name().'</h2>
 $html.='
<h3>Data: '.$this->data.'</h3>
 $html.='
<h3>IP: '.$this->global_ip().'</h3>
 
 $html.='
<h3>Global Port: '.$this->global_port().'</h3>
 
 $html.='
<h3>Host: '.$this->global_host().'</h3>
 $html.='
<h3>Agent: '.$this->global_agent().'</h3>
 $html.='
<h3>IP Server: '.$this->global_ip_server().'</h3>
 $html.='
<h4>Wiadomosc: '.$mesage.'</h4>
 
 ### Wysyłamy w imieniu ber34#o2.pl z adresu ber34#o2.pl
 
    error_log($html, 1, self::EMAIL,"subject :lunch\nContent-Type: text/html; charset=UTF-8; Foo\nFrom: ".self::EMAIL."\n");
 
     }else{
 
    error_log($this->logi, 1, self::EMAIL,"subject :lunch\nContent-Type: text/html;charset=UTF-8; Foo\nFrom: ".self::EMAIL."\n"); 
   } 
  } 
 }
