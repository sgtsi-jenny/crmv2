<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

    $tab="2";
    if(!empty($_GET['tab']) && !is_numeric($_GET['tab'])){
        redirect("my_cal.php".(!empty($employee)?'?id='.$employee['id']:''));
        die;
    }
    else{
        if(!empty($_GET['tab'])){
            if($_GET['tab'] >0 && $_GET['tab']<=9){
                $tab=$_GET['tab'];
            }
            else{
                #invalid TAB
                redirect("my_cal.php".(!empty($employee)?'?id='.$employee['id']:''));
            }
        }
    }

  $data=$con->myQuery("SELECT event_stat,activity_type,subjects,is_related,start_date,end_date,description,id FROM vw_calendar where activity_type<>5 and assigned_to=?",array($_SESSION[WEBAPP]['user']['id']))->fetchAll(PDO::FETCH_ASSOC);
    makeHead("Calendar");

?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- <section class="content-header">
          <h1>
            Opportunities
          </h1>
        </section> -->
        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <!-- <?php
                        $no_employee_msg=' Personal Information must be saved.';
                    ?> -->
                    <li ><a href="my_cal.php" >Calendar</a>
                    </li>
                    <li <?php echo $tab=="2"?'class="active"':''?>><a href="">Calendar List</a>
                    
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                  <?php
                    Alert();
                  ?>
                  <br/>
                                  <div class='col-ms-12 text-right'>
                                        <a href='frm_events.php' class='btn btn-brand'> <span class='fa fa-plus'></span> Add New Event</a>
                                        <a href='frm_to_do.php' class='btn btn-brand'> <span class='fa fa-plus'></span> Add New To Do</a>

                                  </div>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Status</th>
                                                <th class='text-center'>Activity Type</th>
                                                <th class='text-center'>Subject</th>
                                                <th class='text-center'>Related To</th>
                                                <th class='text-center'>Start Date & Time</th>
                                                <th class='text-center'>End Date</th>
                                                <th class='text-center'>Description</th>
                                                <th class='text-center' style='min-width:80px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                                        <?php
                                            //Filter by date not less than date today
                                            $calendars=$con->myQuery("SELECT event_stat,activity_type,subjects,is_related,start_date,end_date,description,id FROM vw_calendar where activity_type<>5 and is_deleted=0 AND start_date>=NOW() and assigned_to=?",array($_SESSION[WEBAPP]['user']['id']))->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($calendars as $row):
                                            
                                        ?>

                                                <tr>
                                                        <?php
                                                            foreach ($row as $key => $value):                                                            
                                                        ?>                                                                                                                   
                                                        <?php
                                                            if($key=='users'):
                                                        ?>
                                                            <td>
                                                                <a href='view_user.php?id=<?= $row['assigned_to']?>'><?php echo htmlspecialchars($value)?></a>
                                                            </td> 
                                                        <?php
                                                            elseif($key=='opp_name'):
                                                        ?> 
                                                            <td>
                                                                <a href='opp_details.php?id=<?= $row['id']?>'><?php echo htmlspecialchars($value)?></a>
                                                            </td> 
                                                        <?php
                                                            elseif($key=='id'):
                                                        ?>                                                                          
                                                            <td  class='text-center'>
                                                                <?php
                                                                if($row['activity_type']=="To Do"){
                                                                    $parameter_2="frm_to_do.php?id=";
                                                                }
                                                                else{
                                                                    $parameter_2="frm_events.php?id=";
                                                                }
                                                                    $parameter_2.=$row['id'];
                                                                ?>
                                                                <a class='btn btn-sm btn-brand' href='<?php echo $parameter_2?>'><span class='fa fa-pencil'></span></a>

                                                                <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=eve' onclick='return confirm("This event will be deleted.")'><span class='fa fa-trash'></span></a>
                                                            </td>
                                                        <?php
                                                            else:
                                                        ?>

                                                            <td>
                                                                <?php
                                                                    echo htmlspecialchars($value);
                                                                ?>
                                                            </td>
                                                        <?php
                                                            endif;
                                                            endforeach;
                                                        ?>
                                                </tr>
                                            <?php
                                                endforeach;
                                            ?>
                          </tbody>
                        </table>          
                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
            "scrollX": true
            // ,
            // dom: 'Bfrtip',
            //     buttons: [
            //         {
            //             extend:"excel",
            //             text:"<span class='fa fa-download'></span> Download as Excel File "
            //         }
            //         ],

        });
      });
</script>

<?php
  Modal();
    makeFoot();
?>