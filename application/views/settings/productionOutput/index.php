<?php $currentWeek         = $lastWeek = $previousWeek = $thirdWeek = $fourthWeek = $SevenDays ='';
// echo "<pre>";print_r($productionOutput);die;
if (!empty($productionOutput)) {
	$currentDate    = date('Y-m-d H:i:s');
	$nextMonday    	= date('Y-m-d H:i:s',strtotime('+ 6 day'));
	$currentWeekDay = date('Y-m-d H:i:s',strtotime('-1 monday'));
	$current2ndWeek = date('Y-m-d H:i:s',strtotime('-2 monday'));
	$current3rdWeek = date('Y-m-d H:i:s',strtotime('-3 monday'));
	$current4thWeek = date('Y-m-d H:i:s',strtotime('-4 monday'));
	foreach ($productionOutput as $key => $value) {
		  // echo "<pre>";print_r($value);
		 // echo $current2ndWeek.'<br>';
		 // echo $currentWeekDay.'</br>';
		 // echo "$value->weekStartDate >= $currentDate   && $value->weekEndDate <= $nextMonday   <br>";;die;
		// echo "$past7Days >=  $value->addedOn  && $past7Days <= $value->addedOn)";die;
		// echo "$currentDated >=  $value->addedOn  && $currentDatedd <= $value->addedOn)";

		if ($value->weekStartDate <=  $currentDate   && $value->weekEndDate >= $currentDate   )
		{
			$blocks = explode(',',$value->blocks);
			$currentWeek = '<ul class="list-inline">';
			foreach ($blocks as $key => $blockValue) {
			$currentWeek .= '	<li>
					<strong>'.daysName($key).'</strong>
					<span><input class="form-control number productionCount" name="days['.$key.']" value="'.$blockValue.'" type="text"></span>
				</li> ';
			}
			$currentWeek .= '</ul>';

		}elseif ($value->weekStartDate >= $currentWeekDay   && $value->weekEndDate <= $currentDate   )
		{

			$blocks = explode(',',$value->blocks);
			$lastWeek = '<ul class="list-inline">';
			foreach ($blocks as $key => $blockValue) {
			$lastWeek .= '	<li>
					<strong></strong>
					<span><input disabled class="form-control number productionCount" name="days['.$key.']" value="'.$blockValue.'" type="text"></span>
				</li> ';
			}
			$lastWeek .= '</ul>';
		}
		elseif ($value->weekStartDate >= $current2ndWeek   && $value->weekEndDate <= $currentDate   )
		{
			$blocks = explode(',',$value->blocks);
			$thirdWeek = '<ul class="list-inline">';
			foreach ($blocks as $key => $blockValue) {
			$thirdWeek .= '	<li>
					<strong></strong>
					<span><input disabled class="form-control  number productionCount" name="days['.$key.']" value="'.$blockValue.'" type="text"></span>
				</li> ';
			}
			$thirdWeek .= '</ul>';
		}
		elseif ($value->weekStartDate >= $current3rdWeek   && $value->weekEndDate <= $currentDate   )
		{
			$blocks = explode(',',$value->blocks);
			$fourthWeek = '<ul class="list-inline">';
			foreach ($blocks as $key => $blockValue) {
			$fourthWeek .= '	<li>
					<strong></strong>
					<span><input disabled class="form-control  number productionCount" name="days['.$key.']" value="'.$blockValue.'" type="text"></span>
				</li> ';
			}
			$fourthWeek .= '</ul>';
		}
		else {
			$blocks = explode(',',$value->blocks);
			$elseWeek = '<ul class="list-inline">';
			foreach ($blocks as $key => $blockValue) {
			$elseWeek .= '	<li>
					<strong></strong>
					<span><input disabled class="form-control  number productionCount" name="days['.$key.']" value="'.$blockValue.'" type="text"></span>
				</li> ';
			}
			$elseWeek .= '</ul>';
		}
}
}
	?>
	<div class="row">
		<div class="col-md-12">
			<section class="sale_panel">
				<div class="this-week">
					<form id="productionOutput" action="<?php echo base_url();?>addUpdateProductionOutput" method="post" autocomplete="off">
						<div class="col-md-12">
							<div class="week_blocks_panel">
								<div class="row">
									<div class="col-md-8">
										<h2>This Week</h2>
									</div>
									<div class="col-md-4">
										<div class="text-right">
											<button type="submit" class="btn btn-primary">Save</button>
										</div>
									</div>
								</div>
								<?php if (trim($currentWeek) !=''){ ?>
									<?php echo $currentWeek ?>
								<?php }else{ ?>
									<ul class="list-inline">
										<li>
											<strong>Monday</strong>
											<span><input class="form-control number productionCount" name="days[0]" value="0" type="text"></span>
										</li>
										<li>
											<strong>Tuesday</strong>
											<span><input class="form-control number productionCount" name="days[1]" value="0" type="text"></span>
										</li>
										<li>
											<strong>Wednesday</strong>
											<span><input class="form-control number productionCount" name="days[2]" value="0" type="text"></span>
										</li>
										<li>
											<strong>Thursday</strong>
											<span><input class="form-control number productionCount" name="days[3]" value="0" type="text"></span>
										</li>
										<li>
											<strong>Friday</strong>
											<span><input class="form-control number productionCount" name="days[4]" value="0" type="text"></span>
										</li>
										<li>
											<strong>Saturday</strong>
											<span><input class="form-control number productionCount" name="days[5]" value="0" type="text"></span>
										</li>
										<li>
											<strong>Sunday</strong>
											<span><input class="form-control number productionCount" name="days[6]" value="0" type="text"></span>
										</li>
									</ul>
							<?php } ?>
							</div>
						</div>
					</form>
				</div>
				<?php

				/* ?>
				<?php if (trim($lastWeek)!='' ): ?>
					<div class="this-week">
						<div class="col-md-12">
							<div class="week_blocks_panel">
								<div class="row">
									<div class="col-md-12">
										<h2>Previous Week</h2>
									</div>
								</div>
								<?php echo $lastWeek ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if (trim($thirdWeek)!=''): ?>
					<div class="this-week">
						<div class="col-md-12">
							<div class="week_blocks_panel">
								<div class="row">
									<div class="col-md-12">
										<h2>Third Week</h2>
									</div>
								</div>
								<?php echo $thirdWeek ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if (trim($fourthWeek)!=''): ?>
					<div class="this-week">
						<div class="col-md-12">
							<div class="week_blocks_panel">
								<div class="row">
									<div class="col-md-12">
										<h2>Fourth Week</h2>
									</div>
								</div>
								<?php echo $fourthWeek ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
<?php */ ?>
			</section>
		</div>
	</div>
