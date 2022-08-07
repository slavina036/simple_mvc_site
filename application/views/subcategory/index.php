<?php
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;

$User = Config::getObject('core.user.class');
?>
<?php // include('includes/admin-subcategories-nav.php'); ?>

<h1>Список подкатегорий</h1>
<p>Всего подкатегорий: <?= count($subcategories)?></p>
<p><a href="<?= Url::link("admin/subcategories/add") ?>">Добавить новую подкатегорию</a></p>
<?php if (!empty($subcategories)): ?>
<table class="table">
    <thead>
    <tr>
      <th scope="col">Название</th>
      <th scope="col">Описние</th>
    </tr>
     </thead>
    <tbody>
    <?php foreach($subcategories as $subcategory): ?>
    <tr>
        <td> <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('admin/subcategories/edit&id='
		. $subcategory->id . ">{$subcategory->name}</a>" ) ?> </td>
        <td> <?= $subcategory->description ?> </td>
    </tr>
    <?php endforeach; ?>

    </tbody>
</table>

<?php else:?>s
    <p> Список категорий пуст</p>
<?php endif; ?>

