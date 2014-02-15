<h1>View Example</h1>
<ul>
    <li>ID: <?= $main->id ?></li>
    <li>Name: <?= $main->name ?></li>
    <li>Another field: <?= $main->another_field?></li>
    <li>Created: <?= format_date($main->created) ?></li>
    <li>Created by: <?= $main->creator ?></li>
    <li>Modified: <?= format_date($main->modified) ?></li>
    <li>Modified by: <?= $main->modifier ?></li>
</ul>

