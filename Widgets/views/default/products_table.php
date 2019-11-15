<?php
//$configComp
?>
<?php if ($fields && $products) : ?>
  
    <?php foreach ($products as $k => $v) : ?>
    
    
    <h2><?= isset($cat[$k]) ? $cat[$k] : $k ?>: <?=count($v);?> товаров</h2>
    
    <?php if ($view_products) :?>
        <?php
        if ($f_cat = $configComp->set('key', 'doc_out_' . $k)->get()){
            $f = ['article'=>$fields['article']];
            foreach ($f_cat as $val) {
                $f[$val] = isset($fields[$val]) ? $fields[$val] : $val;
            } 
            
        } else
            $f = $fields;
        ?>
  <table class="table table-<?=$k?>">
            <thead>
                <tr>
        <?php foreach ($f as $val) : ?>
                        <th><?= $val ?></th>
        <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>

        <?php if ($v) : ?>
            <?php foreach ($v as $k1 => $v1) : ?>
                       <?php $v1 = $main_comp->convertProduct($v1) ?> 
                        <tr>
                        <?php foreach ($f as $key => $val) : ?>
                                <td><?= isset($v1[$key]) ? $v1[$key] : '' ?></td>
                <?php endforeach; ?>
                        </tr>

                        <?php endforeach; ?>

        <?php endif; ?>

            </tbody>

        </table>
    <?php endif;?>
    <script>
    $(function () {
        $(".table-<?= $k ?>").dataTable({
//            "aoColumnDefs": [{
//                    "aTargets": [4], 
//                    "bSortable": false
//
//                }]
        });
    });
</script>
    <?php endforeach; ?>

<?php else: ?>

<p style="font-size: 25px">Загруженых товаров ранее нет!</p>

<?php endif; ?>


