<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/logos/newlogo.png">
    <title>CATS
    </title>
    <link rel="stylesheet" href="cadminexpand.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

</head>
    <body>
          <div class="header">
            <div class="left-section">
                <a href="cadminhome.php" id="logo-link">
                    <img src="images/logos/newwhitetranspa.png" alt=""> 
                </a>
            </div>
            
            <div class="right-section">
                <a href="cadminhome.php" class="dashh">Dashboard</a>
                <a href="cadminorg.php" class="orgs">Organization</a>
                <a href="cadminaudit.php" class="audtrail" >Audit Trail</a>
                <a href="ceventlist.php" class="ev" >Event List</a> <!-- edited -->
                
               
                <div class="profile-link">
                  <a href="cadminprofile.php" target="_blank">
                    <img class="profile-picture" src="<?php echo $user["profile_picture"]; ?>" alt=""> <!-- <?php echo $profile_picture; ?> -->
                  </a>
                </div>
                <div class="user-name">
                  <a href="cadminprofile.php" target="_blank"> <?php echo $user["username"]; ?></a> <!-- <?php echo $post['username']; ?> -->
              </div>
      
              <a class="logout" href="logout.php">Log out</a>
            </div>
          </div>
          <div class="post-container">
               
               <div>
                   <div>
                     <div class="user-profile">
                       <div class="poster-profile-picture">
                           <img class="poster-profile-picture" src="<?php echo $post['profile_picture']; ?>" alt="">
                        
                       </div>
                       <div class="poster-info">
                         <div class="profile-name"><?php echo $post['username']; ?></div>
                         <div class="date-posted"><?php echo $post['date_posted']; ?></div>
                       </div>
                     </div>
                   </div>
             <div class="user-post-description">
               <div class="post-what">  Event:        <?php echo $post['event']; ?></div>
               <div class="post-where"> Date:          <?php echo $post['date']; ?></div>
               <div class="post-when">  Location:      <?php echo $post['location']; ?></div>
               <div class="qr-image"><img src="<?php echo $post['qr']; ?>">  </div> 
                 <div class="mga-buttons">
                   <a href="cadminedit.php?id=<?php echo $post['post_id'];?>">Edit  </a>
                   <a href="cadmindelete.php?id=<?php echo $post['post_id'];?>" >Delete</a>
                 </div>
                
                  
               
             </div> 
           </div>
       
         </div>
             
       </body>
   </html>