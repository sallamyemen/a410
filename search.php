
<?php
 function searchHeading($key) {
     switch($key) {
         case 'product'     : return 'Найдено в продукции';
         case 'catalogue'   : return 'Найдено в каталоге';
         case 'content'     : return 'Найдено в статьях';
         case 'accii'       : return 'Найдено в акциях';

         default : return false;
     }
 }
?>
<?php $count = 0; ?>
    <?php foreach($model as $key => $section) : ?>        
        <?php if($model[$key]->hits->total > 0) : ?>
        <div class="search-result-section">
            <h5><?= searchHeading($key) ?></h5>
            <ul>
                <?php foreach($model[$key]->hits->hits as $item) : ?>
                    <li>
                        <a class="hv" href="<?= Yii::$app->urlManager->createUrl($item->_source->url) ?>">
                            <?= $item->_source->title ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
        <?php else: ?>
            <?php $count++; ?>
        <?php endif ?>    
    <?php endforeach ?>    
<?php if ($count == 3): ?>     
    <div class="search-result-section">                
            <p class="hv" style="padding: 10px">Результатов нет!</p>              
    </div>    
<?php endif ?>
