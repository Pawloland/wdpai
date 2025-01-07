<?php if (!isset($skip_label) || !$skip_label): ?>
    <label for="<?= $id ?? ''; ?>"><?= $label ?? ''; ?></label>
<?php endif; ?>
<select name="<?= $name ?? ''; ?>" id="<?= $id ?? ''; ?>" required>
    <?php if (isset($array)) foreach ($array as $key => $value): ?>
        <option value="<?= $key; ?>" <?= "$key" === ($default_option ?? '') ? 'selected' : ''; ?>><?= $value; ?></option>
    <?php endforeach; ?>
</select>