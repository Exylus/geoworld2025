<?php require_once 'header.php'; ?>
<?php
require_once 'inc/manager-db.php';

// Get current page from URL, default to 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max($page, 1); // Ensure page is at least 1

// Get rows per page from URL, default to 10
$perPage = isset($_GET['perPage']) ? (int) $_GET['perPage'] : 10;
$perPage = in_array($perPage, [10, 20]) ? $perPage : 10; // Only allow 10 or 20

if (isset($_GET['name']) and !empty($_GET['name'])) {
  $nom = $_GET['name'];
  $desPays = getPaginatedCountriesByContinent($nom, $page, $perPage);
  $totalCountries = countCountriesByContinent($nom);
} else {
  $nom = "Monde";
  $desPays = getAllPaginatedCountries($page, $perPage);
  $totalCountries = countAllCountries();
}

// Calculate total pages
$totalPages = ceil($totalCountries / $perPage);
?>
<link rel="stylesheet" href="css/index.css">
<main role="main" class="flex-shrink-0">

  <div class="container">
    <h1>Les pays en <?= $nom ?> </h1>

    <!-- Rows per page selector -->
    <div class="row mb-3">
      <div class="col-md-6">
        <form method="get" class="form-inline">
          <?php if (isset($_GET['name'])): ?>
            <input type="hidden" name="name" value="<?= htmlspecialchars($_GET['name']) ?>">
          <?php endif; ?>
          <label for="perPage" class="mr-2">Lignes par page:</label>
          <select name="perPage" id="perPage" class="form-control mr-2" onchange="this.form.submit()">
            <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $perPage == 20 ? 'selected' : '' ?>>20</option>
          </select>
        </form>
      </div>
    </div>

    <div>
      <table class="table">
        <tr>
          <th>Drapeau</th>
          <th>Nom</th>
          <th>Population</th>
        </tr>
        <?php foreach ($desPays as $pays): ?>
          <tr>
            <td> <img src="images/flag/<?= $pays->Code2 ?>.png" alt="Country Flag" class="flag list-flag"></td>
            <td> <a href="pays.php?pays=<?= urlencode($pays->Name) ?>"><?= htmlspecialchars($pays->Name) ?></a></td>
            <td> <?= number_format($pays->Population) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>

      <!-- Pagination -->
      <nav aria-label="Page navigation">
        <ul class="pagination">
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" aria-label="First">
                <span aria-hidden="true">&laquo;&laquo;</span>
              </a>
            </li>
            <li class="page-item">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>"
                aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
          <?php endif; ?>

          <?php
          // Show page numbers
          $start = max(1, $page - 2);
          $end = min($totalPages, $page + 2);

          for ($i = $start; $i <= $end; $i++):
            ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $totalPages): ?>
            <li class="page-item">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
            <li class="page-item">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $totalPages])) ?>"
                aria-label="Last">
                <span aria-hidden="true">&raquo;&raquo;</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>

      <div class="text-muted">
        Affichage de <?= (($page - 1) * $perPage) + 1 ?> à <?= min($page * $perPage, $totalCountries) ?> sur
        <?= $totalCountries ?> pays
      </div>
    </div>
  </div>
</main>

<?php
require_once 'javascripts.php';
require_once 'footer.php';
?>