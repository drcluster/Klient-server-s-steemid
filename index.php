<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="description" content="Webpage description goes here" />
  <meta charset="utf-8">
  <title>Eesti bussiga</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="http://code.jquery.com/jquery-latest.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <style>
    .autocomplete-dropdown {
        position: absolute;
        background-color: white;
        border: 1px solid #ddd;
        border-top: none;
        z-index: 1000;
        display: none;
        width: calc(100% - 2px); /* Adjust width as needed */
    }

    .dropdown-item {
        padding: 5px 10px;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: #f0f0f0;
    }
  </style>
</head>

<body>
  
    <div class="container mt-4">
        <form>
            <div class="row mb-3">
              <h1>Eesti bussiga</h1>
            </div>
            <!--div class="row mb-3">
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" id="autofind">Leia minu geograafilise asukoha kasutamise peatamine</button>
                </div>
            </div-->
            <div class="row mb-3">
                <label for="city" class="form-label">Valige linn või piirkond:</label>
                <div class="col">
                    <input type="text" class="form-control" data-action="find_city" id="city" placeholder="Alustage tippimist...">
                    <div class="autocomplete-dropdown" id="autocomplete-dropdown-1"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="busstop" class="form-label">Valige bussipeatus:</label>
                <div class="col">
                    <input type="text" class="form-control" data-action="find_stop" id="busstop" placeholder="Alustage tippimist...">
                    <div class="autocomplete-dropdown" id="autocomplete-dropdown-1"></div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" id="find_bus">Otsi</button>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <div class="d-grid gap-2 d-md-block" id="bus_list">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p class="js-times">Lähim saabumisaeg: </p>
                </div>
            </div>
        </form>
    </div>
    <script src="js/common.js"></script>
</body>
</html>