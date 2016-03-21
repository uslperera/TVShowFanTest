<div class="container i-container">    
    <?php
    echo $this->breadcrumbs->show();
    ?>
    <hr/> 
    <div class="row">                
        <div class="col-lg-8">
            <h2><?php echo $quiz_title; ?></h2>

            <?php
            //create the form open tag
            $attributes = array('name' => 'frmQuiz', 'id' => 'frmQuiz');
            echo form_open('/quiz/finish', $attributes);

            //add the hidden field
            echo form_hidden('quiz', $quiz_id);
            ?>            

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

                        <?php
                        if ($question['isMultiple'] == true) {
                            echo '<p>*(Multiple answers)</p>';
                        }
                        ?>

                        <br/>
                        <?php foreach ($question['choices'] as $key => $choice) { ?>
                            <label class="question-choices">
                                <?php
                                if ($question['isMultiple'] == true) {
                                    $data = array('name' => 'answers[' . $question['id'] . '][' . $key . ']', 'id' => $key);
                                    echo form_checkbox($data);
                                } else {
                                    $data = array('name' => 'answers[' . $question['id'] . ']', 'id' => $key, 'value' => $key);
                                    echo form_radio($data);
                                }
                                ?>
                                <span><?php echo $choice ?></span>
                            </label>
                            <br/>
                            <?php
                        }
                        ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php echo form_close(); ?>
        </div><!--/.col-lg-8 -->
    </div><!--/.row -->
</div><!--/.container -->

<div class="navbar-fixed-bottom quiz-bar">
    <div class="row finish">        
        <input type="button" value="Submit" onclick="submitForm();" class="btn btn-default"/>
        <label id="time" class="label label-default"></label>
    </div><!--/.row-->
</div><!--/.navbar-fixed-bottom -->

<script type="text/javascript">

    function submitForm() {
        //submit the form
        document.getElementById("frmQuiz").submit();
    }

    //initialize time
    var time = <?php echo $quiz_time; ?>;

    //create timer
    setInterval(function () {
        var timer = document.getElementById('time');
        //if time is over submit the form
        if (time === 0) {
            submitForm();
        }
        //get seconds
        var seconds = time % 60;
        //if seconds is less than 10 append 0
        seconds = seconds < 10 ? '0' + seconds : seconds;
        //set time left to the label
        timer.innerHTML = Math.floor(time / 60) + ':' + seconds;
        //decrement time left by 1
        time--;
    }, 1000);
</script>