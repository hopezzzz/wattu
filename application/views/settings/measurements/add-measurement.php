
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
                <div class="col-md-8 text-right">
                    <a href="<?php echo site_url();?>add-unit-of-measurement" class="btn btn-success btn-margin"> Add Measurement</a>
                </div>
              <div class="input-group input-group-sm col-md-4 pull-right">
                <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search">
                <div class="input-group-btn">
                  <button data-url="<?php echo site_url('unit-of-measurement');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <?php echo form_open('addUnitOfMeasurement', array('id' => 'unit-of-measurement', 'autocomplete' => 'off')); ?>
                <div class="row setup-content" id="step-1">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-5 form-group">
                        <label class="form-label"> Unit of Measurement </label>
                          <div class="form-group">
                            <input type="text" placeholder="Unit of Measurement" name="typeName" value="<?php if($result->typeName) echo $result->typeName;?>" class="form-control">
                          </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-md-5 ">
                      <input type="hidden" name="typeRef" value="<?php if($result->typeRef) echo $result->typeRef;?>">
                      <input type="submit" value="Save" class="btn btn-success pull-left">
                    </div>
                    </div>
                  </div>
                </div>
                <?php echo form_close(); ?>
                </div>
              </div>
            <!-- /.box-body -->

          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
