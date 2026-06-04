<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['luser']?:$_GET['e']);
$passwd=Format::input($_POST['lpasswd']?:$_GET['t']);

$content = Page::lookupByType('banner-client');

if ($content) {
    list($title, $body) = $ost->replaceTemplateVariables(
        array($content->getLocalName(), $content->getLocalBody()));
} else {
    $title = __('Sign In');
    $body = __('To better serve you, we encourage our clients to register for an account and verify the email address we have on record.');
}

?>
<div class="signin-page">
    <div class="signin-panel">
        <h1><?php echo Format::display($title); ?></h1>
        <p class="signin-intro"><?php echo Format::display($body); ?></p>

        <form action="login.php" method="post" id="clientLogin" class="signin-form">
            <?php csrf_token(); ?>
            <?php if ($errors['login']) { ?>
                <div class="signin-error"><strong><?php echo Format::htmlchars($errors['login']); ?></strong></div>
            <?php } ?>

            <div class="signin-fields">
                <div class="signin-field">
                    <label for="username"><?php echo __('Email or Username'); ?></label>
                    <input id="username" type="text" name="luser" size="30" value="<?php echo $email; ?>" class="nowarn">
                </div>
                <div class="signin-field">
                    <label for="passwd"><?php echo __('Password'); ?></label>
                    <input id="passwd" type="password" name="lpasswd" size="30" maxlength="128" value="<?php echo $passwd; ?>" class="nowarn">
                </div>
            </div>

            <div class="signin-actions">
                <input class="btn" type="submit" value="<?php echo __('Sign In'); ?>">
                <?php if ($suggest_pwreset) { ?>
                    <a class="signin-forgot" href="pwreset.php"><?php echo __('Forgot My Password'); ?></a>
                <?php } ?>
            </div>

            <?php
            $ext_bks = array();
            foreach (UserAuthenticationBackend::allRegistered() as $bk)
                if ($bk instanceof ExternalAuthentication)
                    $ext_bks[] = $bk;

            if (count($ext_bks) || ($cfg && $cfg->isClientRegistrationEnabled())) { ?>
            <div class="signin-secondary">
            <?php
            if (count($ext_bks)) {
                foreach ($ext_bks as $bk) { ?>
                <div class="external-auth"><?php $bk->renderExternalLink(); ?></div><?php
                }
            }
            if ($cfg && $cfg->isClientRegistrationEnabled()) {
                if (count($ext_bks)) echo '<hr class="signin-divider"/>';
                ?>
                <p class="signin-register">
                <?php echo __('Not yet registered?'); ?>
                <a href="account.php?do=create"><?php echo __('Create an account'); ?></a>
                </p>
            <?php } ?>
            </div>
            <?php } ?>

            <p class="signin-agent">
                <b><?php echo __("I'm an agent"); ?></b> —
                <a href="<?php echo ROOT_PATH; ?>scp/"><?php echo __('sign in here'); ?></a>
            </p>

            <?php
            if ($cfg->getClientRegistrationMode() != 'disabled'
                || !$cfg->isClientLoginRequired()) { ?>
            <p class="signin-panel-note">
            <?php echo sprintf(__('If this is your first time contacting us or you\'ve lost the ticket number, please %s open a new ticket %s'),
                '<a href="open.php">', '</a>'); ?>
            </p>
            <?php } ?>
        </form>
    </div>
</div>
