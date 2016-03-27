"use strict";

/*
 * Attaches the image uploader to the input field
 */
jQuery(document).ready(function($){
 
    // Instantiates the variable that holds the media library frame.
    var meta_image_frame;
 
    // Runs when the image button is clicked.
    $('#meta-image-button').click(function(e){
 
        // Prevents the default action from occuring.
        e.preventDefault();
 
        // open modal - bind this to your button
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor )
            wp.media.editor.open('meta-image-button');

        // backup of original send function
        var original_send = wp.media.editor.send.attachment;

        // new send function
        wp.media.editor.send.attachment = function( a, b) {
              if(b.hasOwnProperty('id'))
                $('input#meta-image').val(b.id);
              else
                return;
              
              //if has thumbnail size
              if(b.hasOwnProperty('sizes')){
                var sizes = b.sizes;
                if(sizes.hasOwnProperty('thumbnail')){
                  //use thumbnail
                  var thumbnail = sizes.thumbnail.url;
                  $('#meta-image-preview').attr('src', thumbnail);
                }else{
                  //use fullsize
                  var thumbnail = sizes.full.url;
                  $('#meta-image-preview').attr('src', thumbnail);
                }
              }
               

        };

        // wp.media.send.to.editor will automatically trigger window.send_to_editor for backwards compatibility

        // backup original window.send_to_editor
       window.original_send_to_editor = window.send_to_editor; 

        // override window.send_to_editor
       window.send_to_editor = function(html) {
           // html argument might not be useful in this case
           // use the data from var b (attachment) here to make your own ajax call or use data from b and send it back to your defined input fields etc.
       }

    });

    //runs when the remove image button is clicked.
    $('#meta-image-remove').click(function(e){

        // Prevents the default action from occuring.
        e.preventDefault();

        clearCategoryImage();
       
    });

    function clearCategoryImage(){
        //reset the val
        $('input#meta-image').val('');

        //change the preview src
        $('#meta-image-preview').attr('src', meta_image.no_image);
    }

    //color picker
    $('.meta-color').wpColorPicker();

    //bind to submit click
    $('form#addtag input#submit').click(function( event ) {
        event.preventDefault();
        clearCategoryImage();
    });


});