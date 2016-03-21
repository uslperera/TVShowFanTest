<div class="container i-container">
    <?php
    echo $this->breadcrumbs->show();
    ?>
    <hr/>        
    <div class="row">
        <div class="col-lg-8">
            <h4 class="section-title">Quizzes</h4>
            <ul class="i-list">
                <?php
                foreach ($quizzes as $quiz) {
                    ?>
                    <div class="row">
                        <li>
                            <a href="<?php echo base_url().'index.php/quiz/index/' . $quiz['id'] ?>"><?php echo $quiz['title'] ?></a>
                            <p><?php echo $quiz['description'] ?></p>
                        </li>
                    </div>
                    <?php
                }
                ?>
            </ul>
        </div><!--/.col-lg-8 -->
        <div class="col-lg-4">
            <div class="">                        
                <ul class="side">
                    <h4 class="title">TV Shows</h4>
                    <?php foreach ($tvshows as $key => $tvshow) { ?>                            
                        <li><a href="<?php echo base_url() . 'index.php/tvshow/index/' . $key ?>"><?php echo $tvshow ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div><!--/.col-lg-4 -->
    </div><!--/.row -->
</div><!--/.container -->