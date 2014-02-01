<form method='post'>
    <table class='list'>
        <tbody>
            <tr>
                <td>Uzivatelske jmeno</td>
                <td><input type="text" name="username" <?php echo _restorePostValue('username', $data['username']); ?> /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" <?php echo _restorePostValue('email', $data['email']); ?> /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <?php echo _xsrfProtect(); ?>
                    <input type="submit" name="<?php echo $submit_trigger ?>" value="<?php echo $submit_text ?>" class='inputsmall' />
                </td>
            </tr>
        </tbody>
    </table>
</form>