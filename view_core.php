<?php

abstract class View{
    private static string $output; //szöveges kimenet.

    //lekérdezzük az outoputot
    public static function getOutput() : string {
      return self::$output;
    }

    //beállítjuk

    public static function setOutput(string $output): void{
      self::$output=$output;
    }

    public static function RenderJSON(){
      ob_end_clean(); //kiüríti, majd mindig a legfrissebbet rakja bele
      header("Content-type: application/json");
      print self::$output;
    }

    public static function RenderXML(){
      ob_end_clea();
      header("Content-type: text/xml");
      print self::$output;
    }
}
