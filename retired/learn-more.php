<?php

// var
$yourEmail = "jj@tachyus.com"; // the email you wish to receive these mails
$yourWebsite = "Tachyus"; // name of website
$thanksPage = "thankyou.html"; // URL to 'thank you' page; leave empty to keep message on the same page 
$maxPoints = 4; // max points a person can hit before it refuses to submit - recommend 4
$requiredFields = "firstname,lastname,email"; // required fields, separate each field with a comma

// start
$error_msg = array();
$result = null;

$requiredFields = explode(",", $requiredFields);

function clean($data) {
  $data = trim(stripslashes(strip_tags($data)));
  return $data;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  foreach($requiredFields as $field) {
    trim($_POST[$field]);
    
    if (!isset($_POST[$field]) || empty($_POST[$field]) && array_pop($error_msg) != "Please fill in the required fields.\r\n")
      $error_msg[] = "Please fill in the required fields.";
  }

  if (!empty($_POST['firstname']) && !preg_match("/^[a-zA-Z-'\s]*$/", stripslashes($_POST['firstname'])))
    $error_msg[] = "Sorry, only letters are allowed for your first name.\r\n";
  if (!empty($_POST['lastname']) && !preg_match("/^[a-zA-Z-'\s]*$/", stripslashes($_POST['lastname'])))
    $error_msg[] = "Sorry, only letters are allowed for your last name.\r\n";
  if (!empty($_POST['email']) && !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', strtolower($_POST['email'])))
    $error_msg[] = "Invalid e-mail address.\r\n";
  if (!empty($_POST['phone']) && !preg_match("/^[0-9-'\s]*$/", stripslashes($_POST['phone'])))
    $error_msg[] = "Sorry, only numbers are allowed for your phone number.\r\n";
  
  if ($error_msg == NULL && $points <= $maxPoints) {
    $subject = "General Inquiries";
    
    $message = "You received this e-mail message through Tachyus website's contact page: \n\n";
    foreach ($_POST as $key => $val) {
      if (is_array($val)) {
        foreach ($val as $subval) {
          $message .= ucwords($key) . ": " . clean($subval) . "\r\n";
        }
      } else {
        $message .= ucwords($key) . ": " . clean($val) . "\r\n";
      }
    }
    $message .= "\r\n";
    $message .= 'IP: '.$_SERVER['REMOTE_ADDR']."\r\n";
    $message .= 'Browser: '.$_SERVER['HTTP_USER_AGENT']."\r\n";

    if (strstr($_SERVER['SERVER_SOFTWARE'], "Win")) {
      $headers   = "From: $yourWebsite\r\n"; 
    }
    $headers  .= "Reply-To: {$_POST['email']}\r\n";

    if (mail($yourEmail,$subject,$message,$headers)) {
      if (!empty($thanksPage)) {
        header("Location: $thanksPage");
        exit;
      } else {
        $result = 'Your message was successfully sent.';
        $disable = true;
      }
    } else {
      $error_msg[] = 'Your mail could not be sent this time. ['.$points.']';
    }
  }
}
function get_data($var) {
  if (isset($_POST[$var]))
    echo htmlspecialchars($_POST[$var]);
}
?>

<!DOCTYPE html[]>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Tachyus | Learn More</title>
    <link href="img/favicon.ico" rel="icon" type="image/ico" />
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <!-- Custom Google Web Font -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Add custom CSS here -->
    <link href="css/landing-page.css" rel="stylesheet" />
    <style type="text/css">
    p.error, p.success {
      margin-top: 10px;
    }
    p.error,
    span.error-star {
      color: #f15a24;
      font-size: 14px;
    }
    p.success {
      color: #4fa000;
    }
    .jobs-row .required-field {
      color: #777777;
      margin-top: 30px;
      margin-bottom: 10px;
      font-size: 14px;
    }
  </style>
  </head>
  <body>
    <nav id="main-nav" class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar" />
            <span class="icon-bar" />
            <span class="icon-bar" />
          </button>
          <a class="navbar-brand" href="/index.html">
            <img src="/img/tachyus.png" style="height:18px;" />
          </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-right navbar-ex1-collapse">
          <ul class="nav navbar-nav">
            <li>
              <a href="/product.html">Product</a>
            </li>
            <li>
              <a href="/whoweare.html">Who We Are</a>
            </li>
            <li>
              <a href="/joinus.html">Join Us</a>
            </li>
            <li>
              <a href="https://medium.com/@tachyuscorp" target="_blank">Blog</a>
            </li>
            <li>
              <a href="mailto:contact@tachyus.com">Contact Us</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- PAGE CONTENT -->
    <div class="content-section-product jobs">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <h1>Learn More About Our Solutions</h1>
            <hr class="product-hr" />
          </div>
        </div>
        <div class="row jobs-row">
          <div class="col-lg-4">
          </div>
          <div class="col-lg-4">
            <p>Please fill in the form and we’ll get back to you as soon as we can.</p>
            <p class="required-field"><span class="error-star">*</span> Indicates required field.</p>
            <?php
              if (!empty($error_msg)) {
                echo '<p class="error">'. implode("<br />", $error_msg) . "</p>";
              }
              if ($result != NULL) {
                echo '<p class="success">'. $result . "</p>";
              }
            ?>
            <form action="<?php echo basename(__FILE__); ?>" method="post">
              <div class="form-group">
                <label class="job-form-label" for="firstname">First Name <span class="error-star">*</span></label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?php get_data("firstname"); ?>">
              </div>
              <div class="form-group">
                <label class="job-form-label" for="lastname">Last Name <span class="error-star">*</span></label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php get_data("lastname"); ?>">
              </div>
              <div class="form-group">
                <label class="job-form-label" for="email">Email <span class="error-star">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="<?php get_data("email"); ?>">
              </div>
              <div class="form-group">
                <label class="job-form-label" for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?php get_data("phone"); ?>">
              </div>
              <div class="form-group">
                <label class="job-form-label" for="message">Message</label>
                <textarea class="form-control" rows="5" name="message"><?php get_data("message"); ?></textarea>
              </div>
              <button class="btn btn-warning btn-learn-more" type="submit">SUBMIT</button>
            </form>
          </div>
          <div class="col-lg-4">
          </div>
        </div>
      </div>
    </div>
    <footer id="main-footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-3" id="page-name-product">
            <ul>
              <li class="page-name">
                <a href="/product.html">Product</a>
              </li>
              <li>
                <a href="/product.html#measurement">Measurement</a>
              </li>
              <li>
                <a href="/product.html#alerts">Alerts</a>
              </li>
              <li>
                <a href="/product.html#management">Management</a>
              </li>
              <li>
                <a href="/product.html#analysis">Analysis</a>
              </li>
              <li>
                <a href="/product.html#reporting">Reporting</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-3" id="page-name-whoweare">
            <ul>
              <li class="page-name">
                <a href="/whoweare.html">Who We Are</a>
              </li>
              <li>
                <a href="/whoweare.html">Our Mission</a>
              </li>
              <li>
                <a href="/whoweare.html">Vision</a>
              </li>
              <li>
                <a href="/whoweare.html#leadership">Leadership</a>
              </li>
              <li>
                <a href="/whoweare.html#advisors">Advisors</a>
              </li>
              <li>
                <a href="/whoweare.html#investors">Investors</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-3" id="page-name-joinus">
            <ul>
              <li class="page-name">
                <a href="/joinus.html">Join Us</a>
              </li>
              <li>
                <a href="/joinus.html">Values</a>
              </li>
              <li>
                <a href="/joinus.html#benefits">Benefits</a>
              </li>
              <li>
                <a href="/joinus.html#tachyons">Tachyons</a>
              </li>
              <li>
                <a href="/joinus.html#jobs">Jobs</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-3" id="page-name-contact">
            <ul>
              <li class="page-name" id="contact-blog">
                <a href="https://medium.com/@tachyuscorp" target="_blank">Blog</a>
              </li>
              <li class="page-name" id="contact-contact">Contact</li>
              <li id="contact-email">
                <a href="mailto:contact@tachyus.com">contact@tachyus.com</a>
              </li>
              <li>
                <a href="https://twitter.com/tachyuscorp" target="_blank">Twitter</a>
              </li>
              <li>
                <a href="https://www.facebook.com/tachyus" target="_blank">Facebook</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js">
    </script>
    <script src="js/bootstrap.js">
    </script>
    <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-47789898-1']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

    </script>
  </body>
</html>