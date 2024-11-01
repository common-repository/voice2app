<?php

namespace MpVoice2App;

class Properties {

  private $what;
  private $conn;
  private $lastId;

  public function __construct($what) {

    $pa = PropParent::getInstance();
    $this->conn = $pa->getCo();
//    $this->conn = new PDO_EXTEND();
    $this->what = $what;
  }

  public function run($query, $arrayParams = false) {
    $stmt = $this->conn->prepare($query);
    if ($arrayParams) {
      $stmt->execute($arrayParams);
    } else {
      $stmt->execute();
    }

    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    if (count($result) > 0) {
      if (count($result[0]) > 0) {
        if (count($result) > 1) {
          return $result;
        } else {
          return $result[0];
        }
      } else {
//        C_MpWr::e("Empty code");
        return FALSE;
      }
    } else {
//        C::e("Empty code");
      return FALSE;
    }
  }

  public function runInsert($query, $params = false) {
    $stmt = $this->conn->prepare($query);
    $done = FALSE;
    if ($params !== false) {
      $done = $stmt->execute($params);
    } else {
      $done = $stmt->execute();
    }
    return $done;
  }

  public function runUpdate($query, $arrayParams = false) {
    $stmt = $this->conn->prepare($query);
    $done = FALSE;
    if ($arrayParams !== false) {
      $done = $stmt->execute($arrayParams);
    } else {
      $done = $stmt->execute();
    }
    return $done;
  }

  /*
   * get
   * @param $data = array("w" => array("u_id","u_email",...), ["have" => array("u_pass" => "password",u_date => "2017:06:06",
   *        "order" => "", "limit => ""] );
   */

