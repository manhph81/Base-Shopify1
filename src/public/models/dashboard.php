<?php
class Dashboard
{
  public $id;
  public $name_dashboard;
  public $is_active;
  public $reg_date;

  function __construct($id, $name_dashboard, $is_active, $reg_date)
  {
    $this->id = $id;
    $this->name_dashboard = $name_dashboard;
    $this->is_active = $is_active;
    $this->reg_date = $reg_date;
  }

  static function all()
  {
    $list = [];
    $db = DB::getInstance();
    $req = $db->query('SELECT * FROM dashboard');

    if ($req) {
      foreach ($req->fetchAll() as $item) {
        $list[] = new Dashboard($item['id'], $item['name_dashboard'],$item['is_active'], $item['reg_date']);
      }
      return $list;
    }

    return null;
  }

  static function find($id)
  {
    $db = DB::getInstance();
    $req = $db->prepare('SELECT * FROM dashboard WHERE id = :id');
    $req->execute(array('rule_id' => $id));

    if ($req) {
        $list = [];
      foreach ($req->fetchAll() as $item) {
        $list[] = new Dashboard($item['id'], $item['name_dashboard'],$item['is_active'], $item['reg_date']);
      }
      return $list;
    }
    return null;
  }

  // static function insert($products, $rule_id = null, $is_active_rule = null)
  // {
  //   $status = "Fail";
  //   $db = DB::getInstance();
  //   //Product
  //   $sqlCreate = "INSERT INTO dashboard (product_id, rule_id, is_active_rule) VALUES(:product_id, :rule_id,1)";
  //   $reqCreate = $db->prepare($sqlCreate);

  //   if (isset($products)) {
  //     if (count($products) > 1) {
  //       foreach ($products as $pro) {
  //         if (trim($pro) !== "") {
  //           $isExistProduct = $db->prepare('SELECT product_id, rule_id FROM products WHERE product_id = :product_id' );
  //           $isExistProduct->execute(array('product_id' => trim($pro)));
  //           if (!$isExistProduct->rowCount() > 0) {
  //             //Insert Product
  //             $reqCreate->execute(array('product_id' => trim($pro), 'rule_id' =>  $rule_id));
  //           } else {
  //             $status .= trim($pro) . '-';
  //             //Insert Product != rule
  //             $rowProduct = $isExistProduct->fetchAll();
  //             $add = true;
  //             foreach ($rowProduct as $key => $value) {
  //               if (in_array($rule_id, array_values($value))) {
  //                 $id = $value["product_id"];
  //                 $add = false;
  //               }
  //             }
  //             if ($add == true) {
  //               $reqCreate->execute(array('product_id' => trim($pro), 'rule_id' =>  $rule_id));
  //             } else {
  //               $status = $id;
  //             }
  //           }

  //           if ($reqCreate->rowCount()) {
  //             $status = "OK";
  //           }
  //         } else {
  //           continue;
  //         }
  //       }
  //     } else {
  //       $status = "Null";
  //     }
  //   }

  //   return $status;
  // }

  static function delete($id)
  {
    $db = DB::getInstance();
    $sql = "DELETE FROM dashboard WHERE id=" . $id;
    $req = $db->prepare($sql);
    $req->execute(array('id' => $id));

    return "OK";
  }
}
