<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropzone File Upload</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
</head>
<body>
    <form action="{{URL('/upload1')}}" class="dropzone" id="my-dropzone"></form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="{{asset('assets/js/dropzone-config.js')}}"></script>


<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>


 

<script>
    

    Dropzone.autoDiscover = false; // Disable auto-discovery

    var myDropzone = new Dropzone("#my-dropzone", {
        url: "{{URL('/upload1')}}",
        acceptedFiles: 'image/*', // Accept only images
        maxFilesize: 2, // Maximum file size in MB
        maxFiles: 5, // Maximum number of files
        addRemoveLinks: true, // Allow files to be removed

        init: function() {
            this.on("addedfile", function(file) {
                console.log("File added:", file);

                // Custom validation for file type (if needed, as acceptedFiles already does this)
                if (!file.type.match(/image.*/)) {
                    this.removeFile(file); // Remove the file if it's not an image
                    alert("Only images are allowed!");
                    return;
                }

                // Custom validation for file size (if needed, as maxFilesize already does this)
                if (file.size > this.options.maxFilesize * 1024 * 1024) {
                    this.removeFile(file); // Remove the file if it exceeds the size limit
                    alert("File size exceeds the 2MB limit!");
                    return;
                }
            });

            this.on("error", function(file, errorMessage) {
                console.log("Error:", errorMessage);
                alert(errorMessage);
            });

            this.on("success", function(file, response) {
                console.log("Success:", response);
            });

            this.on("maxfilesexceeded", function(file) {
                this.removeFile(file);
                alert("You have reached the maximum number of files allowed.");
            });
        }
    });
 



</script>


</body>



</html>
