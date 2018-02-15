<?php
$searching = (isset($_SESSION['medimax']['search'][$this->activeModuleId]) ? $_SESSION['medimax']['search'][$this->activeModuleId] : null);
$messages = array();
if ($this->filter->getActiveFromSession() !== null) {
    $messages[] = "Filtrování položek je aktivní!";
}
if ($searching !== null) {
    $resetlink = "<a href='" . $_SERVER['REQUEST_URI'] . "&searchreset'><img src='images/icons/delete2.png' class='icon'>zrušit</a>";
    $messages[] = "Zobrazeny jsou pouze výsledky vyhledávání výrazu '" . $searching . "'. &nbsp;&nbsp;" . $resetlink;
}

// filtrovani
if (($mode = _post('btn', null, true)) !== null) {
    $this->filter->refreshFilters(key($mode), _post($this->filter->config['select.name']));
}
// reset vyhledavaneho vyrazu
if (_get('searchreset') !== null) {
    unset($_SESSION['medimax']['search'][$this->activeModuleId]);
    header("Location: index.php?p=medimax&m=" . $this->activeModuleId);
}
?>

<div class="bread-list-before">
    <div class="new-item"><?php $this->renderLink('new', 'vytvořit nový záznam', 'create', null, 'list') ?></div>
    <?php echo $paging[0]; ?>
    <div class="filter">
        <div class="filter-search-forms">
            <form action="" method="post" name="filterform" style="display: none;">
                <?php echo $this->filter->generateSelect(); ?>
                <button type="submit" name="btn[set]" title="Nastavit"><img
                            src="<?php echo MedimaxConfig::getDirectory('root') . 'resources/images/icons/apply.png' ?>">
                </button>
                <button type="submit" name="btn[del]" title="Odebrat"><img
                            src="<?php echo MedimaxConfig::getDirectory('root') . 'resources/images/icons/erase.png' ?>">
                </button>
                <button type="submit" name="btn[clear]" title="Zrušit filtrování"><img
                            src="<?php echo MedimaxConfig::getDirectory('root') . 'resources/images/icons/stop.png' ?>">
                </button>
                <?php echo _xsrfProtect(); ?>
            </form>
            <form action="" method="post" name="searchform" style="display: none;">
                <input type="search" name="<?php echo $this->filter->config['select.name'] ?>">
                <input type="submit" name="btn[search]" value="Vyhledat výraz">
                <?php echo _xsrfProtect(); ?>
            </form>
        </div>
        <div class="filter-search-actions">
            <button id="fshow" title="Filtrace"><img
                        src="<?php echo MedimaxConfig::getDirectory('root') . 'resources/images/icons/filter.png' ?>">
            </button>
            <button id="fhide" title="Filtrace" style="display: none;"><img
                        src="<?php echo MedimaxConfig::getDirectory('root') . 'resources/images/icons/filter.png' ?>">
            </button>
            <button id="sshow" title="Vyhledávání"><img
                        src="<?php echo MedimaxConfig::getDirectory('root') . 'resources/images/icons/loupe.png' ?>">
            </button>
            <button id="shide" title="Vyhledávání" style="display: none;"><img
                        src="<?php echo MedimaxConfig::getDirectory('root') . 'resources/images/icons/loupe.png' ?>">
            </button>
        </div>
        <div class="cleaner"></div>
    </div>
    <div style='clear: both'></div>
</div>

<?php if (count($messages) > 0) {
    echo _formMessage(2, _eventList($messages));
} ?>

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
