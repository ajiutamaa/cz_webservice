<?php
        echo "masuk sini";
        try{
        require_once 'swift_mail_lib/lib/swift_required.php';
        // Create the SMTP configuration
        $transport = Swift_SmtpTransport::newInstance('crimezone.besaba.com',25);
          $transport->setUsername('admin@crimezone.besaba.com');
          $transport->setPassword('pplc01');

        // Create the message
        $message = Swift_Message::newInstance();
        $message->setTo(array(
           "prasetya.ajie94@gmail.com"

        ));

        $message->setSubject("This is a test message ");
        $message->setBody("This is an automatically genrated message kindly do not reply.");
        $message->setFrom("admin@crimezone.besaba.com", "pplc01");



        // Send the email
        $mailer = Swift_Mailer::newInstance($transport);
        $mailer->send($message, $failedRecipients);

        // Show failed recipients
        print_r($failedRecipients);
        echo "berhasil";
        }
        catch(Exception $e){
             echo $e->getMessage();
        }
?>	