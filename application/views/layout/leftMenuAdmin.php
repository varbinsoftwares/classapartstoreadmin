<!-- begin #sidebar -->
<?php
$session_data = $this->session->userdata('logged_in');
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php if ($session_data['image']) { ?>
                    <img src="<?php echo base_url(); ?>assets_main/userimages/<?php echo $session_data['image']; ?>" class="img-circle" alt="User Image" style="    height: 45px;">

                    <?php
                } else {
                    ?>
                    <img src="<?php echo base_url(); ?>assets_main/image/logo.png" class="img-circle" alt="User Image">
                <?php } ?> 

            </div>
            <div class="pull-left info">
                <p style="line-height: 35px;"><?php echo $session_data['first_name'] . " " . $session_data['last_name']; ?></p>
            </div>
        </div>

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-archive"></i>
                    <span>Product Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li>
                        <a href="<?php echo base_url(); ?>index.php/ProductManager/add_product">
                            <i class="active fa fa-plus "></i> <span>Add Product</span>
                        </a>
                    </li>   
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/ProductManager/productReport">
                            <i class="active fa fa-plus "></i> <span>Product Reports</span>
                        </a>
                    </li>    


                    <!--Admin Access-->
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/ProductManager/categories">
                            <i class="active fa fa-plus "></i> <span>Categories</span>
                        </a>
                    </li>     
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/ProductManager/createAttribute">
                            <i class="active fa fa-plus "></i> <span>Attributes</span>
                        </a>
                    </li>     
                    <!--end of admin access-->


                </ul>
            </li>



            <?php if ($session_data['user_type'] == 'Admin') { ?>


                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-user"></i>
                        <span>User Management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!--Admin Access-->
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/UserManager/addVendor">
                                <i class="active fa fa-plus "></i> <span>Add User</span>
                            </a>
                        </li>   
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/UserManager/usersReport">
                                <i class="active fa fa-plus "></i> <span>Users Reports</span>
                            </a>
                        </li>   
                        <!--end of admin access-->

                    </ul>
                </li>

                <li>
                    <?php
                }
                ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
