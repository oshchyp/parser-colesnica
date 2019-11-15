<form method="post">
 <div><button class="btn btn-success">Сохранить</button></div>
      <?php if ($fields) : ?>
        <select id="select-field">
            <?php foreach ($fields as $k => $v) : ?>

                <option data-key="<?= $k ?>" data-name="<?= $v ?>"><?= $v ?></option>

            <?php endforeach; ?>
        </select>
    <?php endif; ?>

    <button type="button" class="btn btn-primary" id="add-field-doc">Добавить</button>

    <table class="table">

        <thead>
            <tr>
                <th>
                    Столбец
                </th>

<!--                <th>
                    Ключ-->
<!--                </th>-->

                <th>
                    Название
                </th>
                <th>

                </th>
            </tr>
        </thead>

        <tbody id="new-field-doc">

            <?php if ($structure) : ?>
                <?php foreach ($structure as $k => $v) : ?>

                    <tr>

                        <td>
                            <input value="<?= $k ?>" class="form-control" type="text" name="key[]">
                            <input value="<?= $v ?>" class="form-control" type="hidden" name="value[]">
                        </td>
                         <td><?= isset($fields[$v]) ? $fields[$v] : '' ?></td>
                        <td><button class="btn btn-danger" data-deleteparent="tr">Удалить</button></td>
                    </tr>

                <?php endforeach; ?>
            <?php endif; ?>

        </tbody>


    </table>
</form>

