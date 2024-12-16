<?php

function checkSlug($val)
{
        $slug = explode('/', Yii::$app->request->get('slug'));
        $cnt = 0 ;
        foreach($slug as $item)
        {
            $cnt++;

            for($i = 0; $i < $cnt; $i++) $arr[] = $slug[$i];

            $chain[] = implode('/', $arr);

            unset($arr);
        }

    foreach($chain as $item)
        if($item == $val)
            return true;
}
?>

<?php function openTree($children, $depth) { ?>
    <ul class="<?= $depth == 1 ? 'lv-root' : '' ?> <?= $depth <= 1 ? 'side-menu' : 'side-sub-menu' ?> <?= $depth == 2 ? 'li-active' : '' ?> <?= $depth == 3 ? 'depth' : '' ?> <?= $depth > 3 ? 'deeper' : '' ?>">
    <?php foreach($children as $item) : ?>
                <li>
                <?php $ankor = $item['ankor'] ? $item['ankor'] : $item['title']; ?>

                <?php if($item['routs']['route'] == Yii::$app->request->get('slug')) : ?>
                    <a class="cat-active" href="<?= urldecode(Yii::$app->getUrlManager()->createUrl(['route/index', 'slug' => $item['routs']['route']])) ?>"><?= $ankor ?></a>
                <?php else : ?>
                    <a href="<?= urldecode(Yii::$app->getUrlManager()->createUrl(['route/index', 'slug' => $item['routs']['route']])) ?>"><?= $ankor ?></a>
                <?php endif ?>
                    <?php if(!empty($item['children']) && checkSlug($item['routs']['route'])) : ?>
                        <?= openTree($item['children'], $item['depth']); ?>
                    <?php endif ?>
                </li>

    <?php endforeach ?>
    </ul>
<?php } ?>

<?= openTree($tree, 1) ?>
