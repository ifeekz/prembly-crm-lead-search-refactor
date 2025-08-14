<?php
declare(strict_types=1);
function build_query(array $params, array $overrides): string {
    return http_build_query(array_merge($params, $overrides));
}
?>
<nav aria-label="Page navigation" class="mt-3">
  <ul class="pagination">
    <li class="page-item <?= $pagination['hasPrev'] ? '' : 'disabled' ?>">
      <a class="page-link" href="?<?= build_query($params, ['page' => $pagination['prev']]) ?>">Previous</a>
    </li>
    <?php for ($p = 1; $p <= max(1,$pagination['pages']); $p++): ?>
      <li class="page-item <?= $p === $pagination['page'] ? 'active' : '' ?>">
        <a class="page-link" href="?<?= build_query($params, ['page' => $p]) ?>"><?= $p ?></a>
      </li>
    <?php endfor; ?>
    <li class="page-item <?= $pagination['hasNext'] ? '' : 'disabled' ?>">
      <a class="page-link" href="?<?= build_query($params, ['page' => $pagination['next']]) ?>">Next</a>
    </li>
  </ul>
</nav>
