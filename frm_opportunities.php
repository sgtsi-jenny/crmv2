<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

	$opportunity="";
  if(!empty($_GET['id'])){
        $opportunity=$con->myQuery("SELECT id,opp_name,org_id,contact_id,opp_type,assigned_to,sales_stage,forecast_amount,amount,description,product_set,expected_close_date,date_created,date_modified FROM opportunities WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($opportunity)){
            //Alert("Invalid asset selected.");
            //Modal("Invalid Opportunity Selected");
            redirect("opportunities.php");
            die();
        }
        //var_dump($opportunity);
        //die;
    }

    $opp_types=$con->myQuery("SELECT id,name FROM opp_types where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $opp_statuses=$con->myQuery("SELECT id,name FROM opp_statuses where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $org_name=$con->myQuery("SELECT id,org_name FROM organizations")->fetchAll(PDO::FETCH_ASSOC);
    $prod=$con->myQuery("SELECT id,product_name,unit_price from products")->fetchAll(PDO::FETCH_ASSOC);
    $contact=$con->myQuery("SELECT id,CONCAT(fname,' ',lname) as name from contacts")->fetchAll(PDO::FETCH_ASSOC);
    $user=$con->myQuery("SELECT id,CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);
                                            
    makeHead("Opportunities");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            New Opportunity Form
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-10 col-md-offset-1'>
				<?php
					Alert();
				?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                	<div class='col-sm-12 col-md-8 col-md-offset-2'>
                        <form class='form-horizontal' method='POST' action='save_opp.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($opportunity)?$opportunity['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Opportunity Name *</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='opp_name' placeholder='Enter Opportunity Name' value='<?php echo !empty($opportunity)?$opportunity['opp_name']:"" ?>' required>
                                    </div>
                                </div>

                                <!-- <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Organization Name</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control' name='org_id' data-placeholder="Select a Organization name" <?php echo!(empty($opportunity))?"data-selected='".$opportunity['org_id']."'":NULL ?> >
                                                    <?php
                                                        echo makeOptions($org_name);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='' class='btn btn-sm btn-success'><span class='fa  fa-plus'></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Contact Name</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control' name='contact_id' data-placeholder="Select a Contact name" <?php echo!(empty($opportunity))?"data-selected='".$opportunity['contact_id']."'":NULL ?>>
                                                    <?php
                                                        echo makeOptions($contact,'Select Contact Name',NULL,'',!(empty($opportunity))?$opportunity['contact_id']:NULL)
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='' class='btn btn-sm btn-success'><span class='fa  fa-plus'></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Expected Close Date*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <?php
                                        $expected_close_date="";
                                         if(!empty($opportunity)){
                                            $expected_close_date=$opportunity['expected_close_date'];
                                            if($expected_close_date=="0000-00-00"){
                                                $expected_close_date="";
                                            }
                                         }
                                        ?>
                                        <?php
                                        $purchase_date="";
                                         if(!empty($asset)){
                                            $purchase_date=$asset['purchase_date'];
                                            if($purchase_date=="0000-00-00"){
                                                $purchase_date="";
                                            }
                                         }
                                        ?>
                                        <input type='date' class='form-control' name='expected_close_date' value='<?php echo $expected_close_date; ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Sales Stage *</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control' name='sales_stage' data-placeholder="Select Sales Stage" <?php echo!(empty($opportunity))?"data-selected='".$opportunity['sales_stage']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($opp_statuses,'Select Stage',NULL,'',!(empty($opportunity))?$opportunity['sales_stage']:NULL)
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='' class='btn btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Type</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control' name='opp_type' data-placeholder="Select a Opportunity Type" <?php echo!(empty($opportunity))?"data-selected='".$opportunity['opp_type']."'":NULL ?>>
                                                    <?php
                                                        echo makeOptions($opp_types,'Select type',NULL,'',!(empty($opportunity))?$opportunity['opp_type']:NULL)
                                                    ?>
                                                </select>

                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='' class='btn btn-sm btn-success'><span class='fa  fa-plus'></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Forecast Amount</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='0.00' name='forecast_amount' value='<?php echo !empty($opportunity)?$opportunity['forecast_amount']:"0" ?>'>
                                    </div>
                                </div>


                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Description</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control' name='description' value=''><?php echo !empty($opportunity)?$opportunity['description']:"" ?></textarea>
                                    </div>
                                </div>                             
                                

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='opportunities.php' class='btn btn-default'>Cancel</a>
                                        <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                    </div>
                                    
                                </div>                          
                            </form>
                      </div>
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable();
      });
</script>

<?php
	makeFoot();
?>