<h1><?= htmlspecialchars($title) ?></h1>
<table class="table table-sm table-hover table-bordered" width="98%">
    <thead>
        <tr class="light-blue-bg-1 center-text">
            <th>Date</th>
            <th>Name</th>
            <th>Main Phone</th>
            <th>Second Phone</th>
            <th>Gender</th>
            <th>E-Mail</th>
            <th>City</th>
            <th>State</th>
            <th>Office</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $lead): ?>
                <tr>
                    <td>
                        <?= date("m/d/Y", htmlspecialchars(strtotime($lead->real_date))) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($lead->full_name ?: $lead->fname . ' ' . $lead->lname) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($lead->mainPhone) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($lead->secondPhoneArea != "" && $lead->secondPhone != "" ? $lead->secondPhone : "") ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($lead->sex) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($lead->email) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($lead->city) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($lead->state) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($lead->name) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($lead->current_status) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10">No results found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>