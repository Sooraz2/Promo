<div class="alert alert-<?php if ($_SESSION["ConfirmationMessageType"] == "Success") echo 'success';
else if ($_SESSION["ConfirmationMessageType"] == "Failed") echo 'danger'; else if ($_SESSION["ConfirmationMessageType"] == "Warning") echo 'warning'; ?> alert-dismissible"
     role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <?php echo  $_SESSION["ConfirmationMessage"] ?>
</div>

