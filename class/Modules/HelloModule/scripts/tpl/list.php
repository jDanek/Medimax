<?php
// filtrovani
if (($mode = _post('btn', null, true)) !== null)
{
    $this->filter->refreshFilters(key($mode), _post($this->filter->config['select.name']));
}
?>

<div class="bread-list-before">
    <div class="new-item"><?php $this->renderLink('new', 'vytvořit nový záznam', 'create', null, 'list') ?></div>
    <?php echo $paging[0]; ?>
    <div class="filter">
        <form action="" method="post" name="filterform">

            <?php echo $this->filter->generateSelect(); ?>

            <input type="submit" name="btn[set]" value="Nastavit">
            <input type="submit" name="btn[del]" value="Odebrat">
            <input type="submit" name="btn[clear]" value="Zrušit filtry">
            <?php echo _xsrfProtect(); ?>
        </form>
    </div>
    <div style='clear: both'></div>
</div>

<table class="list" width="100%">

    <thead>
    <tr>
        <td class="minimal-width">#</td>
        <td class="minimal-width">Username</td>
        <td class="minimal-width">Email</td>
        <td><!-- placeholder //--></td>
        <td class="minimal-width">Akce</td>
    </tr>
    </thead>

    <tbody>
    <?php while ($item = DB::row($result)): ?>
        <tr>
            <td><?php $this->renderStr($item['id']) ?></td>
            <td><?php $this->renderStr($item['username']) ?></td>
            <td><?php $this->renderStr($item['email']) ?></td>
            <td><!-- placeholder //--></td>

            <!-- AKCE //-->
            <td class="bread-actions">
                <?php $this->renderLink('edit', '<span>editovat</span>', 'edit', array($item['id']), $self, array('class' => 'list-action-btn')) ?>
                <?php $this->renderLink('delete3', '<span>smazat</span>', 'del', array($item['id']), $self, array('class' => 'list-action-btn')) ?>
            </td>
        </tr>
    <?php endwhile ?>
    </tbody>

</table>

<?php echo $paging[0]; ?>
