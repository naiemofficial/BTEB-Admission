<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    include('header.php');
    include('aside.php');
} ?>



<div class="row">
    <div class="col-3">
        <section id="third">
            <div class="avartar">
                <a href="#">
                    <img id="applicant_image" src="assets/images/applicant.jpg" class="applicant" alt="your image" />
                </a>
                <div class="avartar-picker">
                    <input type="file" id="applicant_picture" class="inputfile" onchange="readURL(this);" />

                    <label for="applicant_picture">
                        <i class="zmdi zmdi-camera"></i>
                        <span>Choose Picture</span>
                    </label>
                </div>
                <div class="progress upload_progress">
                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-9">
        <div class="info_h">
            SSC Information
        </div>
        <section class="profile_overview">
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" id="name"  readonly>
                    <label for="name">Name :</label> 
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" id="father"  readonly>
                    <label for="father">Father's Name :</label> 
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" id="mother"  readonly>
                    <label for="mother">Mother's Name :</label> 
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" id="date-of-birth"  readonly>
                    <label for="date-of-birth">Date of Birth :</label> 
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control w-incr" id="board" readonly>
                    <label for="board" class="w-incr">Board :</label> 
                </div>
                <div class="col">
                    <input type="number" class="form-control w-incr" id="year" readonly>
                    <label for="year" class="w-incr">Passing Year :</label> 
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="number" class="form-control w-incr" id="roll" readonly>
                    <label for="roll" class="w-incr">Roll :</label> 
                </div>
                <div class="col">
                    <input type="number" class="form-control w-incr" id="reg" readonly>
                    <label for="reg" class="w-incr">Reg :</label> 
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" id="gender" enable readonly>
                    <label for="gender">Gender :</label>
                    <div class="select_gender enable label">
                        <label class="radio">Male
                            <input type="radio" name="gender" value="male">
                            <span></span>
                        </label>
                        <label class="radio">Female
                            <input type="radio" name="gender" value="female">
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-12">
        <section class="profile_overview subject_eligibility" id="third">
            <div class="info_h">
                Application Eligibility By Subject
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Subject Code</th>
                        <th scope="col">Subject Name</th>
                        <th scope="col">Grade</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- eligible Subject Table -->
                </tbody>
            </table>
        </section>
        <?php include('button_next.php'); ?> 
    </div>
</div>


<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    // this part is show wintout ajax request
    include('footer.php');
}?>