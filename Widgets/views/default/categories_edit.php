<form method="post">
    <div style="margin-bottom: 10px;">
    <button class="btn btn-success">Сохранить</button>
    </div>
    <button type="button" class="btn btn-primary" id="add-field">Добавить</button> 
<table class="table">

        <thead>
            <tr>
                <th>
                    Название
                </th>

                <th>
                    Ключ
                </th>

                <th>

                </th>
            </tr>
        </thead>

        <tbody id="new-field">

            <?php if ($cat) : ?>
                <?php foreach ($cat as $k => $v) : ?>
                    <tr>
                        <td><input class="form-control" type="text" name="value[]" value="<?= $v ?>"></td>
                        <td><input class="form-control" type="text" name="key[]" value="<?= $k ?>"></td>
                        <td><button class="btn btn-danger" data-deleteparent="tr">Удалить</button></td>
                    </tr>

                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>


    </table>
</form>
