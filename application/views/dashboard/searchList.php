
<?php if (!empty($data['orders'])): ?>
<ul class="list-group">
<li><span>Orders</span></li>
<?php foreach ($data['orders'] as $key => $value): ?>
  <li class="list-item"> <a href="<?php echo base_url().'/order-details/'.$value['orderRef']; ?>"><?php echo $value['orderNo']; ?> &nbsp; Status :- <?php echo orderStatus($value['orderStatus']) ?></a> </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (count($data['customers']) > 0): ?>
  <ul class="list-group">
    <li><span>Customers List</span></li>
    <?php foreach ($data['customers'] as $key => $value): ?>
      <li class="list-item"> <a href="<?php echo base_url().'/view-customer/'.$value['customerRef']; ?>"><?php echo $value['contactName'] . ' &nbsp; Business Name:- ' . $value['businessName'];; ?></a> </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>


<?php if (!empty($data['users'])): ?>
  <ul class="list-group">
    <li><span>Users</span></li>
    <?php foreach ($data['users'] as $key => $value): ?>
      <li class="list-item"> <a href="<?php echo base_url().'/view-user/'.$value['userRef']; ?>"><?php echo $value['userName']; ?></a> </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php if (empty($data['users']) &&  empty($data['orders']) && empty($data['customers']) ) : ?>
    <ul class="list-group">
      <li><span>No Record found.</span></li>
    </ul>
<?php endif; ?>
