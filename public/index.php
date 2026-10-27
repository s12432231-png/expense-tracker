
<?php


require_once __DIR__ . '/../includes/db.php';


$sql = "SELECT expenses.id,
               expenses.description,
               expenses.amount,
               expenses.expense_date,
               categories.name AS category_name
        FROM expenses
        LEFT JOIN categories
            ON expenses.category_id = categories.id
        ORDER BY expenses.expense_date DESC,
                 expenses.id DESC";

$stmt = $pdo->query($sql);
$expenses = $stmt->fetchAll();

$totalStmt = $pdo->query(
    "SELECT COALESCE(SUM(amount), 0) FROM expenses"
);
$total = $totalStmt->fetchColumn();

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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Expense Tracker</h1>

        <a href="add_expense.php" class="btn btn-primary">
            Add Expense
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">

            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($expenses)): ?>

                    <tr>
                        <td colspan="4" class="text-center">
                            No expenses found.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td>
                                <?= e($expense['expense_date']) ?>
                            </td>

                            <td>
                                <?= e($expense['description']) ?>
                            </td>

                            <td>
                                <?= e($expense['category_name'] ?? 'Other') ?>
                            </td>

                            <td>
                                <?= number_format(
                                    (float) $expense['amount'],
                                    2
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th>
                        <?= number_format((float) $total, 2) ?>
                    </th>
                </tr>
            </tfoot>

        </table>
    </div>

</div>

<?php

require_once __DIR__ . '/../includes/footer.php';
?>
