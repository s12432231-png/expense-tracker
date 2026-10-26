<?php
require_once __DIR__ . '/../includes/db.php';

$sql = "SELECT
            categories.name,
            COALESCE(SUM(expenses.amount), 0) AS total
        FROM categories
        LEFT JOIN expenses
            ON categories.id = expenses.category_id
        GROUP BY categories.id, categories.name
        ORDER BY categories.id";

$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalExpenses = 0;

foreach ($categories as $category) {
    $totalExpenses += (float) $category['total'];
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <h1>Expense Summary</h1>

    <h4 class="mb-4">
        Total Expenses:
        $<?php echo number_format($totalExpenses, 2); ?>
    </h4>

    <?php foreach ($categories as $category): ?>

        <?php
        $amount = (float) $category['total'];

        $percentage = $totalExpenses > 0
            ? ($amount / $totalExpenses) * 100
            : 0;
        ?>

        <div class="mb-4">

            <div class="d-flex justify-content-between">
                <strong>
                    <?php
                    echo htmlspecialchars($category['name']);
                    ?>
                </strong>

                <span>
                    $<?php echo number_format($amount, 2); ?>
                    (<?php echo number_format($percentage, 1); ?>%)
                </span>
            </div>

            <div class="progress">
                <div
                    class="progress-bar"
                    role="progressbar"
                    style="width: <?php echo $percentage; ?>%;"
                    aria-valuenow="<?php echo $percentage; ?>"
                    aria-valuemin="0"
                    aria-valuemax="100">
                </div>
            </div>

        </div>

    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>