  public function get($data) {
    $table = $this->what;
    $values = "";
    $want = "";
    $prep = "";
    ksort($data);
    $hExists = false;
    if (array_key_exists("w", $data)) {
      foreach ($data["w"] as $k => $v) {
        $want .= $v . ",";
      }
      $want = rtrim($want, ",");
      $prep .= "SELECT $want FROM {$table} ";
      if (array_key_exists("h", $data)) {
        $hExists = TRUE;
        $num = count($data["h"]);
//        $prep .= " WHERE ";
        $gg = $this->prepOrAnd($data["h"]);
        $prep .= $gg[0];
        $values = $gg[1];
      }
      if (array_key_exists("orderD", $data)) {
        $prep .= " ORDER BY " . $data["orderD"] . " DESC ";
      } elseif (array_key_exists("orderA", $data)) {
        $prep .= " ORDER BY " . $data["orderA"] . " ASC ";
      }
      if (array_key_exists("limit", $data)) {
        $prep .= " LIMIT " . $data["limit"];
      }
      Db::$prep = $prep;
      Db::$values = $values;
//      echo $prep;
//      echo "=======================";
//      var_dump($values);
      $stmt = $this->conn->prepare($prep);
//      var_dump($values);
      if ($hExists) {
        $stmt->execute($values);
      } else {
        $stmt->execute();
      }

      $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);      //
//      Common::in_script([
//          'result' => $result
//      ]);
      if (count($result) > 0) {
        if (count($result[0]) > 0) {
          if (isset($data['limit']) && ((int) $data["limit"]) === 1) {
            return $result[0];
          }
          return $result;
//          if (count($result) > 1) {
//            return $result;
//          } else {
//            return $result[0];
//          }
//          if (count($result) > 1) {
//            return $result;
//          } else {
//            return $result[0];
//          }
        } else {
//          C_MpWr::e("Empty code");
          return FALSE;
        }
      } else {
//        C::e("Empty code");
        return FALSE;
      }
    } else {
//      C_MpWr::e("Invalid get code ");
      return FALSE;
    }
  }

  private $v;

  private function getOperator($v) {
    $prep = "";
    $operator = "=";
    $this->v = $v;
    $a = explode(Db::md, $v);
    if (count($a) === 2) {
      if ($a[0] === ">") {
        $operator = $a[0];
      } elseif ($a[0] === "<") {
        $operator = $a[0];
      }
      $this->v = $a[1];
    }
    return $operator;
  }

  private function getOperatorValue($v) {
    $value = $v;
    $this->v = $v;
    $a = explode(Db::md, $v);
    if (count($a) === 2) {
      $value = $a[1];
    }
    return $value;
  }

  /*
   * insert
   * $data = array("u_email" => $email, "u_fname" => $fname);
   */

  public function insert($data) {
    $table = $this->what;
    $place = "";
    $values = array();
    $arrBind = array();
    $prep = "INSERT INTO $table(";
    foreach ($data as $key => $val) {
      $prep .= "`$key`" . ",";
      $place .= ":$key,";
      $value[] = $val;
    }
    $prep = rtrim($prep, ",");
    $place = rtrim($place, ",");
    $prep .= ") VALUES(" . $place . ")";
    foreach ($data as $key => $val) {
      $arrBind[":$key"] = $val;
    }
//    echo $prep."\n"; var_dump($arrBind);
//    echo ($prep); var_dump("\n",$arrBind);
//    $c = new PdoExtend();

    $pa = PropParent::getInstance();
    $c = $pa->getCo();

    $stmt = $c->prepare($prep);
    $res = $stmt->execute($arrBind); //echo "last id ins = ".$c->lastInsertId();
    $this->lastId = $c->lastInsertId();
    Db::$insertedId = $c->lastInsertId();
    return $res;
  }

  /*
   * 
   * $data = array("w" => array("u_fname" => "first1", "u_email" => "new@new.com"), "h" => array("u_sname" => "sname"));
   * @return bool
   * @check  if(update === TRUE) 
   */

  public function update($data) {
    $table = $this->what;

    ksort($data);
    $filds = "";
    $where = "";
    $arrBind = array();
    if (array_key_exists("w", $data) && array_key_exists("h", $data)) {
      foreach ($data["w"] as $key => $val) {
        $filds .= " $key = :$key, ";
        $arrBind[":$key"] = $val;
      }
      $filds = rtrim($filds, " ,");
      foreach ($data["h"] as $key => $val) {
        $where .= "$key = :$key AND ";
        $arrBind[":$key"] = $val;
      }
      $where = rtrim($where, " AND ");



//        var_dump($where,$arrBind);

      $prep = "UPDATE $table SET $filds  WHERE $where ";
//      echo $prep, "<br />";
//      var_dump($arrBind, "<br />");

      try {
        $stmt = $this->conn->prepare($prep);
        $res = $stmt->execute($arrBind); //echo $res;
        return $res;
      } catch (Exception $ex) {
        echo "error = { ", $ex;
      }
    } else {
      echo "bad code";
    }
  }

  /*
   * delete
   * array( $col1 => $val1, $col2 => $val2, $col3 => $col3) 
   * 
   * $sql = "DELETE FROM movies WHERE filmID =  :filmID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':filmID', sanitize_text_field($_POST['filmID']), PDO::PARAM_INT);
    $stmt->execute();
   */

  public function delete($data) {
    $table = $this->what;
    $prep = "DELETE FROM $table WHERE ";
    $fields = "";
    $params = array();
    foreach ($data as $k => $v) {
      $fields .= "$k = :$k AND ";
      $params["$k"] = $v;
    }
    $fields = rtrim($fields, " AND ");
    $prep .= $fields;
//    var_dump($prep,$params);

    try {
      $stmt = $this->conn->prepare($prep);
      $res = $stmt->execute($params); //echo $res;
      return $res;
    } catch (Exception $ex) {
      echo "error = { ", $ex;
    }
  }

  public function getLastId() {
    return $this->lastId;
  }

  private function prepOrAnd($data) {
    $prep = "  WHERE (";
    $params = array();
    $one = 0;
    $leave = false;
    $prep1 = "";
//    var_dump($data);
    
    foreach ($data as $k => $v) {
      $cond = " AND ";
//  var_dump($v);echo "<br />";
      if (is_array($v)) {
        $levelOne = $v;
//        $prep1 = "";
        $comma = 0;    //dump($v);
        foreach ($v as $kk => $vv) {
          $cond1 = " AND ";
          if ($kk === Db::orr) {
            $cond1 = " OR ";
          }
          foreach ($vv as $kkk => $vvv) {
            if (is_array($vvv)) {
              $secondLayer = $this->prepOrAnd2($levelOne);
              $prep1 = $secondLayer[0] . " AND ";
//              var_dump("*************************",$prep1);
              $leave = TRUE;
//              var_dump("____________________________________",$params);
              $params = array_merge($params, $secondLayer[1]);
//              var_dump("____________________________________",$secondLayer[1]);
            } else {
              $prep1 .= ($comma === 0) ? "(" : "";
              $w_k = $one . $k . $comma . "param";
              $prep1 .= "`$kkk`" . " " . $this->getOperator($vvv) . " :" . $w_k . " $cond1 ";
              $params[$w_k] = $this->sanitizeData($this->getOperatorValue($vvv));
              $comma++;
            }
          }
        }
        if ($leave) {
          
        } else {
          $prep1 = rtrim($prep1, " AND OR ");
          $prep1 .= ") $cond ";
          $prep .= $prep1;
        }

//    echo $prep1."<br >";
      } else {
        $prep .= "`$k`" . " " . $this->getOperator($v) . " :" . $k . " $cond ";
        $params[":" . $k] = $this->sanitizeData($this->getOperatorValue($v));
      }


      $one++;
    }
    if ($leave) {
      $prep1 = rtrim($prep1, " AND OR ");
      $prep1 .= ") $cond ";
      $prep .= $prep1;
    }
    $prep = trim($prep, $cond);
    $prep .= ") ";
    return array($prep, $params);
//    var_dump($prep);
  }

  private function sanitizeData($a) {
    $a = htmlspecialchars($a);
    return $a;
  }

  private function prepOrAnd2($data) {
//    var_dump($data);
    foreach ($data as $k => $v) {
      $prep = " (";
      $count = 0;
      $params = array();
      if (is_array($v)) {
        foreach ($v as $kk => $vv) {
          $prep .= " (";
          foreach ($vv as $kkk => $vvv) {
            $pp = $kkk . $count;
            $prep .= " $kkk = :$pp AND ";
            $params[$pp] = $this->sanitizeData($vvv);
            $count++;
          }
          $prep = rtrim($prep, " AND ");
          $prep .= ") OR ";
        }
        $prep = rtrim($prep, " OR ");
      }
    }
    $prep .= " )";
//    var_dump($prep);
//    var_dump($params);
    return array($prep, $params);
  }

}
