<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	makeHead();
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
          </h1>
        </section>
        <?php
                if(AllowUser(array(1,2,3))):
        ?>

        <!-- Main content -->
        <section class="content">
        <div class='row'>
                                <div class='col-md-8'>
                                <div class='panel panel-primary'>
                                <div class='panel-heading text-left'>
                                <div class="row">
                                    <h2 class="col-xs-10">Recent Client Activity</h2>
                                    <span class="fa fa-tasks fa-3x col-md-2" style="padding-top: 15px;"></span>
                                    
                                </div>
                                </div>
                                    <?php
                                    $uid=$_SESSION[WEBAPP]['user']['id'];
                                        $activities=$con->myQuery("SELECT opportunities.opp_name,  DATE_FORMAT(action_date, '%M %d, %Y %h:%i %p'),notes FROM activities
                                        inner join opportunities on activities.opp_id=opportunities.id
                                        inner join users on activities.user_id=users.id
                                        where opportunities.is_deleted=0
                                        and activities.user_id='$uid'
                                        order by action_date desc
                                       ")->fetchAll(PDO::FETCH_ASSOC);
                                        if(!empty($activities)):

                                    ?>
                                    <div style="padding:10px;">
                                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th style='min-width:70px'>Client</th>
                                                <th class='date-td' style='min-width:80px'>Date</th>
                                                <th style='min-width:130px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                                    foreach ($activities as $activity):
                                                    
                                            ?>
                                            <tr>
                                                <?php
                                                    foreach ($activity as $key => $value):
                                                    if($key=='id'):
                                                    elseif($key=='opp_name'):
                                                ?>
                                                    <td>
                                                        <a href='opp_details.php?id=<?= $activity['id']?>'><?php echo htmlspecialchars($value)?></a>
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
                                    </div>
                                    <?php
                                        else:
                                            ?>

                                        <?php
                                            createAlert("No Results.");
                                        endif;
                                    ?>
                                </div>
                                </div>
                                
                                
                            
                                <div class='col-md-4'>
                                <div class='panel panel-primary'>
                                <div class='panel-heading text-left'>
                                <!-- <div class="row">
                                    <h4 class="col-xs-10">Birthdays</h4>
                                    <span class="fa fa-gift fa-3x col-md-2"></span>
                                    
                                </div> -->
                                <div class="row">
                                    <h2 class="col-xs-10">Birthdays</h2>
                                    <span class="fa fa-gift fa-3x col-md-2" style="padding-top: 15px;"></span>
                                    
                                </div>
                                </div>
                                <?php
                                    $uid=$_SESSION[WEBAPP]['user']['id'];
                                                $activities=$con->myQuery("SELECT DATE_FORMAT(dob,'%M %d') AS dob, CONCAT(lname, ', ', fname) As uname, organizations.org_name
                                                    FROM contacts 
                                                    inner join organizations on contacts.org_id=organizations.id
                                                    WHERE contacts.is_deleted=0 and 
                                                    week(dob) BETWEEN WEEK( CURDATE() )  AND  WEEK( DATE_ADD(CURDATE(), INTERVAL +21 DAY) ) 
                                                    Order by dob")->fetchAll(PDO::FETCH_ASSOC);
                                        if(!empty($activities)):

                                    ?>
                                    <table class='table table-bordered table-condensed '>
                                        <thead>
                                            <tr>    
                                                <th class='date-td'>Date</th>
                                                <th >Client</th>
                                                <th>Organization</th>
                                                
                                               <!-- <th>Email</th>
                                                <th>Mobile Phone</th> -->   
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                    foreach ($activities as $activity):
                                                    
                                            ?>
                                                    <tr>
                                                <?php
                                                    foreach ($activity as $key => $value):
                                                    if($key=='id'):
                                                    elseif($key=='opp_name'):
                                                ?>
                                                    <td>
                                                        <a href='opp_details.php?id=<?= $activity['id']?>'><?php echo htmlspecialchars($value)?></a>
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
                                    <?php
                                        else:
                                            
                                            birthdayAlert("No Results.");
                                        endif;
                                    ?>
                                        </div>
                                        
                                </div>
                                </div>
            </div>
            <?php
                else:
                $asset=$con->myQuery("SELECT CONCAT(first_name,' ',middle_name,' ',last_name) as name,username,email,contact_no,id FROM qry_users WHERE id=?",array($_SESSION[WEBAPP]['user']['id']))->fetch(PDO::FETCH_ASSOC);


            ?>
            
            <?php
                endif;
            ?>
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
	makeFoot();
?>