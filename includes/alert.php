<?php

/**
 * ==========================================================
 * FILE        : alert.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Flash Message
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

$alert = get_alert();

if ($alert) :

?>

    <div class="alert alert-<?= $alert['type']; ?> alert-dismissible fade show">

        <?= $alert['message']; ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

<?php endif; ?>