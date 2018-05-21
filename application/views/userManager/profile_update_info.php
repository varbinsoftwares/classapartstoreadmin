<?php
$this->load->view('layout/layoutTop');
?>
<style>
    .product_image{
        height: 200px!important;
    }
    .product_image_back{
        background-size: contain!important;
        background-repeat: no-repeat!important;
        height: 200px!important;
        background-position-x: center!important;
        background-position-y: center!important;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="">

        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Vendor Id:<?php echo $user_details->email; ?></h3>
            </div>
            <div class="box-body">
                <form action="#" method="post" enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="thumbnail">
                                <div class="product_image product_image_back" style="background: url(<?php
                                if ($user_details->image) {
                                    echo base_url() . 'assets_main/userimages/' . $user_details->image;
                                } else {
                                    echo (base_url() . "assets_main/" . default_image);
                                }
                                ?>)">
                                </div>
                                <div class="caption">
                                    <div class="form-group">
                                        <label for="image1">Upload Store Image</label>
                                        <input type="file" name="picture" />           
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="col-md-12">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >First Name</label>
                                        <input type="text" class="form-control" name="first_name"  placeholder="First Name" value="<?php echo $user_details->first_name; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Last Name</label>
                                        <input type="text" class="form-control"  name="last_name"  placeholder="Last Name" value="<?php echo $user_details->last_name; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email address</label>
                                        <span class="form-control"  ><?php echo $user_details->email; ?></span> 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact No.</label>
                                        <input type="text" class="form-control"  name="contact_no" placeholder="Contact No." value="<?php echo $user_details->contact_no; ?>">
                                    </div>
                                </div>



                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control"  placeholder="Address" name="address"><?php echo $user_details->address; ?></textarea>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label >City</label>
                                        <input type="text" class="form-control" name="city"  placeholder="City" value="<?php echo $user_details->city; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label >State</label>
                                        <input type="text" class="form-control"  name="state"  placeholder="State" value="<?php echo $user_details->state; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pincode</label>
                                        <input type="text" class="form-control"  name="pincode"  placeholder="Pincode" value="<?php echo $user_details->pincode; ?>">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" name="submit" class="btn btn-primary">Update Profile</button>
                                    <button type="submit" name="delete_user" class="btn btn-danger pull-right" value="<?php echo $user_details->id; ?>" >Delete User</button>

                                    <button type="submit" name="<?php echo $user_details->status=='Blocked'?'unblock_user':'block_user'; ?>" class="btn btn-warning pull-right" value="<?php echo $user_details->id; ?>" style="margin-right: 10px"><?php echo $user_details->status=='Blocked'?'Unblock User':'Block User'; ?></button>


                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- end col-6 -->
</div>


<?php
$this->load->view('layout/layoutFooter');
?> 

