<?php
if (!empty($records)) {
  foreach ($records as $key => $value) {
    $blocks = explode(',',$value->blocks);
    $currentWeek = '<ul class="list-inline">';
    foreach ($blocks as $blockKey => $blockValue) {
      $currentWeek .= '	<li> ';
      // if ($key !='') {
        $currentWeek .='<strong>'.daysName($blockKey).'</strong>';
      // }
      $currentWeek .='<span><input disabled class="form-control number productionCount" name="days['.$blockKey.']" value="'.$blockValue.'" type="text"></span>
      </li> ';
    }
    $currentWeek .= '</ul>';

    ?>
<div class="this-week">
    <div class="col-md-12">
      <div class="week_blocks_panel">
        <div class="row">
          <div class="col-md-8">
            <h3><?php echo date('d-M-Y',strtotime($value->weekStartDate)); ?> To <?php echo date('d-M-Y',strtotime($value->weekEndDate)); ?></h3>
          </div>
        </div>
          <?php echo $currentWeek ?>
      </div>
    </div>
  </form>
</div>
<?php }
echo $paginationLinks;
} else echo "No Record Found;"?>
