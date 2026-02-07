 
    Dropzone.autoDiscover = false; // Disable auto-discovery

    var myDropzone = new Dropzone("#my-dropzone", {
        url: "/upload1",
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
 
