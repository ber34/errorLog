<?php
include("class-error-log.php");
 
  $logi = new error_logi($_SERVER);
   
  $mesage='Błąd log przypisujemy np. błąd pdo lub mysql
                    albo błąd logowania';
     $user='Adam ';
     $html=true;
   
  ## ustawiamy kiedy zapisujey logi uzytkownika ##
    $logi->user_logi_save($mesage,$user);
     $logi->user_logi_email($mesage, $user, $html);
 
  ## ustawiamy kiedy zapisujey logi globalne ##
     $logi->global_logi_save($mesage);
      $logi->global_logi_email($mesage, $html);
