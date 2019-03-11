<section class="dashboard">
    <div class="row">
        <div class="col-md-12">
          <div class="col-md-7">

          </div>
          <div class="col-md-2 padding-right">
            <div class="form-group">
              <label for=""></label>
                <input type="text" readonly placeholder="Start Date" id="startDate" value="<?php echo date('d-m-Y');?>" class="form-control success datepicker">
              </div>
            </div>
            <div class="col-md-2 padding-right">
            <div class="form-group">
              <label for=""></label>
                <input type="text" readonly placeholder="End Date" id="endDate" value="<?php echo date('d-m-Y',strtotime('+7 Days'));?>" class="form-control success datepicker">
            </div>
          </div>
          <div class="col-md-1 padding-right" style="vertical-align: middle;margin: 20px 0;">
            <a href="javascript:void(0)" class="btn btn-success filterStatistics" style="padding:5px 31px"> <i class="fa fa-search"></i> </a>
          </div>
        </div>
    </div>
    <div class="statisticsDate">

    </div>
    <div class="clearfix" style="margin:20px auto">

    </div>
</section>
