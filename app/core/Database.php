<?php
class Database {
  private $dbhost = DB_HOST;
  private $dbname = DB_NAME;
  private $dbuser = DB_USER;
  private $dbpass = DB_PASSWORD;
  private $dbchar = DB_CHARSET;

  private $pdo;
  private $dsn;
  private $options;
  private $stmt;

  public function __construct() {
    $this->pdo = null;

    $this->dsn = "mysql:host=" . $this->dbhost . ";dbname=" . $this->dbname . ";charset=" . $this->dbchar;

    $this->options = [
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_CASE => PDO::CASE_LOWER
    ];

    try {
      $this->pdo = new PDO($this->dsn, $this->dbuser, $this->dbpass, $this->options);
    } catch(\PDOException $e) {
      throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    return $this->pdo;
  }

  public function query($sql) {
    $this->stmt = $this->pdo->prepare($sql);
  }

  public function bind($param, $value, $type = null) {
    if(is_null($type)) {
      switch(true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
          break;
      }
    }

    $this->stmt->bindValue($param, $value, $type);
  }

  public function execute() {
    return $this->stmt->execute();
  }

  public function results() {
    $this->execute();

    return $this->stmt->fetchAll();
  }

  public function fetchArray() {
    $this->execute();

    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function single() {
    $this->execute();

    return $this->stmt->fetch();
  }

  public function rowCount() {
    return $this->stmt->rowCount();
  }

  public function exists($table, $arr) {
    foreach($arr as $key => $value) {
      $this->query("SELECT * FROM " . $table . " WHERE " . $key . "='" . $value . "'");
    }

    $this->execute();

    return ($this->rowCount() > 0) ? true : false;
  }
}