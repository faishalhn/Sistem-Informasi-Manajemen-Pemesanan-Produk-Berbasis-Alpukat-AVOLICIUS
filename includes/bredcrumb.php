<?php

$breadcrumb = $breadcrumb ?? [];

?>

<?php if (!empty($breadcrumb)) : ?>

    <nav aria-label="breadcrumb" class="mb-3">

        <ol class="breadcrumb">

            <?php foreach ($breadcrumb as $item) : ?>

                <?php if (isset($item['url'])) : ?>

                    <li class="breadcrumb-item">

                        <a href="<?= $item['url']; ?>">

                            <?= $item['title']; ?>

                        </a>

                    </li>

                <?php else : ?>

                    <li class="breadcrumb-item active">

                        <?= $item['title']; ?>

                    </li>

                <?php endif; ?>

            <?php endforeach; ?>

        </ol>

    </nav>

<?php endif; ?>