<!-- The file upload form used as target for the file upload widget -->

    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar here">
        <span class="btn btn-success fileinput-button">
            <span><i class="icon-plus icon-white"></i> Add files...</span>
            <input type="file" name="files[]" multiple >
        </span>
        <div class="clear"></div>
        <div id="fileupload-holder-message">
            <?php if (isset($images)): ?>
                <?php foreach ($images as $key => $image): ?>
                <li class="image_container">
                    <div>
                        <img width="100px" height="100px" src="<?php echo $image['url'] ?>" >
                        <a id="remove_image">Remove</a>
                        <input id="hidden_images" type="hidden" name="images[<?php echo $key ?>][image_id]" value="<?php echo $image['id'] ?>">
                    </div>
                <li>
            <?php endforeach ?>
            <?php endif ?>
        </div>
        <div class="clear"></div>
    </div>
