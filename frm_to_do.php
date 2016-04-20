<?php
    require_once("support/config.php");
    if(!isLoggedIn()){
        toLogin();
        die();
    }

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

    $to_do="";
  if(!empty($_GET['id'])){
        $to_do=$con->myQuery("SELECT ev.stat_id,ev.event_stat,ev.atype_id,ev.activity_type,ev.priority_id,ev.priority,ev.location,ev.event_place,ev.description,ev.subjects,DATE(ev.start_date) AS start_date,TIME(ev.start_date) AS start_time,DATE(ev.end_date) AS end_date,TIME(ev.start_date) AS end_time,ev.opp_name,ev.assigned_to,ev.due_date,ev.id FROM vw_calendar ev WHERE ev.is_deleted=0 AND ev.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($to_do)){
            redirect("events.php");
            die();
        }
    }

    $to_do_stat=$con->myQuery("SELECT id,name FROM event_status where type=1 or type=3")->fetchAll(PDO::FETCH_ASSOC);
    $activity_type=$con->myQuery("SELECT id,name FROM act_types")->fetchAll(PDO::FETCH_ASSOC);
    $org_name=$con->myQuery("SELECT id,org_name FROM organizations")->fetchAll(PDO::FETCH_ASSOC);
    $prod=$con->myQuery("SELECT id,product_name,unit_price from products")->fetchAll(PDO::FETCH_ASSOC);
    $prior=$con->myQuery("SELECT id,name FROM priorities")->fetchAll(PDO::FETCH_ASSOC);
    $contact=$con->myQuery("SELECT id,CONCAT(fname,' ',lname) as name from contacts")->fetchAll(PDO::FETCH_ASSOC);
    $user=$con->myQuery("SELECT id,CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);
                           
    makeHead("Calendar");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create New To Do
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
                    <div class='col-sm-12 col-md-8 col-md-offset-1'>
                        <form class='form-horizontal' method='POST' action='save_to_do.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($to_do)?$to_do['id']:""?>'>
                                <!-- <input type='hidden' name='to_do_id' value='<?php echo $to_do['id']?>'> -->
                                <div class='form-group'>
                                    <label for="" class="col-sm-4 control-label"> Subject *</label>
                                    <div class='col-sm-8'>
                                        <input type='text' class='form-control' name='subject' placeholder='Enter Subject Name' value='<?php echo !empty($to_do)?$to_do['subjects']:"" ?>' required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Start Date*</label>
                                    <div class="col-sm-8">
                                            <?php
                                                $start_date="";
                                                if(!empty($to_do)){
                                                $start_date=$to_do['start_date'];
                                                if($start_date=="0000-00-00"){
                                                $start_date="";
                                                }
                                                }
                                            ?>
                                        <input type='date' class='form-control' name='start_date' value='<?php echo $start_date; ?>' required>
                                    </div>
                                    <label for="" class="col-sm-4 control-label">Time*</label>
                                    <div class="col-sm-3">
                                    <input type="time" class="form-control" value='<?php echo !empty($to_do)?$to_do['start_time']:"" ?>' id="until_t" name="start_time" required/>
                                </div>
                              </div>

                              <div class="form-group">
                                <label for="" class="col-sm-4 control-label">Due Date*</label>
                                <div class="col-sm-8">
                                        <?php
                                                $due_date="";
                                                if(!empty($to_do)){
                                                $due_date=$to_do['due_date'];
                                                if($due_date=="0000-00-00"){
                                                $due_date="";
                                                }
                                                }
                                            ?>
                                    <input type='date' class='form-control' name='due_date' value='<?php echo $due_date; ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label for="" class="col-md-4 control-label">Status*</label>
                                    <div class="col-sm-8">
                                        <select class='form-control' name='status' data-placeholder="Select an option" <?php echo!(empty($to_do))?"data-selected='".$to_do['stat_id']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($to_do_stat);
                                                    ?>
                                        </select>
                                    </div>  
                                </div>
                              
                              <!-- <div class='form-group'>
                                    <label class='col-sm-4 control-label' > User *</label>
                                    <div class='col-sm-6'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                             <select id="disabledSelect" class='form-control' name='assigned_to' data-placeholder="Select a user" <?php echo!(empty($opportunity))?"data-selected='".$opportunity['assigned_to']."'":"data-selected='".$_SESSION[WEBAPP]['user']['id']."'" ?> required>
                                                <?php
                                                    echo makeOptions($user);
                                                ?>
                                            </select>
                                            </div>
                                            <?php
                                                if($_SESSION[WEBAPP]['user']['user_type']==1):
                                            ?>
                                            <div class='col-ms-1'>
                                            <a href='frm_users.php' class='btn btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                            <?php
                                                endif;
                                            ?>
                                        </div>
                                    </div>
                                </div> -->
                              
                              <div class='form-group'>
                                    <label class='col-sm-4 control-label' > Priority</label>
                                    <div class='col-sm-8'>
                                        
                                                <select class='form-control' name='priority' data-placeholder="Select an option" <?php echo!(empty($to_do))?"data-selected='".$event['priority_id']."'":NULL ?>>
                                                    <?php
                                                    echo makeOptions($prior);
                                                ?>
                                                </select>
                                    </div>
                                </div>

                               <div class='form-group'>
                                    <label for="" class="col-sm-4 control-label"> Location</label>
                                    <div class='col-sm-8'>
                                        <input type='text' class='form-control' name='event_place' placeholder='Enter Location' value='<?php echo !empty($to_do)?$to_do['event_place']:"" ?>'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label for="" class="col-sm-4 control-label"> Description</label>
                                    <div class='col-sm-8'>
                                        <textarea class='form-control' placeholder="Enter Description" name='description' value=''><?php echo !empty($to_do)?$to_do['description']:"" ?></textarea>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='calendar_list.php' class='btn btn-default'>Cancel</a>
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