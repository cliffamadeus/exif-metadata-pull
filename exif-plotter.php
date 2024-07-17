<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXIF Weather</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
    <style>
        .home-flex-container {
            display: flex;
            justify-content: start;
            gap: 2rem; 
        }
        .modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="./exif-weather.php">Weather</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./exif-plotter.php">Plotter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#welcomeModal">Privacy Policy</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<body>
    <div class="container" style="margin-top: 2rem;">
        <div class="row mb-4">
            <div class="col-md-4">
                <img id="imagePreview" class="img-fluid" src="https://www.freeiconspng.com/uploads/no-image-icon-6.png" width="200px">
            </div>
            <div class="col-md-8">
                <h5 class="card-title">Upload Image</h5>
                <br>
                <input type="file" id="imageInput" class="form-control" onchange="handleImageInput(this);" />
                <div class="mt-3">
                    
                    <form>
                    <div class="form-group mb-3">
                        <label for="latitudeInput">Latitude</label>
                        <input type="text" class="form-control" id="latitudeInput" readonly disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="longitudeInput">Longitude</label>
                        <input type="text" class="form-control" id="longitudeInput" readonly disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="dateTime">Datetime</label>
                        <input type="text" class="form-control" id="dateTime" readonly disabled>
                    </div>
                        <div id="weatherInfo" class="mt-3"></div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Save Data
                        </button>
                        <button id="clearButton" class="btn btn-secondary">Clear</button>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           Upload Details?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="welcomeModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6><b>Information We Collect</b></h6>
                    <p>We collect EXIF metadata from uploaded images, which may include camera settings, date, time, and GPS data. We may also collect personal information you provide, such as your name and email address.</p>
                    <h6><b>How We Use Your Information</b></h6>
                    <p>We use your information to process EXIF data, improve our services, and communicate with you.</p>
                    <h6><b>Data Sharing</b></h6>
                    <p>We do not sell your information. We may share data with service providers and comply with legal obligations.</p>
                    <h6><b>Your Rights</b></h6>
                    <p>You have rights regarding your personal information, including access, correction, and deletion. Contact us to exercise these rights.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Agree</button>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var myModalElement = document.getElementById('welcomeModal');
    var myModal = new bootstrap.Modal(myModalElement, { keyboard: false });

    document.getElementById('modalTriggerBtn').addEventListener('click', function() {
        myModal.show();
    });

    document.querySelector('#welcomeModal .btn-primary').addEventListener('click', function() {
        console.log('User agreed.');
        myModal.hide();
    });

    myModalElement.addEventListener('hidden.bs.modal', function () {
        var backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    });
});

</script>

<script src="plotter.js"></script>

</body>
</html>
