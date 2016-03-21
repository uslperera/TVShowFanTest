<div class="container i-container">
    <p>Welcome to the <?php echo $this->config->config['site_title']; ?>. It is time to check if you are a real fan of your favorite TV Show.</p>
    <hr/>
    <div class="row">
        <div class="col-lg-8">
            <h4 class="section-title">Recent Quizzes</h4>
            <ul class="i-list">
                <?php
                foreach ($quizzes as $quiz) {
                    ?>
                    <div class="row">
                        <li>
                            <a href="<?php echo base_url() . 'index.php/quiz/index/' . $quiz['id'] ?>"><?php echo $quiz['title'] ?></a>
                            <p><?php echo $quiz['description'] ?></p>
                        </li>
                    </div>
                    <?php
                }
                ?>
            </ul>
        </div><!-- /.col-lg-8 -->
        <div class="col-lg-4">                  
                <ul class="side">
                    <h4 class="title">TV Shows</h4>
                    <?php foreach ($tvshows as $key => $tvshow) { ?>                            
                        <li><a href="<?php echo base_url() . 'index.php/tvshow/index/' . $key ?>"><?php echo $tvshow ?></a></li>
                    <?php } ?>
                </ul>
        </div><!-- /.col-lg-8 -->
    </div><!-- /.row -->
</div><!-- /.container i-container-->