<?php
  /**
   * NOTE:
   * ======
   * Could all of this be placed in the 'before borders'?
   * 
   * The $isCityPage variable does not work
   */
  global $thePage, $cmsPageData, $siteTokens;
  $siteConfig = new SiteConfig($thePage, $csmPageData, $siteTokens);

  $isDevelopmentMode = (!empty($_GET['test']) && $_GET['test'] == 1);

  // List of pages that contain no silo
  $noSilo = array (
    "insulation",
    "refer",
    "opinion",
    "sitemap",
    "pay-now",
    "service-area",
    "privacy-policy",
    "free-estimate"
  );

  // List of all the macros
  $macroPages = array (
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

  /**
   * ==================================================
   *                  REMOVE THIS
   * ==================================================
   */
  // Temp work around for the city pages
  // This assumes that the city page is inside the services area and checking to see if the url is nested
  $isCityPage = (strpos($thePage, 'service-area') !== false) && (strpos($thePage, '/') !== false);

  /**
   * Function declarations
   * ======================
   */
  // Checks for substring matches in an array
  // TODO: return the matching index
  function substr_in_array($haystack, $needle) {
    for($i = 0; $i < count($haystack); $i++) {
      $string_pos = strpos($needle, $haystack[$i]);

      if ($string_pos !== false) {
        return true;
      }
    }

    return false;
  }

  /**
   * Determine page type (and macro)
   * ====================
   */
  $pageType = "";
  $macroType = "";

  if($thePage == "index") {
    $pageType = 'HOME';
  } else if(substr_in_array($macroPages, $thePage)) {
    // content is now partial and should only contain subset of the content styles
    $pageType = 'CONTENT';
  } else {
    /**
     * When the page type is unknown it could be that
     * the logic is not picking it up properly. But it
     * is highly likely that its a content page of some
     * sort. So load in the content styles and the page should
     * work half decently.
     */
    $pageType = 'CONTENT';
  }


  /**
   * Selective Content (should render)
   * ====================
   * - Should render silo
   * - Should render breadcrumbs
   * - Should render service areas
   */
  $showSilo = true;
  if(substr_in_array($noSilo, $thePage) || $isCityPage) {
    $showSilo = false;
  }
  
  $showBreadcrumbs = true;
  if(strpos($thePage, 'free-estimate') !== false) {
    $showBreadcrumbs = false;
  }
  
  $showServiceAreas = true;
  if((strpos($thePage, 'service-area') !== false)) {
    // Don't show service area section in service area pages
    $showServiceAreas = false;
  }

  /**
   * Selective styles & scripts
   * ====================
   * When a page contains a macro only a subset of the
   * content styles get loaded.
   * 
   * Maybe inject all but the global styles into
   * a style tag. Stylesheets can then be hosted
   * on the file manager (CDN)
   */
  $topInject = "";
  $bottomInject = "";

  

  if($pageType == "HOME") {
    $topInject .= '<link rel="stylesheet" type="text/css" href="https://cdn.treehouseinternetgroup.com/cms_images/101/homepage.css?v2">';
    $bottomInject .= '<script src="https://cdn.treehouseinternetgroup.com/cms_images/101/home.js?v1"></script>';
  } elseif ($pageType == "CONTENT") {
    // TODO: inject proper macro styles
    $topInject .= '<link rel="stylesheet" type="text/css" href="https://cdn.treehouseinternetgroup.com/cms_images/101/content.css?v2">';
    $bottomInject .= '<script src="https://cdn.treehouseinternetgroup.com/cms_images/101/content.js?v1"></script>';

    if(strpos($thePage, 'free-estimate') !== false) {
      // Inject jquery
      $topInject .= '<script type="text/javascript" src="https://cdn.treehouseinternetgroup.com/cms_core/assets/js/jquery.min.js"></script>';
    }

    // Inject some styles and scripts into document
    if($isCityPage || $thePage == 'refer' || $thePage == 'opinion' || strpos($thePage, 'photo-gallery') || strpos($thePage, 'meet-the-team') || strpos($thePage, 'confirmation')) {      
      // Some widgets(Maps) still require jquery to be present
      $topInject .= '<script type="text/javascript" src="https://cdn.treehouseinternetgroup.com/cms_core/assets/js/jquery.min.js"></script><style>@media screen and (max-width:767px){.photogallery_wrapper .ad-gallery .ad-image-wrapper,.photogallery_wrapper .ad-gallery .ad-image-wrapper .ad-image{height:400px!important}.photogallery_wrapper .ad-gallery .ad-image-wrapper .ad-image img{height:auto}.ad-gallery .ad-controls{top:370px}}@media screen and (max-width:640px){.photogallery_wrapper .ad-gallery .ad-image-wrapper,.photogallery_wrapper .ad-gallery .ad-image-wrapper .ad-image{height:300px!important}.ad-gallery .ad-controls{top:270px}}</style>';
    }
    
    if(strpos($thePage, 'meet-the-team')) {      
      $topInject .= '<style>#cboxOverlay,#cboxWrapper,#colorbox{position:absolute;top:0;left:0;z-index:9999;overflow:hidden;-webkit-transform:translate3d(0,0,0)}#cboxWrapper{max-width:none}#cboxOverlay{position:fixed;width:100%;height:100%}#cboxBottomLeft,#cboxMiddleLeft{clear:left}#cboxContent{position:relative}#cboxLoadedContent{overflow:auto;-webkit-overflow-scrolling:touch}#cboxTitle{margin:0}#cboxLoadingGraphic,#cboxLoadingOverlay{position:absolute;top:0;left:0;width:100%;height:100%}#cboxClose,#cboxNext,#cboxPrevious,#cboxSlideshow{cursor:pointer}.cboxPhoto{float:left;margin:auto;border:0;display:block;max-width:none;-ms-interpolation-mode:bicubic}.cboxIframe{width:100%;height:100%;display:block;border:0;padding:0;margin:0}#cboxContent,#cboxLoadedContent,#colorbox{box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box}#cboxOverlay{background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/overlay.png) repeat 0 0;opacity:.9}#colorbox{outline:0}#cboxTopLeft{width:21px;height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -101px 0}#cboxTopRight{width:21px;height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -130px 0}#cboxBottomLeft{width:21px;height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -101px -29px}#cboxBottomRight{width:21px;height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -130px -29px}#cboxMiddleLeft{width:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) left top repeat-y}#cboxMiddleRight{width:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) right top repeat-y}#cboxTopCenter{height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/border.png) 0 0 repeat-x}#cboxBottomCenter{height:21px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/border.png) 0 -29px repeat-x}#cboxContent{background:#fff;overflow:hidden}.cboxIframe{background:#fff}#cboxError{padding:50px;border:1px solid #ccc}#cboxLoadedContent{margin-bottom:28px}#cboxTitle{position:absolute;bottom:4px;left:0;text-align:center;width:100%;color:#949494}#cboxCurrent{position:absolute;bottom:4px;left:58px;color:#949494}#cboxLoadingOverlay{background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/loading_background.png) no-repeat center center}#cboxLoadingGraphic{background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/loading.gif) no-repeat center center}#cboxClose,#cboxNext,#cboxPrevious,#cboxSlideshow{border:0;padding:0;margin:0;overflow:visible;width:auto;background:0 0}#cboxClose:active,#cboxNext:active,#cboxPrevious:active,#cboxSlideshow:active{outline:0}#cboxSlideshow{position:absolute;bottom:4px;right:30px;color:#0092ef}#cboxPrevious{position:absolute;bottom:0;left:0;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -75px 0;width:25px;height:25px;text-indent:-9999px}#cboxPrevious:hover{background-position:-75px -25px}#cboxNext{position:absolute;bottom:0;left:27px;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -50px 0;width:25px;height:25px;text-indent:-9999px}#cboxNext:hover{background-position:-50px -25px}#cboxClose{position:absolute;bottom:0;right:0;background:url(https://cdn.treehouseinternetgroup.com/cms_core/images/colorbox/controls.png) no-repeat -25px 0;width:25px;height:25px;text-indent:-9999px}#cboxClose:hover{background-position:-25px -25px}</style><script src="https://cdn.treehouseinternetgroup.com/cms_core/assets/js/jquery.colorbox-min.js" defer=""></script>';
    }
    
    if(strpos($thePage, 'confirmation')) {
        $topInject .= '<style>.slick-list.draggable {display: block;overflow: hidden;} .open_times {width: 320px !important;} 	@media screen and (max-width:640px) { .open_times {display:none;}}</style>';
    }
    
  }

  // Dev Specific
  if($isDevelopmentMode) {
    $bottomInject .= '<script src="https://cdn.treehouseinternetgroup.com/cms_images/101/dev_tools.js"></script>';
  }

  /**
   * Generated Content (super navs)
   * ====================
   * - Top nav
   * - Bottom nav
   */
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

  $bottomNav = new nav();
  $bottomNav->superTemplateId = 21;
  $bottomNav->superMode = 'bottom';
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon -->
    <link rel="icon" href="http://images.suredrybasements.com/101/Sure_Dry_Circle_Only.png">
    <script src="https://unpkg.com/embla-carousel/embla-carousel.umd.js"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;800;900&display=swap"
      rel="stylesheet" />

    <title>[[title]]</title>

    <!-- Styles (Global)-->
    <?php  /* this is template.css  */?>
    <link rel="stylesheet" type="text/css" href="/inc/sb-styles.css?v=3">

    <!-- Selected styles -->
    <?= $topInject; ?>
  </head>

  <body>
    <!--
      Navigation bar
      ==============
    -->
    <nav>
      <!-- Top navigation -->
      <section class="navigation-layout">
        <!-- BG for banner -->
        <div class="banner"></div>
        <div class="banner-text navigation-start-triangle">
          [[open-indicator]]
          <!-- This could be a border or a character -->
          <div class="serving">Yes, we serve <b>[territory]</b></div>
          <div class="cta">
            <span class="cta-text"><a href="tel:<?= $phonenumber; ?>">[[phone]]</a></span>
            <span class="cta-text-small">
                <a href="tel:<?= $phonenumber; ?>">
                    <svg
                        class="phone"
                        viewBox="0 0 24 24"
                        fill="none"
                        >
                          <path
                            d="M22 12C22 10.6868 21.7413 9.38647 21.2388 8.1731C20.7362 6.95996 19.9997 5.85742 19.0711 4.92896C18.1425 4.00024 17.0401 3.26367 15.8268 2.76123C14.6136 2.25854 13.3132 2 12 2V4C13.0506 4 14.0909 4.20703 15.0615 4.60889C16.0321 5.01099 16.914 5.60034 17.6569 6.34326C18.3997 7.08594 18.989 7.96802 19.391 8.93848C19.7931 9.90918 20 10.9495 20 12H22Z"
                            fill="white"
                          />
                          <path
                            d="M2 10V5C2 4.44775 2.44772 4 3 4H8C8.55228 4 9 4.44775 9 5V9C9 9.55225 8.55228 10 8 10H6C6 14.4182 9.58173 18 14 18V16C14 15.4478 14.4477 15 15 15H19C19.5523 15 20 15.4478 20 16V21C20 21.5522 19.5523 22 19 22H14C7.37259 22 2 16.6274 2 10Z"
                            fill="white"
                          />
                          <path
                            d="M17.5433 9.70386C17.8448 10.4319 18 11.2122 18 12H16.2C16.2 11.4485 16.0914 10.9023 15.8803 10.3928C15.6692 9.88306 15.3599 9.42017 14.9698 9.03027C14.5798 8.64014 14.1169 8.33081 13.6073 8.11963C13.0977 7.90869 12.5515 7.80005 12 7.80005V6C12.7879 6 13.5681 6.15527 14.2961 6.45679C15.024 6.7583 15.6855 7.2002 16.2426 7.75732C16.7998 8.31445 17.2418 8.97583 17.5433 9.70386Z"
                            fill="white"
                          />
                    </svg>
                  Call Now
                </a>
                
            </span>
          </div>
        </div>

        <!-- Top Nav -->
        <?= $topNav->generateSuperMarkup(); ?>

        <div class="logo-container">
          <a href='/'>
            <img
              class="object-contain- object-left- img"
              src="https://cdn.treehouseinternetgroup.com/cms_images/101/logo.f311f897.png"
            />
          </a>
        </div>
      </section>
    </nav> 

    <main>
      <!--
        Home Page
        ==============
      -->
      <? if($pageType == "HOME"): ?>     
      
      <!--
        Content Page
        ==============
      -->
      <? elseif ($pageType == "CONTENT"): ?>
      <!-- Content Section -->
      <section
        class="content-template-article space-section <?= $showSilo ? "" : 'no-silo'; ?> <?= $showBreadcrumbs ? "" : 'no-breadcrumbs'; ?>"
      >
        <? if($showBreadcrumbs): ?>
        <nav aria-label="Breadcrumb" class="content-template-breadcrumbs">
          [[breadcrumbs]]
        </nav>
        <? endif; ?>

        <? if ($showSilo): ?>
        <aside class="content-template-silo">
          <div class="content-template-silo-menu">
            <button id="silo-menu-button">
              Open Menu
              <span class="dropdown">
                <svg
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M6.34317 7.75732L4.92896 9.17154L12 16.2426L19.0711 9.17157L17.6569 7.75735L12 13.4142L6.34317 7.75732Z"
                    fill="currentColor"
                  />
                </svg>
              </span>
            </button>
          </div>
          <div class="content-template-silo-container" id="silo-container">
            [[single_silo_nav]]
          </div>
        </aside>
        <? endif; ?>

        
        <article class="content-template-main">
          <? endif; ?>
          [[content]]
          <? if($pageType == "CONTENT"): ?>
        </article>
      </section>
      <? endif; ?>

      <!--
        Service Areas (Somewhat page agnostic)
        ==============
      -->
      <? if($showServiceAreas): ?>
      <section class="service-areas">
        <div class="service-areas-map-container">
          <picture>
            <source
              srcset="https://cdn.treehouseinternetgroup.com/cms_images/101/map.opt.avif"
              type="image/avif"
            />
            <source
              srcset="https://cdn.treehouseinternetgroup.com/cms_images/101/map.opt.webp"
              type="image/webp"
            />
            <img
              class="object-cover- img"
              src="https://cdn.treehouseinternetgroup.com/cms_images/101/map.opt.jpg"
            />
          </picture>
        </div>

        <div class="service-areas-bg-container">
          <picture>
            <source
              srcset="https://cdn.treehouseinternetgroup.com/cms_images/101/noise.opt.avif"
              type="image/avif"
            />
            <source
              srcset="https://cdn.treehouseinternetgroup.com/cms_images/101/noise.opt.webp"
              type="image/webp"
            />
            <img
              class="img service-areas-bg-image"
              src="https://cdn.treehouseinternetgroup.com/cms_images/101/noise.opt.jpg"
            />
          </picture>
        </div>

        <div class="service-areas-heading-container">
          <h2 class="service-areas-heading">
            Proudly serving Central & Northeast Wisconsin - Green Bay, Oshkosh,
            Appleton, Wausau
          </h2>
        </div>

        <div class="service-areas-content-container">
          [[city_scroll:50]]
        </div>
      </section>
      <? endif; ?>
    </main>

    <!--
      Footer Links
      ==============
    -->
    <footer class="footer">
      <!-- Top Navigation -->
      <section class="top-footer">
        <div class="cta-block">
          <label class="cta-label"> Contact Us Online </label>
          <a class="button" href="/free-estimate.html"> Get Your Free Estimate </a>

          <label class="cta-label"> Or call us at </label>
          <p class="cta-number"><a href="tel:<?php echo $phonenumber; ?>">[[phone]]</a></p>
          <p class="footer-address">[[display_addresses]]</p>
        </div>

        <!-- Bottom Nav -->
        <?= $bottomNav->generateSuperMarkup(); ?>
      </section>

      <!-- Bottom Banner -->
      <section class="bottom-footer">
        <span class="text-span">
          <span class="text-bold"> © <?php echo(date('Y')); ?> Sure-Dry Basement Systems </span>
          
        </span>
    
         <!-- <span class="text-span">| <a href="tel:<?php echo $phonenumber; ?>">[[phone]]</a></span>-->
      </section>
    </footer>
    
    <script>
        // Replace header service with "we service" token if it's not empty
       (() => {
            var ws = '[[city_serve_token]]';
            if (ws && ws == '[[city_serve_token]]') {
                document.querySelector('.serving').innerHTML = ws.replace(/(<\/?h)([0-6])>/g, '');
            }
       })();
    </script>

    <!-- Selected scripts -->
    <?= $bottomInject; ?>

    <!-- City Page core scripts -->
    <?php if( ($isCityPage) OR (stristr($thePage,"meet-the-team")) OR (strpos($thePage, 'confirmation')) ) { ?>
    [[custom_core_v3_9_js]]
    <?php } ?>
    
    
    <script defer src="https://connect.podium.com/widget.js#API_TOKEN=abfc03c0-6d23-4ce4-b9b9-b284af5de0ce" id="podium-widget" data-api-token="abfc03c0-6d23-4ce4-b9b9-b284af5de0ce"></script>
    
 
     
     
  </body>

</html>