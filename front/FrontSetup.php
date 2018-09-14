<?php

namespace SSM\Front;

use SSM\Front\Front;
use SSM\Includes\Helpers as SSMH;

class FrontSetup extends Front
{

    /**
     * Show current year as a shortcode
	 */
    public function addYearShortcode()
    {
        add_shortcode('year', array( $this, 'addYearShortcodeCB' ) );
    }
    
    /**
     * addYearShortcode() callback
	 */
    public function addYearShortcodeCB()
    {
        
        $year = date('Y');
        return $year;
    
    }

    /**
     * Set Favicon
     */
    public function setFavicon()
    {

        if ( $favicon = SSMH::getField('favicon', 'options') ) {
            echo '<link rel="shortcut icon" href="' . $favicon['url'] . '" />';
        }

    }

    /**
     * Dynamically Adds the Facebook Pixel
     */
    public function doFacebookPixel()
    {

        if ( $fb_id = SSMH::getField('facebook_account_id', 'options') ) {
            
            global $post;
            
            $fb_standard_event = '';
            $value = '';
            
            if ( SSMH::getField('facebook_standard_event') != NULL && SSMH::getField('facebook_standard_event') == 'purchase' ) {
            
                if ( $value ) {
                    $fb_standard_event = 'fbq("track", "Purchase", {"value": "' . $value . '" , "currency" : "USD"});';
                } else {
                    $fb_standard_event = 'fbq("track", "Purchase");';
                }
        
            } elseif ( SSMH::getField('facebook_standard_event') != NULL ) {
                $fb_standard_event = SSMH::getField('facebook_standard_event');
            }
            
            ?>

            <!-- Facebook Pixel Code -->
            <script>
                !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
                n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
                document,'script','https://connect.facebook.net/en_US/fbevents.js');
                fbq("init", "<?php echo $fb_id; ?>");
                fbq("track", "PageView");

                <?php echo $fb_standard_event; ?>

            </script>

            <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $fb_id; ?>&ev=PageView&noscript=1"/></noscript>
            <!-- End Facebook Pixel Code -->
        <?php } ?>
    <?php }

    /**
     * Setup Google Tag Manager
     */
    public function setupGoogleTagManager()
    {
        ?>

        <?php if ( $gtm = SSMH::getField('google_tag_manager_id', 'options') ) { ?>

        <!-- Begin Google Tag Manager -->
        <noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $gtm; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php echo $gtm; ?>');
        </script>
        <!-- End Google Tag Manager -->

        <?php } ?>

    <?php }


    /**
     * Setup Google Tag Manager
     */
    public function setupGoogleSiteVerification()
    {
        ?>

        <?php if ( $sv = SSMH::getField('google_site_verification_id', 'options') ) { ?>

        <!-- Begin Google Search Console Verification -->
        <meta name="google-site-verification" content="<?php echo $sv; ?>" />
        <!-- End Begin Google Search Console Verification -->

        <?php } ?>

    <?php }

    /**
     * Custom Head Scripts
     */
    public function CustomHeadScripts()
    {

        $custom_scripts = SSMH::getField('custom_tracking_scripts', 'options');
        
        if ( $custom_scripts ) {
            
            foreach ( $custom_scripts as $script ) {
            
                if ( $script['location'] == 'header' && $script['code'] != NULL ) {
                    echo $script['code'];
                }
            }
        }
    }

    /**
     * Custom Footer Scripts
     */
    public function customFooterScripts()
    {
        
        $custom_scripts = SSMH::getField('custom_tracking_scripts', 'options');
        
        if ( $custom_scripts ) {
           
            foreach ( $custom_scripts as $script ) {
            
                if ( $script['location'] == 'footer' && $script['code'] != NULL ) {
                    echo $script['code'];
                }
           
            }
        }
    }

    /**
     * Force Gravity Forms to init scripts in the footer and ensure that the DOM is loaded before scripts are executed
	 */
    public function footerScriptsInit()
    {
        return true;
    }

    /**
     * Wrap Gform - CData open
	 */
    public function wrapGformCdataOpen( $content = '' )
    {
        
        if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
            return $content;
        }
        
        $content = 'document.addEventListener( "DOMContentLoaded", function() { ';
        
        return $content;
    
    }

    /**
     * Wrap Gform - CData close
	 */
    public function wrapGformCdataClose( $content = '' )
    {
   
        if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
            return $content;
        }
    
        $content = ' }, false );';
    
        return $content;
    
    }

}