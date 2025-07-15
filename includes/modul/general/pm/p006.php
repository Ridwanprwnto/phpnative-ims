<?php
    $page_id = $_GET['page'];
    $office_id = $_SESSION['office'];
    $dept_id = $_SESSION['department'];
    $user = $_SESSION["user_name"];

    $strplus_pi = rplplus($page_id);
    $dec_page = decrypt($strplus_pi);

    if(file_exists("documentation/imsguide.pdf")) { ?>

        <!-- Basic form layout section start -->
        <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">User Manual IMS Application</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body card-dashboard">
                        <embed src="documentation/imsguide.pdf" type="application/pdf" width="100%" height="600px" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- // Basic form layout section end -->
    <?php
    }
    else {
        include ("includes/templates/error-404.php");
    }
?>