<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['lemail']?$_POST['lemail']:$_GET['e']);
$ticketid=Format::input($_POST['lticket']?$_POST['lticket']:$_GET['t']);

if ($cfg->isClientEmailVerificationRequired())
    $button = __("Email Access Link");
else
    $button = __("View Ticket");
?>
<div class="signin-page">
    <div class="signin-panel">
        <h1><?php echo __('Check Ticket Status'); ?></h1>
        <p class="signin-intro"><?php
        echo __('Please provide your email address and a ticket number.');
        if ($cfg->isClientEmailVerificationRequired())
            echo ' '.__('An access link will be emailed to you.');
        else
            echo ' '.__('This will sign you in to view your ticket.');
        ?></p>

        <form action="login.php" method="post" id="clientLogin" class="signin-form">
            <?php csrf_token(); ?>
            <?php if ($errors['login']) { ?>
                <div class="signin-error"><strong><?php echo Format::htmlchars($errors['login']); ?></strong></div>
            <?php } ?>

            <div class="signin-fields">
                <div class="signin-field">
                    <label for="email"><?php echo __('Email Address'); ?></label>
                    <input id="email" type="text" name="lemail" size="30"
                        placeholder="<?php echo __('e.g. john.doe@osticket.com'); ?>"
                        value="<?php echo $email; ?>" class="nowarn">
                </div>
                <div class="signin-field">
                    <label for="ticketno"><?php echo __('Ticket Number'); ?></label>
                    <input id="ticketno" type="text" name="lticket" size="30"
                        placeholder="<?php echo __('e.g. 051243'); ?>"
                        value="<?php echo $ticketid; ?>" class="nowarn">
                </div>
            </div>

            <div class="signin-actions">
                <input class="btn" type="submit" value="<?php echo $button; ?>">
            </div>

            <?php if ($cfg && $cfg->getClientRegistrationMode() !== 'disabled') { ?>
            <div class="signin-secondary">
                <p>
                <?php echo __('Have an account with us?'); ?>
                <a href="login.php"><?php echo __('Sign In'); ?></a>
                <?php if ($cfg->isClientRegistrationEnabled()) {
                    echo sprintf(__('or %s register for an account %s to access all your tickets.'),
                        '<a href="account.php?do=create">','</a>');
                } ?>
                </p>
            </div>
            <?php } ?>

            <?php
            if ($cfg->getClientRegistrationMode() != 'disabled'
                || !$cfg->isClientLoginRequired()) { ?>
            <p class="signin-panel-note">
            <?php echo sprintf(
                __("If this is your first time contacting us or you've lost the ticket number, please %s open a new ticket %s"),
                '<a href="open.php">','</a>'); ?>
            </p>
            <?php } ?>
        </form>
    </div>
</div>
