<?php
//A Controller adja meg az oldal funkcionalitását. Lényegében ez a réteg fogja össze a két előző réteget és adja hozzá a funkcionalitást amit az oldal tud.
//Fotnos, hogy a Controller a View és a Model feladatait csak felhasználhatja, de önmagában nem végezheti el: Ennek megfelelően, dierekt kiíratást nem végezhet a kimentere és direkt eléréssel nem kezelhet semmilyen külső adatforrást.

abstract class Controller{

  public static function ServRequest():void{//a kérésekhez megfelőlen megadja a válaszokat

  }

  //Ez egy visszatérési érték egy függvényből, és egy tömböt ad vissza. Ez a tömb, két kulcs-érték párból áll.
  private static function GetEmps():array{ //statikusság=osztályszintűség. vagyis az osztályhoz tartozik, nem az adott példányhoz(new). Tehát nem kell külön példányt létrehozni, hanem közvetlenül az osztályon belül használható.
    try{
      return Modell::GetEmps();
    }
    catch(Exception $ex){
      return array("error"=> true, "reason"=> $ex-> getMessage(), "realReason" => $ex->getPrevious()->getMessage());
      //ez egy asszociatív tömb, ami kulcs-érték párokat tartalmaz. "az error mezőp igaz(true)=error-kulcs"|true-érték|reason mező tartalmazza a hiba okát:"reason"->másik kulcs| $ex->getMessage()-a hiba üzenet maga
    }
  }


 //lekérdezés
  private static function SetEmp(array $input): array{
    try{
      $neededKeys=array("empno", "aname", "job", "mgr", "hiredate", "sal", "comm", "deptno"); //azt kell leelőnizrizni, hogy a paraméterül kapott input tömbbe megkaptunk-e mindent, ami szükséges a módosításhoz.
      $keys=array_keys($input); //milyen kulcsokat kaptunk
      $data=array(); //
      $ok=true; //alapérterlmezetten rendben vagyunk

      foreach($neededKeys as $key){// végig megyünk a kulcsokon
        if(!in_array($key,$keys)){
          $ok=false;
          break;
        }
        else{
          $data[$key]=$input[$key];
        }
      }
       //felvitel:
      if($ok){
        Modell::SetNewEmp($data);
        return array("error" => false, "reason" => "Hozzáadás sikeres!"); //ha minden ok, akkor fellehet tölteni az új dolgozót.
      }
      else{
        return array("error" => true, "reason"=>"Hiányzó adatok!"); //ha a kpott tömbbe hiányzik valami
      }
    }
    catch(Exception $ex){
      return array("error"=> true, "reason"=> $ex-> getMessage(), "realReason" => $ex->getPrevious()->getMessage());
    }
  }

  //Módosítás
  private static function ModEmp(array $input) : array {
    try{
      if(array_key_exists("empno", $input)){
        $empno=$input["empno"];
        unset($input["empno"]);
        Modell::UpdateEmp($empno, $input);
        return array("error"=>false, "reason" => "A módosítás sikeres");
      }
      else{
        return array("error" => true, "reason" => "Az empno megadása kötelező!");
      }
    }
    catch(Exception $ex){
      return array("error"=> true, "reason"=> $ex-> getMessage(), "realReason" => $ex->getPrevious()->getMessage());
    }
  }

  //törlés:
  private static function DelEmp(array $input) :array{
    try{
      if(array_key_exists("emmpno", $input)){
        Modell::DeleteEmp($input["empno"]);
        return array("error" => false, "reason" => "A törlés sikeres!");
      }
      else{
        return array("error" => true, "reason" => "Az empno megadása köteelző!");
      }

    }
    catch(Exception $ex){
       return array("error"=> true, "reason"=> $ex-> getMessage(), "realReason" => $ex->getPrevious()->getMessage());
    }
  }
}
