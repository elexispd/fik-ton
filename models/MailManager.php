<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


require_once(__DIR__ . "/../vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailManager{
    private $system_message=[];
    private $messageData_ob=null;
    function sendMail($from, $to,$subject,$message){
        $mail = new PHPMailer(true);
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'promisedeco24@gmail.com';                     //SMTP username
            $mail->Password   = 'sekbnjxjssrchgka';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($from, "Fikkon");
            $mail->addAddress($to);     //Add a recipient        
            //$mail->addReplyTo($reply_to);
            //$mail->addCC($cc);

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $this->template($message);
            
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return 1;
        } catch (Exception $e) {
            return "email failed to send ". $e->getMessage();
        }
        //return $this->system_message;
    }


    function template($message) {
       $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
       <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

       <head>
           <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
           <meta name="viewport" content="width=device-width">
           <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 



       </head>

       <body style="background-color: #f3faff; margin: 0; font-family: Montserrat; font-style:normal; font-weight: lighter;">
           <table class="body" style="width: 80%; margin: 0 auto;">
               <tr>
                   <td class="center" align="center" valign="top">
                       <center data-parsed="">
                           <table class="container text-center" >
                               <tbody>
                                   <tr>
                                       <td>
                                           <!-- This container adds the grey gap at the top of the email -->
                                           <table class="row grey">
                                               <tbody>
                                                   <tr>
                                                       <th class="small-12 large-12 columns first last">
                                                           <table>
                                                               <tr>
                                                                   <th>
                                                                       &#xA0;
                                                                   </th>
                                                                   <th class="expander"></th>
                                                               </tr>
                                                           </table>
                                                       </th>
                                                   </tr>
                                               </tbody>
                                           </table>
                                       </td>
                                   </tr>
                               </tbody>
                           </table>
                           
                           <table class="container" >
                               <tbody>
                                   <tr style="background-color:#f3faff;">
                                   <td><center data-parsed="">
                                                                           <a href="https://gaijinmall.com" align="center"
                                                                               class="text-center">
                                                                               <img src="https://gaijinmall.com/views/assets/images/logo-sm.png"
                                                                                   class="swu-logo" style="margin-bottom: 15px;">
                                                                           </a>
                                                                       </center></td>
                                   </tr>
                                   <tr style="width: 100%; background-color: #ffffff;">
                                       
                                       <td>
                                           <!-- This container is the main email content -->
                                           
                                           <table class="row">
                                               <tbody>
                                                   <tr>
                                                       <!-- Logo -->
                                                       <th class="small-12 large-12 columns first last">
                                                           <table>
                                                               <tr>
                                                                   <th>
                                                                       
                                                                   </th>
                                                                   <th class="expander"></th>
                                                               </tr>
                                                           </table>
                                                       </th>
                                                   </tr>
                                               </tbody>
                                           </table>
                                           <table class="row masthea" style="width: 100%; ">
                                               <tbody style="width: 100%; ">
                                                   <tr >
                                                       <!-- Masthead -->
                                                       <th class="small-12 large-12 columns first last">
                                                           <table style="width: 100%;">
                                                               <tr style="margin: 0 auto; " >
                                                                   <th>
                                                                       <center data-parsed="">
                                                                           <img src="https://gaijinmall.com/views/assets/images/welcome_verify.png"
                                                                               valign="bottom" style="margin-top: 15px; object-fit:contain; width: 50%;" 
                                                                               class="text-center">
                                                                       </center>
                                                                   </th>
                                                                   <th class="expander"></th>
                                                               </tr>
                                                           </table>
                                                       </th>
                                                   </tr>
                                               </tbody>
                                           </table>
                                           <table class="row">
                                               <tbody>
                                                   <tr>
                                                       <!--This container adds the gap between masthead and digest content -->
                                                       <th class="small-12 large-12 columns first last">
                                                           <table>
                                                               <tr>
                                                                   <th>
                                                                       &#xA0;
                                                                   </th>
                                                                   <th class="expander"></th>
                                                               </tr>
                                                           </table>
                                                       </th>
                                                   </tr>
                                               </tbody>
                                           </table>
                                           <table class="row" align="center" style="text-align:center;">
                                               <tbody>
                                                   <tr>
                                                       <!-- main Email content -->
                                                       <th class="small-12 large-12 columns first last" style="padding:0px 30px 0px 30px;">
                                                           <table>
                                                               <tr>
                                                                   <th>
                                                                      
                                                                       <p style="text-align: center;">'.$message.'</p>
                                                                       <br>
                                                                       <div class="button">
                                                                      
                                                                       </div>
                                                                   </th>
                                                                   <th class="expander"></th>
                                                               </tr>
                                                           </table>
                                                       </th>
                                                   </tr>
                                               </tbody>
                                           </table>
                                           <table class="row">
                                               <tbody>
                                                   <tr>
                                                       <!-- This container adds whitespace gap at the bottom of main content  -->
                                                       <th class="small-12 large-12 columns first last">
                                                           <table>
                                                               <tr>
                                                                   <th>
                                                                       &#xA0;
                                                                   </th>
                                                                   <th class="expander"></th>
                                                               </tr>
                                                           </table>
                                                       </th>
                                                   </tr>
                                               </tbody>
                                           </table>
                                       </td>
                                   </tr>
                               </tbody>
                           </table> <!-- end main email content -->

                           <table class="container text-center" align="center" style="text-align:center; width:100%;">
                               <tbody>
                                   <tr>
                                       <td>
                                           <!-- footer -->
                                           <table class="row grey" style="width:100%;">
                                               <tbody style="width:100%;">
                                                   <tr >
                                                       <th class="small-12 large-12 columns first last" >
                                                           <table style="text-align: center; width:100%;">
                                                               <tr>
                                                                   <th>
                                                                       <p class="text-center footercopy" style="font-size:smaller;">&#xA9; Copyright '. date("Y"). '
                                                                           Fikkon. All Rights Reserved.</p>
                                                                   </th>
                                                                   <th class="expander"></th>
                                                               </tr>
                                                           </table>
                                                       </th>
                                                   </tr>
                                               </tbody>
                                           </table>
                                       </td>
                                   </tr>
                               </tbody>
                           </table>


                       </center>
                   </td>
               </tr>
           </table>
       </body>


        </html>';

        return $message;

    }

   
}


// $output = new MailManager();

// $a = "<h5 style='font-size: 200%;' >Hello, </h5> <p style='font-size: 150%;'>Your Verification token is </p> <h4 style='font-size: 250%;'>123456</h4>";

// $result = $output->sendMail("promisedeco24@gmail.com", "promisedeco24@gmail.com", "Testing Notoification", $a );

// echo $result;
