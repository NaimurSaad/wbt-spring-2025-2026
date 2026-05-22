<?php $user = $_SESSION['user']; $isEdit = !empty($editing); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pharmacist Dashboard &mdash; Pharmacy Management</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="app-body">

<!-- Navbar -->
<header class="navbar">
    <div class="navbar-inner">
        <a class="brand" href="index.php?page=pharmacist">
            <span class="brand-icon">&#128138;</span>
            <span>PharmaSys</span>
        </a>
        <div class="nav-user">
            <span class="user-pill">
                <span class="user-avatar"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                <span class="user-meta">
                    <span class="user-name"><?= htmlspecialchars($user['name']) ?></span>
                    <span class="user-role">Pharmacist</span>
                </span>
            </span>
            <a href="index.php?page=logout" class="btn-logout">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Manage Medicines</h1>
            <p class="page-sub">Add, edit, search and remove medicines in the catalog</p>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <?php $messages = ['added' => 'Medicine added successfully.',
                           'updated' => 'Medicine updated successfully.',
                           'deleted' => 'Medicine deleted successfully.'];
              $msg = $messages[$_GET['msg']] ?? null; ?>
        <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- ============ Add / Edit Form ============ -->
    <div class="card form-card">
        <h3 class="card-title">
            <?= $isEdit ? '&#9998; Edit Medicine (#' . intval($editing['id']) . ')' : '+ Add New Medicine' ?>
        </h3>
        <form method="POST"
              action="index.php?page=pharmacist&action=<?= $isEdit ? 'update&id=' . intval($editing['id']) : 'add' ?>"
              class="form" novalidate>
            <div class="field-row">
                <div class="field">
                    <label for="medicine_name">Medicine Name</label>
                    <input type="text" id="medicine_name" name="medicine_name"
                           value="<?= htmlspecialchars($editing['medicine_name'] ?? '') ?>"
                           placeholder="e.g. Paracetamol 500mg" required>
                </div>
                <div class="field">
                    <label for="manufacturer">Manufacturer</label>
                    <input type="text" id="manufacturer" name="manufacturer"
                           value="<?= htmlspecialchars($editing['manufacturer'] ?? '') ?>"
                           placeholder="Manufacturer name" required>
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" min="0"
                           value="<?= htmlspecialchars($editing['quantity'] ?? '') ?>"
                           placeholder="0" required>
                </div>
                <div class="field">
                    <label for="price">Price ($)</label>
                    <input type="number" id="price" name="price" step="0.01" min="0"
                           value="<?= htmlspecialchars($editing['price'] ?? '') ?>"
                           placeholder="0.00" required>
                </div>
            </div>
            <div class="form-actions">
                <?php if ($isEdit): ?>
                    <a href="index.php?page=pharmacist" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Medicine</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary">Save Medicine</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- ============ Medicines Table ============ -->
    <div class="card">
        <div class="card-toolbar">
            <div class="search-wrap">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="searchInput" class="search-input"
                       placeholder="Search by medicine name or manufacturer...">
            </div>
            <span class="badge" id="resultCount"><?= count($medicines) ?> total</span>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Manufacturer</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($medicines)): ?>
                        <tr><td colspan="6" class="empty">No medicines yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($medicines as $i => $medicine): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($medicine['medicine_name']) ?></td>
                                <td><?= htmlspecialchars($medicine['manufacturer']) ?></td>
                                <td><?= htmlspecialchars($medicine['quantity']) ?></td>
                                <td>$<?= number_format($medicine['price'], 2) ?></td>
                                <td class="text-right">
                                    <a class="btn-sm btn-edit"
                                       href="index.php?page=pharmacist&action=edit&id=<?= $medicine['id'] ?>">Edit</a>
                                    <a class="btn-sm btn-delete"
                                       href="index.php?page=pharmacist&action=delete&id=<?= $medicine['id'] ?>"
                                       onclick="return confirm('Delete this medicine?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<footer class="footer">&copy; <?= date('Y') ?> Pharmacy Management System</footer>

<!-- =========== Inline AJAX search =========== -->
<script>
(function () {
    var input = document.getElementById('searchInput');
    var body  = document.getElementById('tableBody');
    var counter  = document.getElementById('resultCount');
    var timer;

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function render(rows) {
        if (!rows.length) {
            body.innerHTML = '<tr><td colspan="6" class="empty">No matching results.</td></tr>';
            counter.textContent = '0 results';
            return;
        }
        var html = '';
        rows.forEach(function (b, i) {
            html +=
                '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + esc(b.medicine_name) + '</td>' +
                    '<td>' + esc(b.manufacturer) + '</td>' +
                    '<td>' + esc(b.quantity) + '</td>' +
                    '<td>$' + parseFloat(b.price).toFixed(2) + '</td>' +
                    '<td class="text-right">' +
                        '<a class="btn-sm btn-edit" href="index.php?page=pharmacist&action=edit&id=' + b.id + '">Edit</a>' +
                        '<a class="btn-sm btn-delete" href="index.php?page=pharmacist&action=delete&id=' + b.id +
                        '" onclick="return confirm(\'Delete this medicine?\')">Delete</a>' +
                    '</td>' +
                '</tr>';
        });
        body.innerHTML = html;
        counter.textContent = rows.length + (input.value.trim() ? ' results' : ' total');
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            fetch('index.php?page=ajax&type=medicine&q=' + encodeURIComponent(input.value.trim()),
                  { credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(render)
                .catch(function (e) { console.error(e); });
        }, 200);
    });
})();
</script>

</body>
</html>
