<?php 
  $head_def->setcss($this->style . "/css/thickbox.css");  
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  $head_def->set_javascript("thickbox/thickbox-compressed.js");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';

if (isset($products)) {
  if($columns > 1){
    $heading_info = isset($heading_info) ? " - " . $heading_info : "";
    include $shared_path . 'multiple_columns.tpl';
  }
  
} else { 
   echo '<p>' . $text_notfound . '</p>'; 
}
echo '<br>';
 ?>
