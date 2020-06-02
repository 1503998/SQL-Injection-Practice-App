<?php 
  $head_def->setcss($this->style . "/css/manufacturer.css");
  $head_def->setcss($this->style . "/css/paging.css");
  $head_def->setcss($this->style . "/css/product_cat.css");
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  $head_def->set_MetaTitle("Products by Manufacturer");
  $head_def->set_MetaDescription("Sort Products by Manufacturer");
  $head_def->set_MetaKeywords("products, manufacturer, brands");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';  
?>
<?php if ((isset($products) && $columns == 1) || (isset($products) && $display_options == FALSE)) { ?>
 <div class="headingbody"><?php echo $heading_title." - ". $manufacturer; ?></div>
 <div class="contentBody">
  <div class="manufacturer_filter">	
   <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data">
	<div class="top_entry">
	 <div class="left">
	  <?php echo $text_sort_by;?>
	  <div class="data">		  
	   <?php foreach($sort_filter as $filter){ ?>		    
	    <input type="radio" <?php if($default_filter == $filter){ ?>CHECKED <?php }?>name="sort_filter" value="<?php echo $filter;?>"><?php echo $filter;?>
	    <br>	
	   <?php }?>		  
	  </div>
	 </div>
	 <div class="floatleft">
	  <?php echo $text_order;?>
	  <div class="data">
	   <?php foreach($sort_order as $order){ ?>		    
	    <input type="radio" <?php if($default_order == $order){ ?>CHECKED <?php }?>name="sort_order" value="<?php echo $order;?>"><?php echo $order;?>
	    <br>	
	   <?php }?>
	  </div>
	 </div>	
	 <div class="right">
	  <?php if (count($models_data ) > 1){ ?>
	   <table>
	    <tr><td><?php echo $text_model; ?></td></tr>
		<tr><td>
		 <select name="model">
		  <option value="all" <?php if($model == "all"){?>selected <?php }?>><?php echo $text_all;?></option>
		  <?php foreach($models_data as $model_data){?>
		   <option value="<?php echo $model_data['model_value'];?>" <?php if($model_data['model'] == $model){?>selected <?php }?>><?php echo $model_data['model'];?></option>
		  <?php }?>
		 </select>
		</td></tr>
	   </table>
	  <?php }?>		
	 </div>
	 <div class="entry">
	  <input type="submit" value="Change Sort">
	 </div>			
	</div>
	<div class="divider"></div>
	<table><tr>
	 <td><div class="lowerleft">
	  <?php echo $text_page_rows;?>
	  <div class="data">
	   <input type="text" size="4" name="page_rows" value="<?php echo $default_page_rows;?>">
	  </div>
	 </div></td>
	 <td><div class="lowerleft">
	  <?php echo $text_max_rows;?>
	  <div class="data">
	   <input type="text" size="4" name="max_rows" value="<?php echo $default_max_rows;?>">
	  </div>
	 </div></td>	
	 <?php if($display_lock == False){ ?>
	  <td><div class="lowerright">
	   <?php echo $text_columns;?>
	   <div class="data">
	    <select name="columns">
		 <?php if($columns == 1){?>
		  <option value="1" selected>1</option>
		 <?php } else {?>
		  <option value="1">1</option>
		 <?php }?>
		 <?php if($columns == 3){?>
		  <option value="3" selected>3</option>
		 <?php } else {?>
		  <option value="3">3</option>
		 <?php }?>					
		 <?php if($columns == 4){?>
		  <option value="4" selected>4</option>
		 <?php } else {?>
		  <option value="4">4</option>
		 <?php }?>
		</select>
	   </div>
	  </div></td>
	 <?php } ?>
	</tr></table>
   </form>
  </div>	
 </div>
 <div class="contentBodyBottom">  </div>	
<?php }?>
<div id="manufacturer">
<?php if (isset($products)) { ?>
 <?php if($columns == 1){ ?>
  <?php include $shared_path . 'single_column.tpl'; ?>
 <?php } else { ?>
  <?php if($columns > 1){
   $heading_info = isset($heading_info) ? " - " . $heading_info : "";
   include $shared_path . 'multiple_columns.tpl';
  }?>
 <?php } ?>
 <?php include $shared_path . 'pagination.tpl'; ?>
<?php }?>
</div>
 <div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div> 