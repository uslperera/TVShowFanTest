<div class="container i-container">
    <?php
    echo $this->breadcrumbs->show();
    ?>
    <hr/>
    <input type="hidden" value="<?php echo $quiz_id ?>" name="quiz"/>
    <div class="row">
        <div class="col-lg-8">
            <div id="certificate">
                <h2><?php echo $quiz_title; ?></h2>
                <p>
                    <?php echo $message; ?>
                    <br/>
                    You have correctly answered <?php echo $score . ' of ' . $question_count; ?> questions.
                    <br/>
                    On average, users gave <?php echo $average_score; ?> correct answers for the quiz
                    <br/>
                    <a href="<?php echo base_url() . 'index.php/quiz/review/' . $quiz_id ?>">Review</a>
                </p>
                <div id="share">
                    <?= share_button('twitter', array('url' => '', 'text' => $message, 'via' => 'TVShowFanTest', 'type' => 'iframe')) ?>
                    <?= share_button('facebook', array('url' => '', 'description' => $message)) ?>
                </div><!--/.share -->
            </div><!--/.certificate -->
        </div><!--/.col-lg-8 -->
        <div class="col-lg-4">
                <ul class="side">
                    <h4 class="title">Quizzes</h4>
                    <?php foreach ($quizzes as $key => $quiz) { ?>
                    <li>
                        <a href="<?php echo base_url().'index.php/quiz/index/' . $quiz['id'] ?>">
                            <?php echo $quiz['title'] ?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
        </div><!--/.col-lg-4 -->
    </div><!--/.row -->
</div><!--/.container -->
