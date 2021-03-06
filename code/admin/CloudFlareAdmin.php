<?php

class CloudFlareAdmin extends LeftAndMain implements PermissionProvider
{
    private static $url_segment = 'cloudflare';
    private static $url_rule    = '/$Action/$ID/$OtherID';
    private static $menu_title  = 'CloudFlare';
    private static $menu_icon   = 'cloudflare/assets/cloudflare.jpg';

    private static $allowed_actions = array(
        'purge_all',
        'purge_css',
        'purge_images',
        'purge_javascript',
    );

    /**
     * @todo Actually implement this
     * @return array
     */
    public function providePermissions()
    {
        return array(
            "PURGE_ALL"  => "Purge All Cache",
            "PURGE_CSS"  => "Purge CSS Cache",
            "PURGE_JS"   => "Purge JS Cache",
            "PURGE_PAGE" => "Purge Page Cache",
        );
    }

    /**
     * Include our CSS
     */
    public function init()
    {
        parent::init();

        Requirements::css('cloudflare/css/cloudflare.css');
    }

    /**
     * @return \SS_HTTPResponse|string
     */
    public function purge_all()
    {
        CloudFlare::inst()->purgeAll();

        return $this->redirect($this->Link('/'));
    }

    /**
     * @return \SS_HTTPResponse|string
     */
    public function purge_css()
    {
        CloudFlare::inst()->purgeCss();

        return $this->redirect($this->Link('/'));
    }

    /**
     * @return \SS_HTTPResponse|string
     */
    public function purge_javascript()
    {
        CloudFlare::inst()->purgeJavascript();

        return $this->redirect($this->Link('/'));
    }

    /**
     * @return \SS_HTTPResponse|string
     */
    public function purge_images()
    {
        CloudFlare::inst()->purgeImages();

        return $this->redirect($this->Link('/'));
    }

    /**
     * Template function to check for a response "alert" from CloudFlare functionality
     *
     * @return ArrayData
     */
    public function CFAlert()
    {
        $jar = CloudFlare::inst()->getSessionJar();

        $array = array(
            "Type" => (array_key_exists('CFType', $jar)) ? $jar['CFType'] : FALSE,
            "Message" => (array_key_exists('CFMessage', $jar)) ? $jar['CFMessage'] : FALSE,
        );

        return ArrayData::create($array);
    }

    /**
     * Destroys the alert message that is saved in session
     */
    public function DestroyCFAlert() {
        $jar = CloudFlare::inst()->getSessionJar();

        $jar['CFType'] = false;
        $jar['CFMessage'] = false;

        CloudFlare::inst()->setSessionJar($jar);
    }

    /**
     * Template function to determine if CloudFlare is ready (ergo has a zone ID)
     *
     * @return bool|null
     */
    public function isReady() {
        return CloudFlare::inst()->isReady();
    }

    /**
     * @todo Actually implement this
     * @return static
     */
    public function FormSingleUrlForm()
    {
        return CloudFlareSingleUrlForm::create($this, 'purge-single');
    }

    /**
     * Template function to display the detected zone ID
     *
     * @return string
     */
    public function ZoneID() {
        return CloudFlare::inst()->fetchZoneID() ?: "<strong class='cf-no-zone-id'>UNABLE TO DETECT</strong>";
    }


}