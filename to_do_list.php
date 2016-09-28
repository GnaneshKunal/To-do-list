<?php
$mysqli=mysqli_connect("localhost","root","mysql","to_do_list");
if(!$mysqli)
   die("Connection error");
@$ses=$_GET['task'];
?>
   <!DOCTYPE html>
   <html>

   <head>
      <title>To-Do list</title>
      <style type="text/css">
         th,
         td {
            padding: 15px;
            text-align: left;
         }
      </style>
   </head>

   <body>
      <h2>To-Do List</h2>
      <h3>Add new task</h3>
      <?php
      if($ses!='add_task'){
         ?><a href="to_do_list.php?task=add_task">Add Task ?</a>
         <?php
      }if($ses!='view_task'){
         ?><a href="to_do_list.php?task=view_task">View Tasks</a>
            <?php
      }
      ?>
               <br />
               <?php
         if($ses=='add_task'){
            if(!isset($_POST['submit'])){
            ?>
                  <a href="to_do_list.php">Home</a><br /><br />
                  <fieldset>
                     <legend>To-Do List</legend>
                     <form method="post">
                        Enter Description: <input type="text" name="to_do" /><br /><br /> Enter Due Date(dd/mm/yyyy):<br />
                        <input type="text" size="2" name="dd" />
                        <input type="text" size="2" name="mm" />
                        <input type="text" size="2" name="yyyy" />
                        <br />
                        <br /> Priority:
                        <br />
                        <select name="priority">
                    <option value="low">Low</option>
                     <option value="med">Medium</option>
                     <option value="high">High</option>
                  </select><br /><br />
                        <input type="submit" name="submit" />
                     </form>
                  </fieldset>

                  <?php
         }else{
            try{
               $to_do=$_POST['to_do'];
               $dd=$_POST['dd'];
               $mm=$_POST['mm'];
               $yyyy=$_POST['yyyy'];
               $priority=$_POST['priority'];

               if(empty($to_do) || empty($dd) || empty($mm) || empty($yyyy) || strlen($dd)!= 2 || strlen($mm)!=2 || strlen($yyyy)!=4 || empty($priority)){
                  throw new exception("Please Fill the form correctly");
               }
               $date=checkdate($mm,$dd,$yyyy) ? mktime(0,0,0,$mm,$dd,$yyyy) : die("Invalid Date");
               $sql="Insert into tasks(name,due,priority) Values ('{$to_do}','{$date}','{$priority}')";
               if(!$mysqli->query($sql)){
                  throw new exception("Can't Add");
               }else{
                 echo "Task Added Successfully";
               }
            }catch(Exception $e){
               echo $e->getMessage();
            }
            ?>
                     <?php
         }
      }
      if($ses=='view_task'){
         ?>
                        <a href="to_do_list.php">Home</a>
                        <?php
         $sql="select * from tasks";
         if($result=$mysqli->query($sql)){
            if($result->num_rows>0){
               ?>
                           <br /><br />
                           <table border="2">
                              <?php
               while($row=$result->fetch_assoc()){
                  ?>
                                 <tr>
                                    <td>
                                       <?php echo $row['name']; ?>
                                    </td>
                                    <td>
                                       <?php echo date('m/d/Y',$row['due']); ?>
                                    </td>
                                    <td>
                                       <?php echo $row['priority']; ?>
                                    </td>
                                    <td><a href="to_do_list.php?task=delete&id=<?php echo $row['id']; ?>">Mark as Done</a></td>
                                    <td><a href="to_do_list.php?task=edit_post&id=<?php echo $row['id']; ?>">Edit</a></td>
                                 </tr>
                                 <?php
               }
               ?>
                           </table>
                           <?php
            }else{
               die("No tasks");
            }
         }else{
            die("Can't Run the query");
         }
         ?>
                              <?php
      }
      if($ses=='delete'){
         $id=$_GET['id'];
         $sql="delete from tasks where id={$id}";
         if(@$result=$mysqli->query($sql)){
            echo "<div>Done </div>";
         }else{
            echo "Something went wrong";
         }
      }
      if($ses=='edit_post'){
         ?><br /><br />
                                 <?php
         $id=$_GET['id'];
         $sql="select * from tasks where id={$id}";
         if($result=$mysqli->query($sql)){
            if($row=$result->fetch_assoc()){
               if(!isset($_POST['update'])){
               ?>
                                    <fieldset>
                                       <legend>Edit-Task</legend>
                                       <form method="post">
                                          Enter Description: <input type="text" name="to_do" value="<?php echo $row['name'] ?>" /><br /><br /> Enter Due Date(dd/mm/yyyy):<br />
                                          <input type="text" size="2" name="dd" value="<?php echo date('d',$row['due'])?>" />
                                          <input type="text" size="2" name="mm" value="<?php echo date('m',$row['due'])?>" />
                                          <input type="text" size="2" name="yyyy" value="<?php echo date('Y',$row['due']) ?>" />
                                          <input type="hidden" value="<?php echo $row['id'] ?>" name="id" />
                                          <br />
                                          <br /> Priority:
                                          <br />
                                          <select name="priority">
                    <option <?php echo($row['priority']=='low') ? "selected" : ''; ?>  value="low">Low</option>
                     <option <?php echo ($row['priority']=='med')?"selected":""; ?> value="med">Medium</option>
                     <option <?php echo($row['priority']=='high') ? "selected" : ''; ?> value="high">High</option>
                  </select><br /><br />
                                          <input type="submit" id="update" name="update" />
                                       </form>
                                    </fieldset>

                                    <?php
               }else{
                  try{
                     $to_do=$_POST['to_do'];
                     $dd=$_POST['dd'];
                     $mm=$_POST['mm'];
                     $yyyy=$_POST['yyyy'];
                     $priority=$_POST['priority'];
                     $id=$_POST['id'];
                     if(empty($to_do) || empty($dd) || empty($mm) || empty($yyyy) || strlen($dd)!= 2 || strlen($mm)!=2 || strlen($yyyy)!=4 || empty($priority)){
                        throw new exception("Please Fill the form correctly");
                     }
                     $date=checkdate($mm,$dd,$yyyy) ? mktime(0,0,0,$mm,$dd,$yyyy) : die("Invalid Date");
                     $sql="update tasks set name='{$to_do}',due='{$date}',priority='{$priority}' where id={$id}";
                     if(!$mysqli->query($sql)){
                        throw new exception("Can't update");
                     }else{
                       echo "Task Updated Successfully";
                     }
                  }catch(Exception $e){
                     echo $e->getMessage();
                  }
               }
            }else{
               echo "Can't fetch array";
            }
         }else{
            echo "Can't execute Query";
         }
      }
      $mysqli->close();
      ?>
   </body>

   </html>