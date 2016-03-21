<div class="container i-container">
    <?php
    echo $this->breadcrumbs->show();
    ?>
    <hr/> 
    <div class="row">
        <div class="col-lg-8">
            <div id="certificate">
                <h2><?php echo $quiz_title; ?></h2>

                <p><?php echo $quiz_description; ?></p>
                <p>
                    The quiz consists of <?php echo $question_count . ' questions'; ?>
                    and you have
                    <?php echo ($quiz_time / 60) . ' minutes'; ?> to answer.
                </p>
                <a class="btn btn-primary" href="<?php echo base_url() . 'index.php/quiz/start/' . $quiz_id ?>">Start</a>
            </div>
        </div><!--/.col-lg-8 -->
        <div class="col-lg-4">                      
            <ul class="side">
                <h4 class="title">Quizzes</h4>
                <?php foreach ($quizzes as $key => $quiz) { ?>                            
                    <li>
                        <a href="<?php echo base_url() . 'index.php/quiz/index/' . $quiz['id'] ?>">
                            <?php echo $quiz['title'] ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div><!--/.col-lg-4 -->
    </div><!--/.row -->
</div><!--/.container -->