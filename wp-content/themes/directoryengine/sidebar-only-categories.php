<div class="col-md-3 col-xs-12 sidebar-main">
    <br/>
    <p class="title" >Categorías</p>
    <br/>
    <?php
    $arr = array(
        "parent" => 0,
        "style" => "horizontal",
        "hide_empty" => 0,
        "count" => 0,
        "number" => 15,
        "orderby" => "count");

    de_categories_list($arr); ?>
</div>