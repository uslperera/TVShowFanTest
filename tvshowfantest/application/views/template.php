<html>
    <head><?php $this->load->view('common/head'); ?></head>
    <body>
        <?php $this->load->view('common/header'); ?>
        <?php $this->load->view($view, $dataBag); ?>
        <?php $this->load->view('common/footer'); ?>
    </body>
</html>