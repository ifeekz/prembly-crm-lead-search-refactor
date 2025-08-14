<?php
declare(strict_types=1);

function esc(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function highlight(?string $text, string $q): string {
    if ($text === null || $q === '') return esc((string)$text);
    $escaped = esc($text);
    $pattern = '/' . preg_quote($q, '/') . '/i';
    return preg_replace($pattern, '<mark>$0</mark>', $escaped);
}
?>
<table class="table table-striped table-hover mb-0">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Status</th>
      <th>Created</th>
    </tr>
  </thead>
  <tbody>
  <?php if (empty($rows)): ?>
    <tr><td colspan="6" class="text-center py-4">No results</td></tr>
  <?php else: ?>
    <?php foreach ($rows as $i => $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><?= highlight($r['name'] ?? '', $q) ?></td>
        <td><?= highlight($r['email'] ?? '', $q) ?></td>
        <td><?= highlight($r['phone'] ?? '', $q) ?></td>
        <td><?= esc($r['status'] ?? '') ?></td>
        <td><?= esc($r['created_at'] ?? '') ?></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
  </tbody>
</table>
