<div class="panel-body">
    <div class="dashboardMessages">
        <?php foreach ($_dashboardMessages as $_msg) { ?>
            <div class="alert <?php echo
                ($_msg['type'] == 'error'
                    ? 'alert-danger'
                    : 'alert-success');
            ?> alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <ul id="msg_container">
                    <li><?php echo $_msg['msg']; ?></li>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>
