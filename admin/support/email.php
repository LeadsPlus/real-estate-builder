<?php

/**
 * Admin interface: Support tab
 * Email content with "support request" which is sent to admin
 */

?>
<html>
    <head></head>
    <body>
        <p>
            Date: <?php echo date('m/d/Y H:i:s'); ?><br />
            Request type: <?php echo htmlspecialchars($_REQUEST['type']); ?><br />
            URL: <a href="<?php echo htmlspecialchars($_REQUEST['url']); ?>"><?php echo htmlspecialchars($_REQUEST['url']); ?></a><br />
            Name: <?php echo htmlspecialchars($_REQUEST['name']); ?><br />
            E-Mail: <a href="mailto:<?php echo htmlspecialchars($_REQUEST['email']); ?>"><?php echo htmlspecialchars($_REQUEST['email']); ?></a><br />
            Twitter: <a href="http://twitter.com/<?php echo htmlspecialchars($_REQUEST['twitter']); ?>"><?php echo htmlspecialchars($_REQUEST['twitter']); ?></a><br />
            Phone: <?php echo htmlspecialchars($_REQUEST['phone']); ?><br />
            <?php if ($_REQUEST['forum_url']): ?>
            Forum Topic URL: <a href="<?php echo htmlspecialchars($_REQUEST['forum_url']); ?>"><?php echo htmlspecialchars($_REQUEST['forum_url']); ?></a><br />
            <?php endif; ?>
            <?php if (isset($_REQUEST['request_data_url'])): ?>
            Request data: <a href="<?php echo htmlspecialchars($_REQUEST['request_data_url']); ?>"><?php echo htmlspecialchars($request_data_url); ?></a><br />
            <?php endif; ?>
            Subject: <?php echo htmlspecialchars($_REQUEST['subject']); ?>
        </p>

        <p>
            <?php echo nl2br(htmlspecialchars($_REQUEST['description'])); ?>
        </p>

        <hr />

        <font size="-1" color="#ccc">
            E-mail sent from IP: <?php echo htmlspecialchars($_SERVER['REMOTE_ADDR']); ?><br />
            User Agent: <?php echo htmlspecialchars($_SERVER['HTTP_USER_AGENT']); ?>
        </font>
    </body>
</html>
