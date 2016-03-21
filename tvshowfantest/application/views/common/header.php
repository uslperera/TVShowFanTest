<nav class="navbar navbar-default i-navbar">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display --> 
        <div class="navbar-header">
            <a class="navbar-brand i-nav-title" href="<?php echo base_url(); ?>"><?php echo $this->config->config['site_title']; ?></a>
        </div><!-- /.navbar-header -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse i-search" id="bs-example-navbar-collapse-1">
            <?php
            $attributes = array('class' => 'navbar-form navbar-left', 'role' => 'search', 'name' => 'frmSearch', 'id' => 'frmSearch');
            echo form_open('search/index', $attributes);
            ?>
            <div class="form-group">                
                <?php
                $data = array('name' => 'searchQuery', 'id' => 'searchQuery', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Search');
                echo form_input($data);
                ?>
            </div><!-- /.form-group-->
            <button type="submit" class="btn btn-default">
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            </button>
            <?php echo form_close(); ?>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>