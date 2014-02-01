<form method="post">
    <p>Opravdu chcete odstranit tuto položku?<br /><br />
        <q><?php $this->renderStr($data['username']) ?></q></p><br />
    <p><input type="submit" name="<?php echo $submit_trigger ?>" value="<?php echo $submit_text ?>" disabled="disabled" /></p>
</form>