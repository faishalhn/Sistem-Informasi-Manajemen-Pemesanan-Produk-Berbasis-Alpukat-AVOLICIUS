<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : footer.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Master Footer Layout
 * VERSION     : 2.3.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

?>

<!-- ==========================================================
     DASHBOARD DATA
========================================================== -->

<?php
if (
    isset(
        $monthly_revenue_json,
        $category_labels_json,
        $category_totals_json
    )
) :
?>

    <script>
        window.dashboardData = {

            revenue: <?= $monthly_revenue_json; ?>,

            categoryLabels: <?= $category_labels_json; ?>,

            categoryTotals: <?= $category_totals_json; ?>

        };
    </script>

<?php endif; ?>
<!-- ==========================================================
     FOOTER
========================================================== -->

<footer class="dashboard-footer">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center">

            <span>

                © <?= date('Y'); ?>

                <?= APP_NAME; ?>

            </span>

            <span>

                Version <?= APP_VERSION; ?>

            </span>

        </div>

    </div>

</footer>
<!-- ==========================================================
     JAVASCRIPT
========================================================== -->

<!-- JQuery -->

<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>

<!-- Bootstrap -->

<script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- Dashboard Only -->

<?php if (!empty($dashboard_page)) : ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php endif; ?>

<!-- Global -->

<script src="<?= base_url('assets/js/app.js'); ?>"></script>
<script>
    window.APP = {

        baseUrl: "<?= BASE_URL; ?>"

    };
</script>

<!-- Admin -->

<script src="<?= base_url('assets/js/admin.js'); ?>"></script>

</body>

</html>