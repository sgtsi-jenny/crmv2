<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    $tab="7";
    if(!empty($_GET['tab']) && !is_numeric($_GET['tab'])){
        redirect("org_opp.php".(!empty($employee)?'?id='.$employee['id']:''));
        die;
    }
    else{
        if(!empty($_GET['tab'])){
            if($_GET['tab'] >0 && $_GET['tab']<=9){
                $tab=$_GET['tab'];
            }
            else{
                #invalid TAB
                redirect("customers.php".(!empty($employee)?'?id='.$employee['id']:''));
            }
        }
    }
    if(empty($_GET['id'])){
        //Modal("No Account Selected");
        redirect("customers.php");
        die();
    }
    else{
        $opp=$con->myQuery("SELECT id,opp_name,org_id,org_name,cname,opp_type,users,sales_stage,forecast_amount,amount,tprice,description,product_set,date_created,date_modified FROM vw_opp WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($opp)){
            Modal("Invalid Opportunities Selected");
            redirect("opportunities.php");
            die;
        }
    }

    
    $data="";
   $data=$con->myQuery("SELECT f.title,f.subject,f.description,f.document,f.date_uploaded,f.date_modified,CONCAT(users.last_name, ', ', users.first_name) AS uname, f.user_id, f.document,f.amount, f.id FROM files f INNER JOIN opportunities ON f.opp_id=opportunities.id INNER JOIN users ON f.user_id=users.id WHERE f.is_deleted=0 AND f.opp_id=? AND f.cat_id=3 AND f.user_id=?",array($opp['id'],$_SESSION[WEBAPP]['user']['id']))->fetchAll(PDO::FETCH_ASSOC);
          

    if(!empty($_GET['invoice_id'])){
        // var_dump($_GET['po_id']);
        // die;
        $invoices=$con->myQuery("SELECT f.title,f.subject,f.description,f.document,f.date_uploaded,f.date_modified,CONCAT(users.last_name, ', ', users.first_name) AS uname, f.user_id, f.document,f.amount, f.id FROM files f INNER JOIN opportunities ON f.opp_id=opportunities.id INNER JOIN users ON f.user_id=users.id WHERE f.is_deleted=0 AND f.cat_id=3 AND f.id=? LIMIT 1",array($_GET['invoice_id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($invoices)){
            Modal("Invalid invoice selected");
            redirect("opp_invoices.php");
            die;
        }

    } 


    //$quote=$con->myQuery("SELECT id,title,opportunity_name,document,description,user_name,date_uploaded FROM quotes WHERE id NOT IN (SELECT quote_id FROM opp_quotes WHERE opp_id=? AND is_deleted=0)AND is_deleted=0",array($opp['id']))->fetchAll(PDO::FETCH_ASSOC);
  
    $opps=$con->myQuery("SELECT id,opp_name FROM opportunities where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);

    makeHead("Opportunities");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
         <section class="content-header">
                                      <h1>
                                      <img src="uploads/summary_Oppurtunities.png" width="50" height="50" title="Organization" alt="Organization" />
                                      <?php echo htmlspecialchars($opp['opp_name']) ?>
                                      </h1>
        </section>
                               
         <section class="content-header">
        <br/>
          <a href='opportunities.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> List of All Opportunities</a>
          <a href='org_opp.php?id=<?php echo $opp['org_id'] ?>' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back to My Opportunity</a>
          </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <!-- <?php
                        $no_employee_msg=' Personal Information must be saved.';
                    ?> -->
                    <li ><a href="opp_details.php?id=<?php echo $_GET['id'] ?>" >Opportunity Details</a>
                    </li>
                    <li> <a href="opp_events.php?id=<?php echo $_GET['id'] ?>">Activities</a>
                    </li>
                    <li> <a href="opp_contact_persons.php?id=<?php echo $_GET['id'] ?>">Contact Persons</a>
                    </li>
                    <li > <a href="opp_prods.php?id=<?php echo $_GET['id'] ?>">Products</a>
                    </li>
                    <li> <a href="opp_quotes.php?id=<?php echo $_GET['id'] ?>">Quotations</a>
                    </li>
                    <li> <a href="opp_purchases.php?id=<?php echo $_GET['id'] ?>">Purchase Orders</a>
                    </li>
                    <li <?php echo $tab=="7"?'class="active"':''?>> <a href="">Invoices</a>
                    </li>
                    <li> <a href="opp_others.php?id=<?php echo $_GET['id'] ?>">Others</a>
                    </li>
                    
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                         <div class='panel-body'>
                                    <div class='col-md-12 text-right'>
                                        <div class='col-md-12 text-right'>
                                        <button class='btn btn-brand' data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">Add New Invoice File <span class='fa fa-plus'></span> </button>
                                        </div>                                
                                    </div> 
                                </div>
                                <?php
                                Alert();
                                ?>

                <div id='collapseForm' class='collapse'>
                              <form class='form-horizontal' action='save_opp_invoice.php' onsubmit="return validatePost(this)" method="POST" >
                                 <!-- <input type='hidden' name='quote_id' value='<?php echo !empty($data)?$data['id']:""?>'> -->

                                 <input type='hidden' name='opp_invoice' value='<?php echo !empty($invoices)?$invoices['id']:""?>'>
                                 <input type='hidden' name='invoice_id' value='<?php echo $opp['id']?>'>

                                 <div class='form-group'>
                                    <label for="" class="col-md-4 control-label">File *</label>
                                    <div class="col-md-1">
                                      <input type='file' name='file' class="filestyle" data-classButton="btn btn-primary" data-input="false" data-classIcon="icon-plus" data-buttonText=" &nbsp;Select File">
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label for="" class="col-md-4 control-label"> Title *</label>
                                    <div class="col-sm-5">
                                        <a download='<?php echo $invoices['document'];?>' href='uploads/Files/<?php echo $invoices['document'] ?>'>
                                            <?php echo !empty($invoices)?$invoices['document']:"" ?>
                                        </a>
                                        <input type='text' class='form-control' name='title' placeholder='Enter Title' value='<?php echo !empty($invoices)?$invoices['title']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label for="" class="col-md-4 control-label"> Amount *</label>
                                    <div class='col-sm-5'>
                                        <input type='text' class='form-control' placeholder='0.00' name='amount' value='<?php echo !empty($invoices)?$invoices['amount']:"0" ?>' required>
                                    </div>
                                </div>

                               <div class='form-group'>
                                    <label for="" class="col-md-4 control-label"> Description</label>
                                    <div class='col-sm-5'>
                                        <textarea class='form-control' name='description' placeholder="Write a short description."><?php echo !empty($invoices)?$invoices['description']:"" ?></textarea>
                                    </div>
                                </div>
                                
                                  <div class="form-group">
                                    <div class="col-sm-10 col-md-offset-2 text-center">
                                      <button type='submit' class='btn btn-brand'>Save </button>
                                      <a href='opp_invoices.php?id=<?php echo $_GET['id'] ?>' class='btn btn-default'>Cancel</a>
                                    </div>
                                  </div>
                              </form>
                            </div>
                            <br/>

                   <h2>List of Invoice Files</h2>
                   <br>
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Document Name</th>
                                                <th class='text-center'>Description</th>
                                                <th class='text-right'>Amount</th>
                                                <th class='text-center'>Date Uploaded</th>
                                                <th class='text-center'>Date Modified</th>
                                                <th class='text-center' style='min-width:30px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                              foreach($data as $row):
                                            ?>
                                                <tr>
                                                    <td class='text-center'><?php echo htmlspecialchars($row['title']) ?></td>
                                                    <td class='text-center'><?php echo htmlspecialchars($row['description']) ?></td>
                                                    <td class='text-right'><?php echo htmlspecialchars(number_format($row['amount'],2)) ?></td>
                                                    <td class='text-center'><?php echo htmlspecialchars($row['date_uploaded']) ?></td>
                                                    <td class='text-center'><?php echo htmlspecialchars($row['date_modified']) ?></td>
                                                    <!-- <td><?php echo htmlspecialchars($row['uname']) ?></td> -->
                                                    
                                                    <?php
                                                            foreach ($row as $key => $value):
                                                            if($key=='id'):
                                                    ?>
                                                    <td align="center">
                                                        <a href='opp_invoices.php?id=<?php echo $opp['id']?>&invoice_id=<?php echo $row['id']?>' class='btn btn-sm btn-brand'><span class='fa fa-pencil'></span></a>
                                                        
                                                        <a href='delete.php?id=<?php echo $row['id']?>&t=oinvoice&opp_id=<?php echo $opp['id']?>' class='btn btn-sm btn-danger' onclick='return confirm("This invoice will be deleted.")'><span class='fa fa-trash'></span></a>

                                                        <a download='<?php echo $row['document'];?>' href='uploads/Files/<?php echo $row['document'] ?>'  class='btn btn-sm btn-default'><span class='fa fa-download'></span>
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
               // dom: 'Bfrtip',
               //      buttons: [
               //          {
               //              extend:"excel",
               //              text:"<span class='fa fa-download'></span> Download as Excel File "
               //          }
               //          ]
        });
      });
</script>
<script type="text/javascript">
    function validatePost(post_form){
        console.log();
        var str_error="";
        $.each($(post_form).serializeArray(),function(index,field){
            console.log(field);
            if(field.value==""){
            
                switch(field.name){
                    case "prod_name":
                        str_error+="Please select product name. \n";
                        break;
                    case "prod_price":
                        str_error+="Please provide product price. \n";
                        break;
                }
                
            }

        });
        if(str_error!=""){
            alert("You have the following errors: \n" + str_error );
            return false;
        }
        else{
            return true
        }
    }

    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY": true,
                "scrollX": true
        });
    });
    
</script>
<?php 
  if(!empty($invoices)):
    // var_dump("test");
    // die;
?>
<script type="text/javascript">
  $(function(){
    $('#collapseForm').collapse({
      toggle: true
    })    
  });
</script>
<?php
  endif;
?>
<?php
    Modal();
    makeFoot();
?>