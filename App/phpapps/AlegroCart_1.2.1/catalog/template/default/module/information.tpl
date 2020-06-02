
  <div class="headingcolumn"><h1><?php echo $heading_title; ?></h1></div>
  <div class="information">
    <?php foreach ($information as $info) { ?>
    <a href="<?php echo $info['href']; ?>"><?php echo $info['title']; ?></a>
    <?php } ?>
    <a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a> <a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></div>
<div class="columnBottom"></div>

