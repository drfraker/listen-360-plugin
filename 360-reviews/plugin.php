<?php
/*
Plugin Name: 360-reviews
Description: Display Listen 360 reviews on a page using shortcode [l360_reviews]. Enter your unique api key and other settings in the Settings menu under the L360 Reviews link.
Version: 1.0
Author: Dustin Fraker
Author
*/




require('functions.php');


class L360Reviews {

    //define url with api key for listen360
    private $url;

    //var perPage set from options
    private $perPage;

    //define bg colors from color pickers
    private $promoterBg;

    private $passiveBg;

    private $detractorBg;


    /*===========================================================*
     * Constructor
     * ==========================================================*/

    /**
     * Initialize the plugin setting filters and admin functions
     *
     * @since 1.0
     * */

    function __construct()
    {
        //Setup the dactivation function for clearing our reviews when the plugin is deactivated
        register_deactivation_hook(__FILE__, array($this, 'deactivate')); //won't need this if not saving reviews to the database.

        //Register admin stylesheets
        add_action('admin_print_styles', array($this, 'register_admin_styles'));

        //Register plugin stylesheets
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));

        //add options menu to the admin of wp site
        add_action('admin_menu', 'l360_reviews_admin_add_page');

        //grab options array from the wpdb
        $options = get_option('plugin_options');

        //set some variables from input on settings page
        $this->url              =   $options['url'];
        $this->perPage          =   $options['perPage'];
        $this->promoterBg       =   $options['promoterBg'];
        $this->passiveBg        =   $options['passiveBg'];
        $this->detractorBg      =   $options['detractorBg'];


    }

    /*===========================================================*
    * Enqueue Stylesheets
    * ==========================================================*/


    /**
     * Registers and enqueue plugin specific styles
     *
     * @since 1.0
     * */
    public function register_admin_styles()
    {
        wp_enqueue_style('360-reviews-admin', plugins_url('360-reviews/css/admin.css'));
    }//end register_plugin_styles

    /**
     * Registers and enqueue plugin specific styles
     *
     * @since 1.0
     * */
    public function register_plugin_styles()
    {
        wp_enqueue_style('360-reviews', plugins_url('360-reviews/css/plugin.css'));
    }//end register_plugin_styles


    /*===========================================================*
    * Enqueue Stylesheets
    * ==========================================================*/


    /**
     * Registers and enqueue plugin specific styles
     *
     * @since 1.0
     * */

    public function l360_reviews_shortcode( $atts ) {
        extract(shortcode_atts(array(
            //how many reviews to display per page. Default is set to 10 here
            'perPage' => '5'
        ), $atts));

        return $this->render($this->get_reviews($this->perPage));
    }




    //GET PAGE FOR PAGINATION SYSTEM.
    public function getPage()
    {
        //set page var if not available
        if(!isset($_GET['currentPage']))
        {
            $page = 1;
            return $page;
        }
        else
        {
            $page = $_GET['currentPage'];
            return $page;
        }
    }

        //get the reviews based on pagination and shortcode settings.
        public function get_reviews($perPage) {
        $pageNum = Static::getPage();

        //build up url for request
        $pagedUrl = $this->url . "?per_page=$perPage" . "&page=$pageNum";
        //get the response from listen 360
        $response = wp_remote_get($pagedUrl);
        //convert to xml object
        if($response['headers']['status'] == '200 OK')
        {
            return simplexml_load_string($response['body']);
        }else
        {
            die('you have not specified a valid url for the api or the api service is down! Please check your url or try again later');
        }


    }

        //get the appropriate question based on rating
        public function getQuestion($rating){
        if($rating >= 9){
            return "What do you like about our services?";
        }elseif($rating == 7 or $rating == 8){
            return "What could we do to improve?";
        }elseif($rating <= 6){
            return "How did we disappoint you and what can we do to make things right?";
        }else{
            return false;
        }
    }
        //display bg color based on review rating.
        function getBgStyle($rating){
        if($rating >= 9){
            return $this->promoterBg;
        }elseif($rating == 7 or $rating == 8){
            return $this->passiveBg;
        }elseif($rating <= 6){
            return $this->detractorBg;
        }else{
            return false;
        }
    }

        function get_overall_rating($url) {
            //Todo: query 360 and calc total rating
        }

        //take customer name and split it into first and last initials for display.
        public function getInitials($name) {
        //take name from the xml doc and return the initials
        if($name != ''){
            //split name and put results into an array
            $aName = explode(" ", $name);
            //grab first char of first index item
            $firstInitial = $aName[0]{0};
            //grab first char of second index item
            $lastInitial = $aName[1]{0};
            //return and concat results
            return $firstInitial . "." . " " . $lastInitial . ".";

        }else{
            return false;
        }
    }
        public function render($reviews) {

        //loop through all the reviews and format html
        $html = '';

        ob_start();

        foreach ($reviews->survey as $review) {

            // check that review is not censored
            if ($review->censored == 'false'){
                //generate view for reviews
                include('_partials/review.php');
            }
        }

        //generate view for pagination links
        include('_partials/pagination.php');

        $html = ob_get_clean();

        return $html;

    }//end render function

} //end class
