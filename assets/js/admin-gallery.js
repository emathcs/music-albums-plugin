jQuery(document).all ? jQuery(window) : jQuery(document).ready(function($) {
    var galleryFrame;
    var type;
    var number;

    // Setting an image
    $('.robin-upload-btn').on('click', function(e) {
        console.log(1);
        // Updating the preview and the buttons
        // type   = $(this).data('type');
        // number = $(this).data('album');
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
            $('#cover').val(imageIds);
            // Updating the preview and the buttons
            $('#upload-btn').addClass('robin-hide');
            $('#remove-btn').removeClass('robin-hide');
            $('#robin-image-gallery-item-preview').html(previewHtml);
            $('#robin-image-gallery-item-upload').addClass('robin-hide');
            $('#robin-image-gallery-item-preview').removeClass('robin-hide');
        });

        // Finally, open the modal
        galleryFrame.open();
    });

    // Removing the image
    $('.robin-remove-btn').on('click', function(e) {
        // Getting the parameters
        type   = $(this).data('type');
        number = $(this).data('album');
        // Removing the image from the hidden imput
        $('#' + type + '_img_' +  number).val('');
        // Updating the preview and the buttons
        $('#robin-image-gallery-item-preview-' + type + '_img_' + number).html('');
        $('#' + type + '_img_' + number + '-remove-btn').addClass('robin-hide');
        $('#' + type + '_img_' + number + '-upload-btn').removeClass('robin-hide');
        $('#robin-image-gallery-item-preview-' + type + '_img_' + number).addClass('robin-hide');
        $('#robin-image-gallery-item-upload-' + type + '_img_' + number).removeClass('robin-hide');
    });
});
