<div class="table-responsive">
  <table class="table table-striped">
        <thead>
              <tr>
                  <th>Sr No</th>
                  <th>Category Name</th>
                  <th>Category Type</th>
                  <th>Parent Category</th>
                  <th>Status</th>
                  <th>Actions</th>
              </tr>
        </thead>
    <tbody>
      <?php if (!empty($records)) {
          foreach ($records as $key => $value) { ?>
              <tr id="category_<?php echo $value->catRef;?>">
                    <td class="srNum"><?php echo $start + $key + 1; ?></td>
                  <?php if (trim($value->parentCatRef) !=''): ?>
                    <td><a href="javascript:void(0)" class="updateSubCategory" data-parentRef="<?php echo ($value->parentCatRef); ?>" data-ref="<?php echo ($value->catRef); ?>" data-name="<?php echo ucfirst($value->categoryName); ?>"><?php echo ucfirst($value->categoryName); ?></a></td>
                  <?php else: ?>
                    <td><a href="javascript:void(0)" class="updateCategory" data-ref="<?php echo ($value->catRef); ?>" data-name="<?php echo ucfirst($value->categoryName); ?>"><?php echo ucfirst($value->categoryName); ?></a></td>
                  <?php endif; ?>

                 <td>
                   <?php if (trim($value->parentCatRef) !=''){
                     $parentCategory  = getParentCategory($value->parentCatRef);
                     echo '<span class="label label-info">Sub Category</span>';
                   }
                   else
                        echo '<span class="label label-info">Parent Category</span>';
                   ?>
                </td>
                <td>
                  <?php
                  if (trim($value->parentCatRef) !='') {
                    echo $parentCategory->categoryName;
                  }
                   ?>
                </td>
                <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>


                    <td>
                      <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->categoryName);?>" data-type="category" data-ref="<?php echo $value->catRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                      <!-- &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="deleteRecord" data-name="<?php echo ucfirst($value->userName);?>" data-type="users" data-ref="<?php echo $value->catRef;?>"><i class="fa fa-times" aria-hidden="true"></i></a> -->
                    </td>
              </tr>
          <?php }
      }
      else
      { ?>
          <tr><td align="center" colspan="13">No Categories found.</td></tr>
<?php   } ?>
    </tbody>
    </table>
    <div class="">
      <?php echo $paginationLinks; ?>
    </div>
