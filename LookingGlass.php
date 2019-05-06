<!DOCTYPE html>
<html lang="en">

<?php
  $title = "";
  $node_name = "";
?>

<head>
  <title><?php echo $title.' | '.$node_name ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar sticky-top navbar-dark bg-dark">
    <a class="navbar-brand" href="./"><?php echo $title.' ('.$node_name.')' ?></a>
  </nav>
  <div class="container" style="margin-top: 30px">
    <div class="row">
      <div class="card border-white col-sm-12">
        <div class="card-header">Network Information</div>
        <div class="card-body" style="margin-left: 10px; margin-top: 20px">
          <?php
             exec('curl -4 ip.sb; echo $?', $ip4);
             $ip4 = ($ip4[1]=="0") ? $ip4[0] : "Not supported";
             exec('curl -6 ip.sb; echo $?', $ip6);
             $ip6 = ($ip6[1]=="0") ? $ip6[0] : "Not supported";
             $caddr = $_SERVER['REMOTE_ADDR'];
             echo '<p class="text-secondary">Server IPv4: '.$ip4.'</p>';
             echo '<p class="text-secondary">Server IPv6: '.$ip6.'</p>';
             echo '<p class="text-secondary">Your IP Address: '.$caddr.'</p>';
             ?>
        </div>
      </div>
      <div class="card border-white col-sm-12">
        <div class="card-header">Network Test</div>
        <div class="card-body">
          <form class="form-group row" method="post">
            <div class="col-sm-6">
              <div class="input-group">
                <div class="input-group-prepend">
                  <select class="form-control" name="Proto">
                    <?php
                      echo '<option class="dropdown-item" '.(($ip4 == "Not supported")?"disabled":"").'>ping</option>';
                      echo '<option class="dropdown-item" '.(($ip4 == "Not supported")?"disabled":"").'>traceroute</option>';
                      echo '<option class="dropdown-item" '.(($ip6 == "Not supported")?"disabled":"").'>ping6</option>';
                      echo '<option class="dropdown-item" '.(($ip6 == "Not supported")?"disabled":"").'>traceroute6</option>';
                    ?>
                  </select>
                </div>
                <input type="text" name="addr" class="form-control" placeholder="Hostname or IP address">
              </div>
            </div>
            <div class="col-sm-3">
              <input class="btn btn-primary" type="submit" name="submit" value="Run Test" onclick="this.value='Please Wait..';"></input>
            </div>
          </form>
        </div>
      </div>
      <div class="card border-white col-sm-12">
        <div class="card-header">Result</div>
        <?php
         if($_SERVER['REQUEST_METHOD'] == 'POST') {
           $post_message = $_POST['addr'];
           $proto = $_POST['Proto'];
           $rtn = "Invalid hostname or IP address";
           if (preg_match('/[^&\s\/|<>]*[\.:]+[^&\s\/|<>]*/', $post_message, $match_addr)) {
             $ip = $match_addr[0];
             if ($post_message == $ip) {
               switch ($proto) {
                 case "ping":
                  $rtn = shell_exec('ping -c 5 '.$ip.'');
                  break;
                 case "ping6":
                  $rtn = shell_exec('ping -6 -c 5 '.$ip.'');
                  break;
                 case "traceroute":
                  $rtn = shell_exec('traceroute -m 30 '.$ip.'');
                  break;
                 case "traceroute6":
                  $rtn = shell_exec('traceroute -6 -m 30 '.$ip.'');
                  break;
               }
             }
           }
           echo '<div class="card-body">';
           echo '<pre>'.$rtn.'</pre>';
           echo '</div>';
         }
       ?>
      </div>
    </div>
  </div>
</body>
