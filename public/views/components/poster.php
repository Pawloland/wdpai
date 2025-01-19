<a
    <?php if (isset($link) && $link === true): ?>
        href="/select_place?ID_Movie=<?php echo $ID_Movie ?? -1; ?>"
    <?php endif; ?>
>
    <div class="poster" style="background-image: url('<?php echo '/public/img/posters/' . ($posterID ?? 'default'); ?>');">
        <div class="overlay"><?php echo $title ?? 'Missing title'; ?></div>
    </div>
</a>
