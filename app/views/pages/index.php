<?php require APP_ROOT . '/views/inc/header.php'; ?>
<h1><a href="<?php echo URL_ROOT; ?>"><?php echo SITE_NAME; ?></a></h1>
<div class="dropdown-btn">
    <div class="btn-group">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Select country
      </button>
      <div class="dropdown-menu">
          <?php foreach ($data->countries as $country):  ?>
            <a class="dropdown-item" href="<?php echo URL_ROOT; ?>pages/countryNumber/<?php echo $country; ?>"><?php echo $country; ?></a>
          <?php endforeach; ?>
      </div>
    </div>
    <div class="btn-group">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Select State
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="<?php echo URL_ROOT; ?>pages/stateNumber/valid">Valid phone numbers</a>
        <a class="dropdown-item" href="<?php echo URL_ROOT; ?>pages/stateNumber/invalid">Invalid phone numbers</a>
      </div>
    </div>
</div>
<table class="table">
  <thead class="thead-light text-center">
    <tr>
      <th scope="col">Country</th>
      <th scope="col">State</th>
      <th scope="col">Country Code</th>
      <th scope="col">Phone num</th>
    </tr>
  </thead>
  <tbody>
<?php if (!empty($data->numbers)): ?>
    <?php foreach ($data->numbers as $number):  ?>
        <tr class="text-center">
          <td><?php echo $number['country']; ?></td>
          <td><?php echo $number['state'] === true ? 'OK' : 'NOK'; ?></td>
          <td><?php echo '+'.$number['countryCode']; ?></td>
          <td><?php echo $number['phoneNumber']; ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
        <tr class="text-center">
            <td colspan="4">-- No records found --</tr>
        </tr>
<?php endif; ?>
  </tbody>
</table>
<?php echo $data->pagination; ?>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>