jQuery(document).all ? jQuery(window) : jQuery(document).ready(function($) {
    var galleryFrame;
    // Set an image
    $('.album-upload-btn').on('click', function(e) {
        e.preventDefault();
        // If the media frame already exists, reopen it.
        if (galleryFrame) {
            galleryFrame.open();
            return;
        }
        // Create the media frame
        galleryFrame = wp.media({
            title: 'Select Images for Gallery',
            button: {
                text: 'Add to Gallery'
            },
            multiple: false, // Allow only one image selection
            library: {
                type: 'image' // Restrict selection to images only
            }
        });
        // When images are selected, run a callback
        galleryFrame.on('select', function() {
            var selection = galleryFrame.state().get('selection');
            var imageIds = [];
            var previewHtml = '';

            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                // Collect image attachment IDs
                imageIds.push(attachment.id);
                // Build a quick thumbnail preview for the backend
                var thumbnailUrl = attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                previewHtml += '<img src="' + thumbnailUrl + '"/>';
            });

            // Populate the input field with a comma-separated list of IDs
            $('#image_id').val(imageIds);
            // Update the preview and the buttons
            $('#upload-btn').addClass('album-admin-hide');
            $('#remove-btn').removeClass('album-admin-hide');
            $('#album-image-gallery-item-preview').html(previewHtml);
            $('#album-image-gallery-item-upload').addClass('album-admin-hide');
            $('#album-image-gallery-item-preview').removeClass('album-admin-hide');
        });

        // Finally, open the modal
        galleryFrame.open();
    });

    // Removing the image
    $('.album-remove-btn').on('click', function(e) {
        // Remove the image from the hidden imput
        $('#image_id').val('');
        // Update the preview and the buttons
        $('#album-image-gallery-item-preview').html('');
        $('#remove-btn').addClass('album-admin-hide');
        $('#upload-btn').removeClass('album-admin-hide');
        $('#album-image-gallery-item-preview').addClass('album-admin-hide');
        $('#album-image-gallery-item-upload').removeClass('album-admin-hide');
    });
});
