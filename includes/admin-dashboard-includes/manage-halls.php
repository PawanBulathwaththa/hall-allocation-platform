<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include('back/addhalls.php');
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
                                    <h4>Add Hall / Lab</h4>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">

                            <div class="row">
                                <div class="col-lg-6 col-12 ">
                                    <form method="post">
                                        <div class="form-group mb-2">
                                            <p>Hall</p>
                                            <input id="t-text" type="text" name="name" placeholder="Enter Hall..." class="form-control" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <p>Hall Capacity</p>
                                            <input id="t-text" type="text" name="capacity" placeholder="Enter Hall Capacity..." class="form-control" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <p>Select Floor</p>
                                            <select class="form-select" name="floor" id="floor">
                                                <option value="Ground Floor">Ground Floor</option>
                                                <option value="1st Floor">1st Floor</option>
                                                <option value="2nd Floor">2nd Floor</option>
                                                <option value="3rd Floor">3rd Floor</option>
                                                <option value="4th Floor">4th Floor</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-2">
                                            <p>Select Hall Type</p>
                                            <select class="form-select" name="hallType" id="hallType">
                                                <option value="1">Hall (with Smart Board)</option>
                                                <option value="2">Hall (without Smart Board)</option>
                                                <option value="3">Lab</option>
                                            </select>
                                        </div>
                                        <p> </p>
                                        <button type="submit" class="btn btn-success mb-2">Add</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        
                        <?php
                        include('back/updatetablehall.php');
                        ?>
                    </table>


                </div>

                