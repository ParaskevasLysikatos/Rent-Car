var photoshootsImages = 0;
$(document).ready(function () {
    Webcam.set('constraints',
        {facingMode: 'environment'}, {
            width: 1024,
            height: 567,
            dest_width: 1024, // device capture size
            dest_height: 567,
            crop_width:1024,
            crop_height:567,
            image_format: 'jpeg',
            jpeg_quality: 80
        });



    Webcam.attach('#my_camera');

    $(document).on('click', "#take_snapshot", function () {
        // take snapshot and get image data
        Webcam.snap(function (data_uri) {
            var element = document.createElement("div");
            element.className = "preview_image";

            var removeBTN = document.createElement("div");
            removeBTN.innerText = "x";
            removeBTN.className = "remove_preview_image";

            var img = new Image();
            img.src = data_uri;
            img.className = "single_image_preview";

            element.appendChild(removeBTN);
            element.appendChild(img);
            document.getElementById('results').appendChild(element);
        });
        countImages();
    });


    $(document).on('click', "#saveImages", function () {

        //Url of image is Base64 and we upload it on server with below function
        var customIndex = 0;
        $( ".single_image_preview" ).each(function( index ) {
            Webcam.upload($(this).attr('src'), uploadImage+"?booking="+booking, function (code, text) {
                customIndex++;
                var percent = ((customIndex)/photoshootsImages).toFixed(2)*100;

                $('#photoshootProgressBar').css('width',percent+"%");
                $('#uploadedImages').text(customIndex);
            });
        });

    });

    $(document).on('click', ".remove_preview_image", function () {
        $(this).parent().remove();
        countImages();
    });

    $( window ).resize(function() {
        resizeCamera();
    });

    resizeCamera();
});

function countImages() {
    $('#countedImages').text($('.single_image_preview').length);
    photoshootsImages = $('.single_image_preview').length;
}

function resizeCamera() {
    $( "#my_camera" ).css( "width", $('#my_camera_col').width() );
    $( "#my_camera video" ).css( "width", $('#my_camera_col').width() );
}
