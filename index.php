<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXIF Metadata</title>
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

        .img{
            width: 100px;
            height: 100px;
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
   
    <div class="container mt-4">
        <!-- Button to trigger modal 
        <button type="button" class="btn btn-secondary mt-3 text-white" id="modalTriggerBtn" data-bs-toggle="modal" data-bs-target="#welcomeModal">
            Show Website Usage Policy
        </button>
        -->

        <div class="home-flex-container mt-4">
                
            <div class="card" style="width: 18rem;">
                <a href="./exif-basic.php" style="text-decoration: none; color:black">
                    <img src="https://www.svgrepo.com/show/502423/weather.svg" class="img" alt="...">
                    <div class="card-body">
                    <h5 class="card-title">EXIF Weather Integration</h5>
                    <p class="card-text">Basic implementation of EXIF and Openweather</p>
                    <a href="./exif-basic.php" style="margin-top: 10px;"class="btn btn-primary">Go</a>
                    </div>
                </a>
            </div>


            <div class="card" style="width: 18rem;">
                <a href="./exif-weather.php" style="text-decoration: none; color:black">
                <img src="https://www.svgrepo.com/show/502421/news.svg" class="img" alt="...">
                <div class="card-body">
                    <h5 class="card-title">EXIF Weather Integration - Database</h5>
                    <p class="card-text">This applet lets you save your input on a database</p>
                    <a href="exif-plotter.php" class="btn btn-primary">Go</a>
                </div>
                </a>
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

            // Check if the user is visiting for the first time
            if (!localStorage.getItem('visited')) {
                myModal.show();
                localStorage.setItem('visited', 'true');
            }

            // Additional function to show modal when button is clicked
            document.getElementById('modalTriggerBtn').addEventListener('click', function() {
                myModal.show();
            });

            // Handle Agree button click
            document.querySelector('#welcomeModal .btn-primary').addEventListener('click', function() {
                console.log('User agreed.');
                myModal.hide();
            });

            // Handle modal hide event to remove backdrop
            myModalElement.addEventListener('hidden.bs.modal', function () {
                var backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            });
        });
    </script>

    <script src="script.js"></script>
</body>
</html>
