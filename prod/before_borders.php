<?php
  class SiteConfig {
    /**
     * Proxied Data
     * =============
     * Some data is only available AFTER the before borders
     * are done. So I'm passing these values as references
     * from the point that they are actually available (borders)
     */
    private $thePage;
    private $cmsPageData;
    private $siteTokens;
    private $proxy = "https://gh-proxy.jsiwicki.workers.dev/?url=";

    /**
     * Collections
     * ===========
     */
    private $noSilo = array (
      "insulation",
      "refer",
      "opinion",
      "sitemap",
      "pay-now",
      "service-area",
      "privacy-policy",
      "free-estimate"
    );
    private $modulePages = array (
      "opinion",
      "before-after",
      "photo-gallery",
      "refer",
      "meet-the-team",
      "news-and-events",
      "blog",
      "reviews",
      "awards",
      "press-release",
      "crew-review",
      "case-studies",
      "affiliations",
      "technical-papers",
      "case-studies",
      "search",
      "service-area",
      "homeshows",
      "about-us",
      "testimonials",
      "free-estimate"
    );
    private $devLinks = array(
      // JS files
      "home.js" => "https://raw.githubusercontent.com/bs-production/sure-dry-template/master/prod/home.js",
      "content.js" => "https://raw.githubusercontent.com/bs-production/sure-dry-template/master/prod/content.js",
      "dev_tools.js" => "https://raw.githubusercontent.com/bs-production/sure-dry-template/master/prod/dev_tools.js",

      // Css files
      "homepage.css" => "https://raw.githubusercontent.com/bs-production/sure-dry-template/master/prod/homepage.css",
      "content.css" => "https://raw.githubusercontent.com/bs-production/sure-dry-template/master/prod/content.css"
    );
    private $prodLinks = array(
      // Css files
      "template.css" => "/inc/sb-styles.css?v=3",
      "homepage.css" => "https://s3.amazonaws.com/cdn.treehouseinternetgroup.com/cms_images/101/homepage.css",
      "content.css" => "https://s3.amazonaws.com/cdn.treehouseinternetgroup.com/cms_images/101/content.css",

      // Js files
      "jquery.js" => "https://cdn.treehouseinternetgroup.com/cms_core/assets/js/jquery.min.js",
      "jquery.colorbox.js" => "https://cdn.treehouseinternetgroup.com/cms_core/assets/js/jquery.colorbox-min.js",
      "home.js" => "https://s3.amazonaws.com/cdn.treehouseinternetgroup.com/cms_images/101/home.js",
      "content.js" => "https://s3.amazonaws.com/cdn.treehouseinternetgroup.com/cms_images/101/content.js",
      
      "embla-carousel.js" => "https://unpkg.com/embla-carousel/embla-carousel.umd.js",
      
      // Meta
      "favicon.ico" => "http://images.suredrybasements.com/101/Sure_Dry_Circle_Only.png",

      // Fonts
      "fonts.preconnect" => "https://fonts.gstatic.com",
      "fonts.css" => "https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;800;900&display=swap", 
    );
    private $styleHelpers = array(
      "widgets-fix" => "<style>@media screen and (max-width:767px){.photogallery_wrapper .ad-gallery .ad-image-wrapper,.photogallery_wrapper .ad-gallery .ad-image-wrapper .ad-image{height:400px!important}.photogallery_wrapper .ad-gallery .ad-image-wrapper .ad-image img{height:auto}.ad-gallery .ad-controls{top:370px}}@media screen and (max-width:640px){.photogallery_wrapper .ad-gallery .ad-image-wrapper,.photogallery_wrapper .ad-gallery .ad-image-wrapper .ad-image{height:300px!important}.ad-gallery .ad-controls{top:270px}}</style>",
      "meet-the-team-fix" => "<style>#cboxOverlay,#cboxWrapper,#colorbox{position:absolute;top:0;left:0;z-index:9999;overflow:hidden;-webkit-transform:translate3d(0,0,0)}#cboxWrapper{max-width:none}#cboxOverlay{position:fixed;width:100%;height:100%}#cboxBottomLeft,#cboxMiddleLeft{clear:left}#cboxContent{position:relative}#cboxLoadedContent{overflow:auto;-webkit-overflow-scrolling:touch}#cboxTitle{margin:0}#cboxLoadingGraphic,#cboxLoadingOverlay{position:absolute;top:0;left:0;width:100%;height:100%}#cboxClose,#cboxNext,#cboxPrevious,#cboxSlideshow{cursor:pointer}.cboxPhoto{float:left;margin:auto;border:0;display:block;max-width:none;-ms-interpolation-mode:bicubic}.cboxIframe{width:100%;height:100%;display:block;border:0;padding:0;margin:0}#cboxContent,#cboxLoadedContent,#colorbox{box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box}#cboxOverlay{background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/overlay.png) repeat 0 0;opacity:.9}#colorbox{outline:0}#cboxTopLeft{width:21px;height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -101px 0}#cboxTopRight{width:21px;height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -130px 0}#cboxBottomLeft{width:21px;height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -101px -29px}#cboxBottomRight{width:21px;height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -130px -29px}#cboxMiddleLeft{width:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) left top repeat-y}#cboxMiddleRight{width:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) right top repeat-y}#cboxTopCenter{height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/border.png) 0 0 repeat-x}#cboxBottomCenter{height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/border.png) 0 -29px repeat-x}#cboxContent{background:#fff;overflow:hidden}.cboxIframe{background:#fff}#cboxError{padding:50px;border:1px solid #ccc}#cboxLoadedContent{margin-bottom:28px}#cboxTitle{position:absolute;bottom:4px;left:0;text-align:center;width:100%;color:#949494}#cboxCurrent{position:absolute;bottom:4px;left:58px;color:#949494}#cboxLoadingOverlay{background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/loading_background.png) no-repeat center center}#cboxLoadingGraphic{background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/loading.gif) no-repeat center center}#cboxClose,#cboxNext,#cboxPrevious,#cboxSlideshow{border:0;padding:0;margin:0;overflow:visible;width:auto;background:0 0}#cboxClose:active,#cboxNext:active,#cboxPrevious:active,#cboxSlideshow:active{outline:0}#cboxSlideshow{position:absolute;bottom:4px;right:30px;color:#0092ef}#cboxPrevious{position:absolute;bottom:0;left:0;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -75px 0;width:25px;height:25px;text-indent:-9999px}#cboxPrevious:hover{background-position:-75px -25px}#cboxNext{position:absolute;bottom:0;left:27px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -50px 0;width:25px;height:25px;text-indent:-9999px}#cboxNext:hover{background-position:-50px -25px}#cboxClose{position:absolute;bottom:0;right:0;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -25px 0;width:25px;height:25px;text-indent:-9999px}#cboxClose:hover{background-position:-25px -25px}</style>",
      "confirmation-fix" => "<style>.slick-list.draggable {display: block;overflow: hidden;} .open_times {width: 320px !important;} 	@media screen and (max-width:640px) { .open_times {display:none;}}</style>",
    );

    /**
     * Helper properties
     * =================
     */
    public $pageType;
    public $isCityPage;
    public $isDev;
    public $isTest;


    /**
     * Switches
     * ==========
     * These are used to render content based on page conditions such as:
     *    - Page Type
     *    - Collection of pages
     *    - Specific pages
     */
    public $showSilo = true;
    public $showBreadcrumbs = true;
    public $showServiceAreas = true;

    function __construct($thePage, &$cmsPageData, &$siteTokens) {
      $this->thePage = $thePage;
      $this->cmsPageData = &$cmsPageData;
      $this->siteTokens = &$siteTokens;

      // Set helpers
      $this->isCityPage = (strpos($thePage, 'service-area') !== false) && (strpos($thePage, '/') !== false);
      $this->isDev = (!empty($_GET['dev_template']) && $_GET['dev_template'] == 1) || (!empty($_GET['dev_content']) && $_GET['dev_content'] == 1);
      $this->isTest = (!empty($_GET['test']) && $_GET['test'] == 1);

      $this->handleSetters();
      $this->handleSwitches();
      $this->handleCustomTokenCreation();
    }


    /**
     * SETTERS
     * ========================
     */
    private function handleSetters() {
      $this->set_pageType();
    }

    private function set_pageType() {
      if($this->thePage == "index") {
        $this->pageType = 'HOME';
      } else if($this->substr_in_array($this->modulePages, $this->thePage)) {
        $this->pageType = 'CONTENT';
      } else {
        // This should be UKNOWN
        $this->pageType = 'CONTENT';
      }
    }


    /**
     * SWITCH HANDLERS
     * ========================
     */
    private function handleSwitches() {
      $this->set_showSilo_switch();
      $this->set_showBreadcrumbs_switch();
      $this->set_showServiceAreas_switch();
    }

    private function set_showSilo_switch() {
      if($this->substr_in_array($this->noSilo, $this->thePage) || $this->isCityPage) {
        $this->showSilo = false;
      }
    }

    private function set_showBreadcrumbs_switch() {
      if(strpos($this->thePage, 'free-estimate') !== false) {
        $this->showBreadcrumbs = false;
      }
    }

    private function set_showServiceAreas_switch() {
      if((strpos($this->thePage, 'service-area') !== false)) {
        // Don't show service area section in service area pages
        $this->showServiceAreas = false;
      }
    }


    /**
     * CUSTOM TOKEN CREATION
     * =======================
     */
    private function handleCustomTokenCreation() {
      // Tags injection
      $this->create_TopInject_token();
      $this->create_BottomInject_token();
      
      // Links Generation
      $this->create_TopNavLinks_token();
      $this->create_BottomNavLinks_token();

      $this->create_OpenIndicator_token();
    }

    private function create_TopInject_token() {
      $topData = "";

      // Insert favicon
      $topData .= $this->generateLinkTag($this->prodLinks['favicon.ico'], "icon");

      // Insert CSS reset
      $topData .= $this->generateLinkTag($this->prodLinks['template.css']);

      // Insert fonts
      $topData .= $this->generateLinkTag($this->prodLinks['fonts.preconnect'], "preconnect");
      $topData .= $this->generateLinkTag($this->prodLinks['fonts.css'], "stylesheet");

      // Page type based injection
      switch($this->pageType) { 
        case "HOME": {
          if($this->isDev) {
            $topData .= $this->generateLinkTag($this->proxy . $this->devLinks['homepage.css']);
          } else {
            $topData .= $this->generateLinkTag($this->prodLinks['homepage.css']);
          }
          
          $topData .= $this->generateScriptTag($this->prodLinks['embla-carousel.js']);
          break;
        }

        
        case "CONTENT": {
          if($this->isDev) {
            $topData .= $this->generateLinkTag($this->proxy . $this->devLinks['content.css']);
          } else {
            $topData .= $this->generateLinkTag($this->prodLinks['content.css']);
          }

          // Style and script fixes
          if(strpos($this->thePage, 'free-estimate') !== false) {
            $topData .= $this->generateScriptTag($this->prodLinks['jquery.js']);
          }

          if(
            $this->isCityPage ||
            $this->thePage == 'refer' ||
            $this->thePage == 'opinion' ||
            strpos($this->thePage, 'photo-gallery') ||
            strpos($this->thePage, 'meet-the-team') ||
            strpos($this->thePage, 'confirmation')
          ) {
            $topData .= $this->generateScriptTag($this->prodLinks['jquery.js']);
            $topData .= $this->styleHelpers['widgets-fix'];
          }

          if(strpos($this->thePage, 'meet-the-team')) {
            $topData .= $this->generateScriptTag($this->prodLinks['jquery.colorbox.js']);
            $topData .= $this->styleHelpers['meet-the-team-fix'];
          }

          if(strpos($this->thePage, 'confirmation')) {
            $topData .= $this->styleHelpers['confirmation-fix'];
          }
        }

        /**
         * When the page is unknown, its best
         * to treat it as a CONTENT type
         */
        default: {
          if($this->isDev) {
            $topData .= $this->generateLinkTag($this->proxy . $this->devLinks['content.css']);
          } else {
            $topData .= $this->generateLinkTag($this->prodLinks['content.css']);
          }
        }
      }

      $this->siteTokens['[[top-inject]]'] = $topData;
    }

    private function create_BottomInject_token() {
      $bottomData = "";

      // Page type based injection
      switch($this->pageType) { 
        case "HOME": {
          if($this->isDev) {
            $bottomData .= $this->generateScriptTag($this->proxy . $this->devLinks['home.js']);
          } else {
            $bottomData .= $this->generateScriptTag($this->prodLinks['home.js']);
          }
          break;
        }

        /**
         * When the page is unknown, its best
         * to treat it as a CONTENT type
         */
        case "CONTENT":
        default: {
          if($this->isDev) {
            $bottomData .= $this->generateScriptTag($this->proxy . $this->devLinks['content.js']);
          } else {
            $bottomData .= $this->generateScriptTag($this->prodLinks['content.js']);
          }
        }
      }

      // Insert dev_tools reset
      if($this->isTest) {
        $bottomData .= $this->generateScriptTag($this->proxy . $this->devLinks['dev_tools.js']);
      }

      $this->siteTokens['[[bottom-inject]]'] = $bottomData;
    }

    private function create_TopNavLinks_token() {
      $topNav = new nav();
      $topNav->superTemplateId = 20;
      $topNav->superMode = 'top';
      $topNav->superItems = array(
        'Services' => array(
          'class' => 'columned',
          'target' => 'services'
        ),
        17856 => array(
          'class' => 'simple',
          'children' => array(32810,114693,17850,17853,17857,17859,40112,29188,31194,231032, 234827)
        ),
        32810 => array(
          'grandchildren' => false
        ),
        'Service Area' => array(
          'target' => 'map',
        ),
        235370 => array(
          'grandchildren' => true
        ),
        'Free Quote' => array(
          'class' => 'quote',
          'target' => 'contact'
        )
      );

      $this->siteTokens['[[top-nav-links]]'] = $topNav->generateSuperMarkup();
    }

    private function create_BottomNavLinks_token() {
      $bottomNav = new nav();
      $bottomNav->superTemplateId = 21;
      $bottomNav->superMode = 'bottom';

      $this->siteTokens['[[bottom-nav-links]]'] = $bottomNav->generateSuperMarkup();
    }

    private function create_OpenIndicator_token() {
      $times = get_open_time();
      $content = "";

      if($times || isset($_GET['open'])) {
        $content = "
          <div class=\"closing-at-container\">
            <b>Open Now</b> <i class=\"closing-at\">(closing at 6pm)</i>
          </div>
          <div class=\"separator\">|</div>
        ";
      } else {
        $content = "
          <div class=\"closing-at-container\"></div>
        ";
      }

      $this->siteTokens['[[open-indicator]]'] = $content;
    }


    /**
     * UTILITIES
     * =======================
     */
    
    /**
     * Returns true when there is a substr match inside an array
     * 
     * NOTE:
     * ====
     * The naming of this utility is not consistent with the rest of
     * the naming convention. It's more of an extension of php's built-in
     * functions. So it made more sense to keep to that convention and not to mine
     */
    function substr_in_array($haystack, $needle) {
      for($i = 0; $i < count($haystack); $i++) {
        $string_pos = strpos($needle, $haystack[$i]);
  
        if ($string_pos !== false) {
          return true;
        }
      }
  
      return false;
    }

    function generateScriptTag($src) {
      return str_replace(array("\\r", "\\n"), '',sprintf("
        <script type=\"text/javascript\" src=\"%s\"></script>
      ", $src));
    }

    function generateLinkTag($href, $rel = "stylesheet") {
      return str_replace(array("\\r", "\\n"), '',sprintf("
        <link href=\"%s\" rel=\"%s\" ></link>
      ", $href, $rel));
    }

    function generateImageTag($src, $classList = "") {
      return str_replace(array("\\r", "\\n"), '',sprintf("
        <img src=\"%s\" class=\"%s\" />
      ", $src, $classList));
    }

  };
?>