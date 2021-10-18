
<?php
require_once('base_controller.php');
require_once('models/theme.php');

class PagesController extends BaseController
{
  function __construct()
  {
    $this->folder = 'pages';
  }

  public function home()
  {
    // $rules = Rule::all();
    // $ruleTop = array();
    // for($i=0;$i<count($rules);$i++) {
    //     $dataInsert = [
    //         'id' => $rules[$i]->id,
    //         'name_rule' => $rules[$i]->name_rule,
    //         'text_rule' => $rules[$i]->text_rule,
    //         'type_rule' => $rules[$i]->type_rule,
    //         'is_active' => $rules[$i]->is_active,
    //         'age' => $rules[$i]->age,
    //         'is_active_setting' => $rules[$i]->is_active_setting,
    //         'reg_date' => $rules[$i]->reg_date,
    //     ];
    //     $dataInsert['count_product'] = count(Rule::getProductByRule($rules[$i]->id));
    //     $ruleTop[] = $dataInsert;
    // }
    // $data = array('ruleTop' => $ruleTop);
    // $this->render('home', $data);
    $this->render('home');
  }

  public function error()
  {
    $this->render('error');
  }

  public function changeTheme()
  {
    set_time_limit(600);
    // Theme::changeThemeRegister();
    echo "Change Theme success";
  }

}