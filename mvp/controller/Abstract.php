<?php
class Controller_Abstract extends Controller_View {
  public function __construct(Router $router, View $view) {
    parent::__construct($router, $view);
    $this->load_topmenu();
  }
  public function load_marketprices() {
    $sql = "SELECT item.`id`,`name`,`description`,`image`,IFNULL(`price`,0) AS `price`,IFNULL(`change`,0) AS `change` FROM `item` LEFT JOIN `marketprice` ON `item`.`id`=`marketprice`.`item_id`";
    $this->marketprices = Database::fetch($sql);
  }
  public function load_sortby_options() {
    $this->sortby_options = [
      ['text' => 'Newest first', 'value'=> 'date-desc'],
      ['text' => 'Newest last', 'value'=> 'date-asc'],
      ['text' => 'Higher price first', 'value'=> 'price-desc'],
      ['text' => 'Higher price last', 'value'=> 'price-asc'],
    ];
  }
  public function load_categories() {
    $model = new Model_Category;
    $this->categories = $model->all();
  }
  public function load_categories_items() {
    $model = new Model_Category;
    $categories = $model->all();
    $sql = "SELECT `item`.`id`,`item`.`category_id`,`item`.`name`,`item`.`description`,`item`.`image`,IFNULL(`price`,0) AS `price`,IFNULL(`change`,0) AS `change` FROM `item`";
    $sql .= " LEFT JOIN `marketprice` ON `item`.`id`=`marketprice`.`item_id`";
    $sql .= " JOIN `category` ON `item`.`category_id`=`category`.`id`";
    $sql .= " WHERE `item`.`category_id`=?";
    foreach($categories as &$cat) {
      $cat['items'] = Database::fetch($sql, null, ['params' => $cat['id']]);
    }
    $this->categories_items = $categories;
  }
}
