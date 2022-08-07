<?php
use application\assets\DemoJavascriptAsset;
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;
DemoJavascriptAsset::add();

$User = Config::getObject('core.user.class');
?>
<?php // include('includes/admin-categories-nav.php'); ?>

<h1>Список категорий</h1>
<p>Всего категорий: <?= count($categories)?></p>
<p><a href="<?= Url::link("admin/categories/add") ?>">Добавить новую категорию</a></p>
<?php if (!empty($categories)): ?>
<table class="table">
    <thead>
    <tr>
      <th>Название</th>
      <th>Описaние</th>
    </tr>
     </thead>
    <tbody>
    <?php foreach($categories as $category): ?>
    <tr>
        <td> <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('admin/categories/edit&id='
		. $category->id . ">{$category->name}</a>" ) ?> </td>
        <td> <?= $category->description ?> </td>
    </tr>
    <?php endforeach; ?>

    </tbody>
</table>

<?php else:?>
    <p> Список категорий пуст</p>
<?php endif; ?>

