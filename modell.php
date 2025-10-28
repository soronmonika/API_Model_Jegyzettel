<?php

//A Model kialakítása sorűn arra kell törekedni, hogy a rensszer minden olyan műveletet ami külső adattárból, vagy külső adattár fele végez, az ezen a komponensen keresztül menjen végbe. Tehát, a fájlok kezekésem külső források betöltése, adatbáziosok kezelése és minden egyéb adattároló és beolvasó művelet ezen a modulon keresztül megy végbe.
//A mModel-nek tehát biztosítani kell, hogy a renszserben értelmezett adatszerketernek megfelelően ad vissza adatot külső erőforrásból és fordítva is tehát, a belső adatszerkezetekben megkapott adatokat a külső adattárolók fele tudja továbbítani. oSZTÁLYOK(adatbázisok, fájl, logika)

//adatbázis műveletek megtörténnek.
abstract class Modell{ //azért abstract, hogy ne lehessen példányosítani
  private static PDO $con;

  //csatlakozás, hiba kezelés
  public static function Connenct() : void{
    global $cfg;

    //hiba kezelés fontos
    try{
      self::$con= new PDO("mysql:host={$cfg["dbhost"]};dbname={$cfg["dbdb"]}", $cfg["user"], $cfg["pass"]);
      //PDO:egy connection string segítségével csatlakozunk és a rendszert automatikusan beállítjuk arra, hogy a hibákat exception útján jelezze.
      self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //hogyan reagáljon a hibákra. ha valamelyik hiba van az adatbázis kapcsóldásával, és hogyan jelezze nekünk.

    }
    catch(Exception $ex){
      throw new DBException("Csatlakozás sikertelen!", $ex); //ha bármilyen hiba van az adatbázis csatlakozása során, azt mi a saját hiba dobással tudjuk kezelni.
    }
  }

  //lekérdezi a dolgozókat az adatbázisból

  public static function GetEmps():array{
    try{
      $sql="SELECT*FROM emp"; //készítünk egy rövid sql-t
      $result=self::$con->query($sql); //query-vel meghívjuk/futtatjuk. result-ot ad vissza
      $returnArray=$result->fetchAll(PDO::FETCH_ASSOC); // fetchAll-al kieszedjük egy asszociatív tömbe
      $result->closeCursor(); // lezárjuk, felszabadítjuk a memória helyét(engedi, hogy újra tudjuk futtatni a parancsot.)
      return $returnArray; //ezt adjuk vissza
    }
    catch(Exception $ex){
        throw new DBException("A listázás sikertelen!", $ex);
    }
  }

  //új dolgozó felvitel

  public static function SetNewEmp(array $empData) : void {
    try{
      //
      $sql="INSERT INTO emp VALUES (:empno, :ename, :job, :mgr, :hiradate, :sal, :comm, :deptno)"; //össze rakunk egy sql-t
      //értékeket beszúrni:
      $prep=self::$con->prepare($sql); //előkészítjük
      $prep->execute($empData); //feltöltjük az adatokat.  a sorernd fontos, mert akkor feltudja tölteni az értékkel
      $prep->closeCursor(); //vissza nem adunk semmit, egyszerűen lezárjuk.
    }
    catch(Exception $ex){
      throw new DBException("A beszúrás sikertelen!", $ex);
    }
  }

    //Módosítás:

    public static function UpdateEmp(int $empno, array $empData) :void{
       try{
          $sql="UPDATE emp SET ";
          for($i=0; $i<count(array_keys($empData)); $i++){

            $sql .= array_keys($empData)[$i] . " = :" . array_keys($empData)[$i];
            if($sql<count(array_keys($empData)) -1){
              $sql .=", ";
            }
          }
          $sql .= " WHERE empno =" .$empno;
                      //ENAME= :ename, HIRADATE= : hiredate
          $prep=self::$con->prepare($sql);
          $prep->execute($empData);
          $prep->closeCursor();
       }
       catch(Exception $ex){
        throw new DBException("Módosítás sikertelen!", $ex);
       }
    }

  //törlés:

  public static function DeleteEmp(int $empno): void{
    try{
      $sql="DELETE FROM emp WHERE empno=" . $empno;
      self::$con->exec($sql);
    }
    catch(Exception $ex){
      throw new DBException("A törlés siekrtelen!", $ex);
    }
  }



}
