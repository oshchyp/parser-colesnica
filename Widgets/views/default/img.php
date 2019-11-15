<?php if ($imgs) : ?>
    <div class="float-left product-img">

        <?php foreach ($imgs as $v) : ?>


            <img width="100px;" src="/files/images/products/<?= $v ?>?r=<?=time()?>">


        <?php endforeach; ?>
    </div>

<style>
    
    .product-img img {
        cursor: pointer;
    }
    
</style>

    <script>
        $(document).ready(function () {
            $('.product-img img').click(function () {
                if ($(this).attr('width') == '100px;'){
                    $('.product-img img').attr('width', '100px;');
                    $(this).attr('width', '');
                }
                else
                    $(this).attr('width', '100px;');
            });
        });


    </script>
<?php else: ?>

    <p style="font-size: 25px">Загруженых изображений нет!</p>
<?php endif; ?>
