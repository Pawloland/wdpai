<label for="<?= $id ?? ''; ?>"><?= $label ?? ''; ?></label>
<select name="<?= $name ?? ''; ?>" id="<?= $id ?? ''; ?>" required>
    <?php if (isset($array)) foreach ($array as $key => $value): ?>
        <option value="<?= $key; ?>"><?= $value; ?></option>
    <?php endforeach; ?>
</select>