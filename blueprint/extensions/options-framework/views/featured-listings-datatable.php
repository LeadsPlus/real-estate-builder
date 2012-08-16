<table id="<?php echo $dom_id ?>" class="widefat post fixed placester_properties" cellspacing="0">
    <thead>
      <tr>
        <th><span>Address</span></th>
        <?php if ( isset($image_preview) ): ?>
        <th><span>Image</span></th>  
        <?php endif ?>
        <th><span><?php echo $add_remove ?></span></th>
      </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
      <tr>
        <th></th>
        <?php if ( isset($image_preview) ): ?>
        <th></th>  
        <?php endif ?>
        <th></th>
      </tr>
    </tfoot>
  </table>