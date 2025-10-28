<?php

class DBException extends Exception
{
  public function __construct(string $message, Throwable $previous)
  {
    parent::__construct($message, 10, $previous);
  }
}

//csinálunk egy osztályt, ami kiterjeszti az exeption osztályt, és annak az ősosztálynak a construcorával feltölteni. saját hibát tudunk dobni, amennyiben arra szeretnénk reagálni.
