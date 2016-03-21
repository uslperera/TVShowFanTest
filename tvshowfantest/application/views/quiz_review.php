<div class="container i-container">
    <?php
    echo $this->breadcrumbs->show();
    ?>
    <hr/> 
    <div class="row">                
        <div class="col-lg-8">
            <h2><?php echo $quiz_title; ?></h2>
            <ul class="questions">
                <?php
                $question_id = 0;
                foreach ($questions as $key => $question) {
                    ?>

                    <li>
                        <div class="frage">
                            <div class="circle"><?php echo ++$question_id; ?></div>
                            <p class="question-title"><?php echo $question['title'] ?></p>
                        </div>

                        <?php
                        if ($question['image'] != null) {
                            ?>
                            <span class="image">
                                <img src="<?php echo base_url() . $this->config->config['img']; ?>quiz/<?php echo $question['image'] ?> " width="200px"/>
                            </span>
                            <?php
                        }
                        ?>

                        <br/>                        
                        <h5>Given Answer:</h5> 

                        <?php
                        if (isset($selected_choices[$key])) {
                            if (is_array($selected_choices[$key])) {
                                foreach ($selected_choices[$key] as $key => $selected_choice) {
                                    echo '<label>' . $question['choices'][$key] . '</label><br/>';
                                }
                            } else {
                                echo '<label>' . $question['choices'][$selected_choices[$key]] . '</label><br/>';
                            }
                        }
                        ?> 

                        <br/>
                        <h5>Correct Answer:</h5>
                        <?php
                        foreach ($question['correct_choices'] as $correct_choice) {
                            echo '<label>' . $correct_choice . '</label><br/>';
                        }
                        ?>
                    </li>

                    <?php
                }
                ?>
            </ul>
        </div><!--/.col-lg-8 -->
    </div><!--/.row -->
</div><!--/.container -->