<?php if ($cat) :?>
<ul>
<?php foreach ($cat as $k=>$v) :?>

    <li><a style="font-size: 30px;" href="/<?=$url?>/<?=$k?>"><?=$v?></a></li>

<?php endforeach; ?>
</ul>
<?php endif;?>