<?php

declare(strict_types=1);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title; ?> - Prembly CRM</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
  <main role="main" class="container-fluid">
    <!-- <?php include_once __DIR__ . '/partials/search_leads_view.php'; ?> -->

    <div class="table-responsive">
      <div class="table-center">
        <?php require __DIR__ . '/partials/pagination_view.php'; ?>
      </div>

      <?php require_once __DIR__ . '/partials/search_results_view.php'; ?>

      <div class="table-center">
        <?php require __DIR__ . '/partials/pagination_view.php'; ?>
      </div>
    </div>

  </main>
</body>

</html>