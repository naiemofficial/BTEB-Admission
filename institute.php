<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    // this part is show wintout ajax request
    include('header.php');
    include('aside.php');
} ?>

<div class="choice_selection">
    <div class="inst_placeholder" id="placeholder">
        <div class="row">
            <div class="col-7">
                <div class="form-group">
                    <label for="institute">Select Instiute :</label>
                    <select id="institute" class="form-control">
                        <option value="" selected>Choose one &mdash; &mdash;</option>
                        <option value="123456">Dhaka Polytechnic Institute</option>
                        <option value="133160" selected>Cumilla Polytechnic Institute</option>
                        <option value="">Chandpur Polytechnic Institute</option>
                        <option value="">Chittagong Polytechnic Institute</option>
                    </select>
                    <p class="only incld">It's for testing. So only Cumilla Polytechnic Institute data available in database</p>
                </div>
            </div>
            <div class="col-5">
                <div class="form-group">
                    <label for="shift">Select Shfit :</label>
                    <select id="shift" class="form-control">
                        <option value="" selected>Choose one &mdash; &mdash;</option>
                    </select>
                    <div class="alert alert-info shift_cat">
                        Paid for <span id="shift_cat"></span><br>
                        You can apply <span>only 1st</span> shift
                    </div>
                </div>
                <div class="form-group">
                    <label for="technology">Select Department :</label>
                    <select id="technology" class="form-control">
                        <option value="" selected>Choose one &mdash; &mdash;</option>
                        <option value="666">Computer Technology</option>
                        <option value="665">Civil Technology</option>
                        <option value="667">Electrical Technology</option>
                        <option value="668">Electronics Technology</option>
                        <option value="670">Mechanical Technology</option>
                        <option value="671">Power Technology</option>
                        <option value="na">N/A</option>
                    </select>
                </div>
                <div class="form-group" style="display: none;">
                    <label for="shift">Select Shfit :</label>
                    <label class="radio">Day
                        <input type="radio" name="shift" value="1">
                        <span></span>
                    </label>
                    <label class="radio">Morning
                        <input type="radio" name="shift" value="1">
                        <span></span>
                    </label>

                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <button type="reset" class="btn btn-outline-secondary btn-sm btn-block"> <i class="fa fa-sync"></i> Reset</button>
                        </div>
                        <div class="col">
                            <button type="submit" id="add" class="btn btn-primary btn-sm btn-block"> <i class="fa fa-plus-circle"></i> Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="spiral-divider"></div>
    <div class="row">
        <div class="col-12">
            <section class="institute_list">
                <div class="info_h spiral-support">
                    Your choice List
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">EIIN</th>
                            <th scope="col">Institute Name</th>
                            <th scope="col">Department</th>
                            <th scope="col">Trade</th>
                            <th scope="col">Shift</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                 </table>
                 <div class="alert alert-warning drag">
                    <p>Drag and drop wont't work in mobile. Try in firefox browser on desktop for the best practice. </p>
                </div>
                 <div class="alert alert-info choicedata">
                    You don't have any selected choice data yet
                </div>
            </section>
            <?php include('button_next.php'); ?> 
        </div>
    </div>
</div>



<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    // this part is show wintout ajax request
    include('footer.php');
}?>
