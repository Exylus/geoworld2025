<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Stats</title>
    <link rel="stylesheet" href="css/pays.css">
    <link href="assets/bootstrap-4.4.1-dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php
    require_once('header.php');
    require_once('inc/manager-db.php');
    if (isset($_GET['pays']) and !empty($_GET['pays'])) {
        $country = $_GET['pays'];
        $countryInformations = getCountryInformations($country);
    } else {
        header('location: 404.php');
    }
    ?>
    <div class="wrapper">
        <div class="container">
            <h1><?= $countryInformations[0]->Name ?></h1>
            <div class="img-container">
                <img src="images/flag/<?= $countryInformations[0]->Code2 ?>.png" alt="Country Flag" class="flag">
            </div>
            <div class="stats-grid">
                <div class="card"><strong>Capital:</strong> <?= $countryInformations[0]->Capitale ?></div>
                <div class="card"><strong>Population:</strong><?= $countryInformations[0]->Population ?></div>
                <div class="card"><strong>Area:</strong><?= $countryInformations[0]->SurfaceArea ?> km²</div>
                <div class="card"><strong>GDP:</strong><?= $countryInformations[0]->GNP ?></div>
                <div class="card"><strong>Life expectancy:</strong><?= $countryInformations[0]->LifeExpectancy ?></div>
                <div class="card"><strong>Head of state:</strong><?= $countryInformations[0]->HeadOfState ?></div>
            </div>
        </div>
        <div class="update-panel">
            <h2>Update Country Data</h2>
            <label for="countryName">Country name:</label>
            <input type="text" placeholder="Country Name">
            <input type="text" placeholder="Capital">
            <input type="number" placeholder="Population">
            <input type="text" placeholder="Area">
            <input type="text" placeholder="GDP">
            <input type="text" placeholder="Life expectancy">
            <input type="text" placeholder="Head of state">
            <div class="button-container">
                <button class="fetch-btn">Fetch Data</button>
                <button class="update-btn">Update</button>
            </div>
        </div>
    </div>
    <?php require_once('footer.php') ?>
</body>