<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include('back/addbatches.php');
?>
<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="container">
        <div class="container">


            <div class="row layout-top-spacing">

                <div id="basic" class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Add or Remove Batches</h4>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">

                            <div class="row">
                                <div class="col-lg-6 col-12 ">
                                    <form method="post">
                                        <div class="form-group">
                                            <p>Batch</p>
                                            <label for="t-text" class="visually-hidden">Text</label>
                                            <input id="t-text" type="text" name="name" placeholder="Enter Batch..." class="form-control" required>
                                            <p> </p>
                                        </div>

                                        <div class="form-group">
                                            <p>Student Count</p>
                                            <label for="t-text" class="visually-hidden">Text</label>
                                            <input id="t-text" type="text" name="count" placeholder="Enter Student Count..." class="form-control" required>
                                            <p> </p>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="exampleFormControlSelect1">Select School</label>
                                            <select class="form-select" name="school" id="school">
                                                <option value="computing">School of Computing</option>
                                                <option value="business">School of Business</option>
                                                <option value="langueges">School of Languages</option>
                                            </select>
                                        </div>

                                        
                                        <!--button-->
                                        <button class="btn btn-success mb-2 me-4">Add</button>


                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        
                        <?php include('back/updatetablebatch.php'); ?>
                    </table>
                </div>

                
        