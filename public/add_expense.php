
<?php

require_once __DIR__ . '/../includes/db.php';

$error = '';
$description = '';
$amount = '';
$categoryId = '';
$expenseDate = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $description = trim($_POST['description'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $categoryId = $_POST['category_id'] ?? '';
    $expenseDate = $_POST['expense_date'] ?? '';

    if ($description === '') {

        $error = 'Description is required.';

    } elseif (
        !is_numeric($amount) ||
        !is_finite((float) $amount) ||
        (float) $amount <= 0
    ) {

        $error = 'Amount must be greater than zero.';

    } elseif (!ctype_digit((string) $categoryId)) {

        $error = 'Please select a valid category.';

    } else {

        $date = DateTime::createFromFormat(
            '!Y-m-d',
            $expenseDate
        );

        $dateErrors = DateTime::getLastErrors();

        if (
            !$date ||
            (
                $dateErrors !== false &&
                (
                    $dateErrors['warning_count'] > 0 ||
                    $dateErrors['error_count'] > 0
                )
            ) ||
            $date->format('Y-m-d') !== $expenseDate
        ) {

            $error = 'Please enter a valid date.';

        } else {

            $categoryStmt = $pdo->prepare(
                'SELECT id FROM categories WHERE id = ?'
            );

            $categoryStmt->execute([(int) $categoryId]);

            if (!$categoryStmt->fetch()) {

                $error = 'Please select a valid category.';

            } else {

                $sql = "INSERT INTO expenses
                        (
                            description,
                            amount,
                            category_id,
                            expense_date
                        )
                        VALUES (?, ?, ?, ?)";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    $description,
                    (float) $amount,
                    (int) $categoryId,
                    $expenseDate
                ]);

                header('Location: index.php');
                exit;
            }
        }
    }
}

$categories = $pdo->query(
    'SELECT id, name FROM categories ORDER BY name'
)->fetchAll();

function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">

    <h1 class="mb-4">Add Expense</h1>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="add_expense.php">

        <div class="mb-3">
            <label for="description" class="form-label">
                Description
            </label>

            <input
                type="text"
                id="description"
                name="description"
                class="form-control"
                value="<?= e($description) ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">
                Amount
            </label>

            <input
                type="number"
                id="amount"
                name="amount"
                class="form-control"
                min="0.01"
                step="0.01"
                value="<?= e($amount) ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">
                Category
            </label>

            <select
                id="category_id"
                name="category_id"
                class="form-select"
                required
            >
                <option value="">Select Category</option>

                <?php foreach ($categories as $category): ?>
                    <option
                        value="<?= e($category['id']) ?>"
                        <?= (string) $categoryId ===
                            (string) $category['id']
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($category['name']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <div class="mb-3">
            <label for="expense_date" class="form-label">
                Date
            </label>

            <input
                type="date"
                id="expense_date"
                name="expense_date"
                class="form-control"
                value="<?= e($expenseDate) ?>"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Save Expense
        </button>

        <a href="index.php" class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
