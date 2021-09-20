<?php
  /**
   * NOTE:
   * ======
   * Could all of this be placed in the 'before borders'?
   * 
   * The $isCityPage variable does not work
   */
  global $thePage, $cmsPageData;

  $isDevelopmentMode = (!empty($_GET['test']) && $_GET['test'] == 1);

  // List of pages that contain no silo
  $noSilo = array (
    "insulation",
    "refer",
    "opinion",
    "sitemap",
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
   * Content Prevention
   * ====================
   * Prevent Content from being rendered into the home page
   */
  if($pageType == "HOME") {
    $cmsPageData["page.body_content"] = "";
    $cmsPageData["page.bottom_content"] = "";
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
  if((strpos($thePage, 'service-area') !== false) && (strpos($thePage, '/') !== false)) {
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
    $topInject .= '<link rel="stylesheet" type="text/css" href="https://combinatronics.com/IamStephan/sure_dry_template_v2/master/prod/homepage.css">';
    $bottomInject .= '<script src="https://combinatronics.com/IamStephan/sure_dry_template_v2/master/prod/home.js"></script>';
  } elseif ($pageType == "CONTENT") {
    // TODO: inject proper macro styles
    $topInject .= '<link rel="stylesheet" type="text/css" href="https://combinatronics.com/IamStephan/sure_dry_template_v2/master/prod/content.css">';
    $bottomInject .= '<script src="https://combinatronics.com/IamStephan/sure_dry_template_v2/master/prod/content.js"></script>';

    if(strpos($thePage, 'free-estimate') !== false) {
      // Inject jquery
      $topInject .= '<script type="text/javascript" src="https://cdn.treehouseinternetgroup.com/cms_core/assets/js/jquery.min.js"></script>';
    }

    // Inject some styles and scripts into document
    if($isCityPage) {      
      // Some widgets(Maps) still require jquery to be present
      $topInject .= '<script type="text/javascript" src="https://cdn.treehouseinternetgroup.com/cms_core/assets/js/jquery.min.js"></script>';
    }
  }

  // Dev Specific
  if($isDevelopmentMode) {
    $bottomInject .= '<script src="https://combinatronics.com/IamStephan/sure_dry_template_v2/master/prod/dev_tools.js"></script>';
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
      'children' => array(32810,114693,17850,17853,17857,17859,40112,29188,31194,231032)
    ),
    32810 => array(
      'grandchildren' => false
    ),
    'Service Area' => array(
      'target' => 'map',
    ),
    'Free Quote' => array(
      'class' => 'quote',
      'target' => 'contact'
    ),
    43049 => array(
      'grandchildren' => true
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

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;800;900&display=swap"
      rel="stylesheet" />

    <title>[[title]]</title>

    <!-- Some Basic SEO -->
    <meta property="og:site_name" content="Sure-Dry Basement Systems" />
    <meta property="og:description"
      content="Sure Dry Basement Systems is the Basement Systems dealer in Wausau, Marshfield, Oshkosh, Appleton, Green Bay, Rhinelander, and Ashland areas." />

    <!-- Styles (Global)-->
    <link rel="stylesheet" type="text/css" href="https://combinatronics.com/IamStephan/sure_dry_template_v2/master/prod/template.css">

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
          <div><b>Open Now</b> <i class="closing-at">(closing at 6pm)</i></div>
          <!-- This could be a border or a character -->
          <div class="separator">|</div>
          <div class="serving">Yes, we serve <b>Seymour</b></div>
          <div class="cta">
            <span class="cta-text"> 1-800-379-3788 </span>
            <span class="cta-text-small">
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
      <!-- Hero Section -->
      <section class="hero-section-layout space-section">
        <div class="hero-bg">
          <img class="object-cover- object-bottom- img"
            src="https://cdn.treehouseinternetgroup.com/cms_images/101/hero-bg.f69949dd.jpg" />
        </div>

        <div class="logo-bg-container"></div>

        <div class="logo-container">
          <p class="text-placeholder">ACCREDITATION LOGOS COULD GO HERE</p>
        </div>

        <div class="heading-main-text-container">
          <h1 class="heading-main-text">
            Your Home.<br />
            Our Team.<br />
            <span class="heading-main-text-highlight">One Solution.</span>
          </h1>
        </div>

        <div class="heading-sub-text-container">
          <div class="heading-sub-text-container-wrapper">
            <p class="heading-sub-text">
              Foundation Repair <span class="heading-sub-text-highlight">•</span> Basement
              Waterproofing
            </p>
            <p class="heading-sub-text">
              Crawl Space Repair <span class="heading-sub-text-highlight">•</span> Concrete
              Leveling
            </p>
          </div>

          <div class="button-container">
            <a class="button"> Get Your Free Estimate </a>
          </div>
        </div>

        <div class="images-container">
          <div class="image-set-container">
            <div class="image-one">
              <img class="object-cover- img"
                src="https://cdn.treehouseinternetgroup.com/cms_images/101/hero_3.jpg" />
            </div>
            <div class="image-two">
              <img class="object-cover- img"
                src="https://cdn.treehouseinternetgroup.com/cms_images/101/hero_2.jpg" />
            </div>
          </div>
          <div class="image-set-container">
            <div class="image-three">
              <img class="object-cover- img"
                src="	https://cdn.treehouseinternetgroup.com/cms_images/101/hero_4.jpg" />
            </div>
            <div class="image-four">
              <img class="object-cover- img"
                src="	https://cdn.treehouseinternetgroup.com/cms_images/101/hero_1.jpg" />
            </div>
          </div>
        </div>
      </section>

      <!-- Services Section -->
      <section class="solutions space-section">
        <h2 class="solutions-header">Our Solutions</h2>

        <div class="solutions-blocks-container">
          <div class="block">
            <div class="image-container">
              <img class="object-cover- img"
                src="https://cdn.treehouseinternetgroup.com/cms_images/101/cracks.8e22bff8.png" />
            </div>

            <div class="info-container">
              <div class="info-heading">
                <h3 class="info-heading-text">Cracked Foundation?</h3>
              </div>

              <div class="info-detail">
                <h4 class="info-detail-heading">Foundation Repair</h4>
                <p class="info-detail-text">
                  We have permanent solutions for your foundation problems.
                </p>
              </div>

              <div class="info-cta">
                <a class="info-cta-text">LEARN MORE</a>
              </div>

              <div class="mobile-info">
                <h2 class="mobile-info-heading">Foundation Repair</h2>
              </div>
            </div>
          </div>

          <div class="block">
            <div class="image-container">
              <img class="object-cover- img"
                src="https://cdn.treehouseinternetgroup.com/cms_images/101/wet_basement.1025483f.png" />
            </div>

            <div class="info-container">
              <div class="info-heading">
                <h3 class="info-heading-text">Wet Basement?</h3>
              </div>

              <div class="info-detail">
                <h4 class="info-detail-heading">Basement Waterproofing</h4>
                <p class="info-detail-text">
                  We have lasting solutions for waterproofing your basement.
                </p>
              </div>

              <div class="info-cta">
                <a class="info-cta-text">LEARN MORE</a>
              </div>

              <div class="mobile-info">
                <h2 class="mobile-info-heading">Basement Waterproofing</h2>
              </div>
            </div>
          </div>

          <div class="block">
            <div class="image-container">
              <img class="object-cover- img"
                src="	https://cdn.treehouseinternetgroup.com/cms_images/101/crawl_space.6cc595a0.png" />
            </div>

            <div class="info-container">
              <div class="info-heading">
                <h3 class="info-heading-text">Nasty Crawl Space?</h3>
              </div>

              <div class="info-detail">
                <h4 class="info-detail-heading">Crawl Space Repair</h4>
                <p class="info-detail-text">
                  Create a healthier living space and save money on your utility
                  bills.
                </p>
              </div>

              <div class="info-cta">
                <a class="info-cta-text">LEARN MORE</a>
              </div>
              <div class="mobile-info">
                <h2 class="mobile-info-heading">Crawl Space Repair</h2>
              </div>
            </div>
          </div>

          <div class="block">
            <div class="image-container">
              <img class="object-cover- img"
                src="https://cdn.treehouseinternetgroup.com/cms_images/101/sinking_concrete.6735f8cd.png" />
            </div>

            <div class="info-container">
              <div class="info-heading">
                <h3 class="info-heading-text">Sinking Concrete?</h3>
              </div>

              <div class="info-detail">
                <h4 class="info-detail-heading">Concrete Leveling</h4>
                <p class="info-detail-text">
                  Is there sinking concrete near your home? Call for a free
                  estimate!
                </p>
              </div>

              <div class="info-cta">
                <a class="info-cta-text">LEARN MORE</a>
              </div>

              <div class="mobile-info">
                <h2 class="mobile-info-heading">Concrete Leveling</h2>
              </div>
            </div>
          </div>
        </div>

        <div class="notice-container">
          <div class="notice">
            <div class="notice-bg">
              <!-- SVG images on the CMS do not work -->
              <svg xmlns="http://www.w3.org/2000/svg" width="88.841" height="49.982" viewBox="0 0 88.841 49.982">
                <path
                  d="M88.826 27.36a6.54 6.54 0 00-.3-1.944Q84.86 13.962 81.147 2.523C80.51.555 79.733 0 77.673 0Q69.26 0 60.847.006h-49.09c-2.052 0-2.788.53-3.452 2.443Q4.334 13.893.377 25.343a6.656 6.656 0 00-.356 2.1c-.026 6.852-.016 13.7-.015 20.555 0 1.588.389 1.971 2.005 1.971h42.324v.008H86.92c1.506 0 1.923-.434 1.923-1.979-.004-6.877.005-13.758-.017-20.638zM69.786 3.166c1.376 3.091 4.086 4.114 7.161 4.468.8.092 1.183.288 1.4 1.116.93 3.512 1.948 7 2.931 10.5.061.216.092.441.114.543a41.256 41.256 0 00-4.492 1.248 3.375 3.375 0 00-2.374 3.583H47.757c1.583-.49 3.558-.992 5.444-1.727a12.817 12.817 0 003.179-1.9 7.906 7.906 0 001.886-11.033c-2.3-3.634-5.775-5.543-9.868-6.479a5.609 5.609 0 01-1.077-.315zM43.039 7.638c-.1-.814.349-1.086 1.089-1.052.259.012.52-.012.78 0 1.41.042 1.586-.289 1.762 1.529l3.722.625c-.181.524-.319.89-.434 1.263-.2.641-.55.755-1.239.672-1.559-.188-3.14-.206-4.713-.274a.888.888 0 00-.564.185c-.225.19-.534.448-.539.684a.923.923 0 00.51.688c.944.345 1.924.588 2.881.9 1.23.4 2.482.752 3.666 1.261a2.432 2.432 0 01-.063 4.567c-1.04.47-2.157.77-3.343 1.179-.21 1.838-.21 1.838-2.164 1.82h-.607c-1.1-.021-1.248-.168-1.265-1.239 0-.143-.014-.286-.022-.466l-4.9-.743a7.068 7.068 0 01.874-1.728 1.734 1.734 0 011.22-.236c1.634.119 3.262.324 4.9.438a2.809 2.809 0 001.346-.246 1.53 1.53 0 00.832-.85c.047-.232-.388-.7-.706-.839a17.429 17.429 0 00-2.132-.683c-1.294-.39-2.615-.708-3.877-1.185a2.276 2.276 0 01-1.733-2.055 2.536 2.536 0 011.543-2.416 11.621 11.621 0 012.417-.9c.523-.148.83-.3.759-.899zM8.752 15.875c.715-2.344 1.49-4.672 2.134-7.035a1.361 1.361 0 011.5-1.224 8.473 8.473 0 005.124-1.865 24.979 24.979 0 002.375-2.593h22.054a51.109 51.109 0 00-5.021 1.74 12.828 12.828 0 00-6.59 6.248 7.731 7.731 0 002.385 9.966 17.171 17.171 0 008.512 3.362.724.724 0 01.41.14H14.399a3.32 3.32 0 00-1.137-2.808 7.984 7.984 0 00-4.295-1.669c-.425-.058-.859-.055-1.464-.091.442-1.48.836-2.828 1.247-4.171zM2.83 27.826h83.194v2.672H2.828zm83.208 5.561v2.644H2.83v-2.644zm-83.21 5.547h83.163v2.672H2.825zm83.211 8.194h-83.2v-2.634h83.2z"
                  fill="#4a4a4a" />
              </svg>
            </div>

            <div class="notice-info">
              <h3 class="notice-info-heading">Financing Available</h3>
              <p class="notice-info-text">
                From waterproofing to finishing, we offer financing options for
                practically any job.
              </p>
              <p class="notice-info-text-mobile">Request Information</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Social Proof Section -->
      <section class="social-proof space-section">
        <div class="social-proof-bg-container">
          <img src="https://cdn.treehouseinternetgroup.com/cms_images/101/people.e6fbb089.jpg"
            class="object-cover- social-proof-bg img" />
          <div class="social-proof-bg-overlay"></div>
        </div>

        <div class="social-proof-content">
          <h2 class="social-proof-content-heading">
            Trusted by Homeowners Like You
          </h2>

          <div class="testimonials-container">
            <div class="testimonial">
              <div class="testimonial-body">
                <div class="testimonial-body-ratings-container">
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                </div>
                <p class="testimonial-body-review">
                  Customer review content goes here. Customer review content
                  goes here.
                </p>
                <p class="testimonial-body-customer">Customer A.</p>
              </div>
              <div class="testimonial-triangle"></div>
            </div>

            <div class="testimonial">
              <div class="testimonial-body">
                <div class="testimonial-body-ratings-container">
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                </div>
                <p class="testimonial-body-review">
                  Customer review content goes here. Customer review content
                  goes here.
                </p>
                <p class="testimonial-body-customer">Customer A.</p>
              </div>
              <div class="testimonial-triangle"></div>
            </div>

            <div class="testimonial">
              <div class="testimonial-body">
                <div class="testimonial-body-ratings-container">
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                  <svg class="testimonial-rating" xmlns="http://www.w3.org/2000/svg" width="39.66" height="41.269"
                    viewBox="0 0 39.66 41.269">
                    <path
                      d="M19.827 34.219l-12.256 7.05 2.341-14.931-9.915-10.574 13.7-2.178L19.825.001l6.128 13.585 13.7 2.178-9.915 10.574 2.341 14.931z"
                      fill="#ffd00a" />
                  </svg>
                </div>
                <p class="testimonial-body-review">
                  Customer review content goes here. Customer review content
                  goes here.
                </p>
                <p class="testimonial-body-customer">Customer A.</p>
              </div>
              <div class="testimonial-triangle"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- Features Section -->
      <section class="features space-section">
        <div class="features-card-container">
          <div class="features-card-bg-white"></div>
          <div class="features-card-bg-reset"></div>
          <div class="features-card">
            <div class="featured-card-background-image">
              <img class="object-cover- img" src="https://cdn.treehouseinternetgroup.com/cms_images/101/noise.c99464a9.png" />
            </div>

            <div class="feautures-card-main-image">
              <img class="object-cover- img" src="https://cdn.treehouseinternetgroup.com/cms_images/101/company_people.jpg" />
            </div>

            <div class="featured-card-content">
              <h2 class="featured-card-content-heading">
                Why Homeowners Choose Us
              </h2>
              <p class="featured-card-content-body">
                We believe the customer experience can and should be remarkable.
                Our goal is to provide you with the most reliable solutions and
                peace of mind about your home. At the time of your free
                inspection, you will know exactly what the problem is, exactly
                how we will fix it and exactly how much it will cost.
              </p>
            </div>

            <div class="featured-card-button">
              <a class="featured-card-button-text"> LEARN ABOUT OUR COMPANY </a>
            </div>
          </div>
        </div>

        <div class="featured-body-container">
          <p class="featured-body-text">
            Our friends and neighbors in Northeast and Central Wisconsin choose
            Sure-Dry because they expect a permanent fix that will protect their
            home and everything in it. You can expect the same thing because
            protecting homes and what’s on the inside is job one for us and a
            responsibility we take seriously. Our basement waterproofing,
            foundation repair, and concrete lifting systems have helped
            thousands of homeowners, and they can help you too. Call Sure-Dry
            today!
          </p>
        </div>

        <div class="featured-item-list">
          <div class="feautured-item">
            <div class="featured-item-icon-container">
              <svg class="featured-item-icon" xmlns="http://www.w3.org/2000/svg" width="85.19" height="81.104" viewBox="0 0 85.19 81.104"><g stroke="#c8102e"><path d="M62.999 19.229L75.133 7.095a1.561 1.561 0 00-1.1-2.665H35.994V2.551a1.8 1.8 0 00-3.6 0v53.5a1.8 1.8 0 003.6 0V34.027h38.035a1.561 1.561 0 001.1-2.665zM35.582 7.552h34.679L59.688 18.125a1.561 1.561 0 000 2.208l10.573 10.573H35.582z" fill="#c8102e" stroke-width="1.5"/><path d="M2.25 78.853l15.851-18.981 7.887 7.427 16.928-21.251L58.17 67.299l7.844-7.427 16.923 18.981z" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="4.5"/></g></svg>
            </div>

            <div class="featured-item-content">
              <h3 class="featured-item-title">Industry Leaders</h3>
              <h4 class="featured-item-subtitle">
                Exclusive patented, proven products
              </h4>
            </div>
          </div>

          <div class="feautured-item">
            <div class="featured-item-icon-container">
              <svg class="featured-item-icon" xmlns="http://www.w3.org/2000/svg" width="93.094" height="79.531" viewBox="0 0 93.094 79.531"><defs><style>.a{fill:#c8102e;stroke:#c8102e;stroke-miterlimit:10}</style></defs><path class="a" d="M76.52 51.1a3.3 3.3 0 01-2.22-1.106q-2.554-2.445-5.105-4.912l-1.967-1.9a1.786 1.786 0 01-.33-2.132 3.96 3.96 0 00-.993-5.406 3.27 3.27 0 00-2.913-.735 6.072 6.072 0 00-2.86 1.875l-.088.083a1.786 1.786 0 01-2.495-.061l-7.47-7.669a1.784 1.784 0 01-.286-2.1 14.136 14.136 0 001.749-5.058 10.735 10.735 0 00-5.48-11.07 16.937 16.937 0 00-6.546-2.122 3.187 3.187 0 01-3.114-2.5c-.161-.733-.268-2.548 2.185-3.632a24.376 24.376 0 0119.99.006 24.1 24.1 0 018.67 6.115c2.629 2.895 5.393 5.773 8.064 8.556l1.3 1.356a4.994 4.994 0 011.552 4.139 9.418 9.418 0 00.015 1.429l.021.408c.048.974.4 1.331 1.349 1.365a55.99 55.99 0 003.409-.007 3.93 3.93 0 012.986 1.237q1.8 1.843 3.613 3.665l1.818 1.837c1.6 1.614 1.6 3.165.008 4.741q-6.309 6.251-12.644 12.473A3.248 3.248 0 0176.52 51.1zm-5.909-9.61l1.061 1.023c1.616 1.559 3.222 3.108 4.834 4.651q6.11-6 12.2-12.034l-1.679-1.7c-1.215-1.226-2.432-2.451-3.635-3.688-.159-.163-.206-.164-.336-.159-1.082.028-2.359.05-3.629.005a4.686 4.686 0 01-4.787-4.756l-.019-.377a12.189 12.189 0 010-2.005c.043-.408-.037-.735-.578-1.3l-1.3-1.355c-2.689-2.8-5.47-5.7-8.132-8.628a21.92 21.92 0 00-23.378-5.746 20.221 20.221 0 016.62 2.385 14.343 14.343 0 017.237 14.607 16.754 16.754 0 01-1.555 5.145l5.383 5.526a7.31 7.31 0 013.507-1.705 6.807 6.807 0 015.838 1.574 7.4 7.4 0 012.348 8.537z"/><path class="a" d="M16.08 79.03h-.007a8.437 8.437 0 01-8.655-10.015 11.549 11.549 0 013.8-7.054q5.07-4.618 10.13-9.245 7.29-6.663 14.6-13.309c2.651-2.406 6.425-5.675 9.125-7.993a978.389 978.389 0 013.786-3.235c1.954-1.661 2.765-2.347 3.93-1.246l.015.014c1.247 1.2 2.48 2.45 3.673 3.657s2.418 2.445 3.654 3.636a1.785 1.785 0 01.084 2.483 651.42 651.42 0 01-6.956 7.579q-10.973 11.8-21.972 23.566l-6.988 7.486a10.894 10.894 0 01-8.219 3.676zm35.389-48.39c-2.686 2.276-9.213 7.856-13.128 11.41q-7.311 6.633-14.59 13.3-5.065 4.628-10.133 9.249a7.957 7.957 0 00-2.665 4.917 5.281 5.281 0 001.081 4.281 5.225 5.225 0 004.042 1.662h.005a7.358 7.358 0 005.6-2.54l6.991-7.489Q39.663 53.656 50.64 41.87a629.061 629.061 0 005.77-6.273c-.841-.831-1.667-1.668-2.476-2.486s-1.635-1.647-2.466-2.469z"/><path class="a" d="M28.583 50.308h-.018a1.778 1.778 0 01-1.281-.56c-.525-.556-3.656-3.93-5.167-5.668-.143-.165-.2-.232-.716-.188a20.629 20.629 0 01-9.184-.94A17.208 17.208 0 01.781 28.997a22.49 22.49 0 01-.2-5.485 11.339 11.339 0 01.7-2.863c.111-.321.22-.64.313-.957a1.784 1.784 0 012.949-.792l1.554 1.486c1.151 1.1 2.266 2.157 3.359 3.239.858.849 1.7 1.714 2.541 2.579.651.671 1.3 1.342 1.963 2 .568.57.754.574 1.321.019l.159-.154a295.398 295.398 0 004.223-4.154c.575-.582.621-.723-.024-1.382l-.446-.455a837.76 837.76 0 00-7.006-7.1 10.854 10.854 0 00-1.123-.918c-.225-.169-.45-.337-.668-.511a1.785 1.785 0 01.109-2.871 12.4 12.4 0 017.963-1.934A17.426 17.426 0 0135.425 22.83a15.546 15.546 0 01-.183 7.594c-.111.385-.112.43.2.743 1.42 1.433 2.555 2.69 3.755 4.022.57.632 1.149 1.274 1.776 1.952a1.783 1.783 0 01-.037 2.459c-1.791 1.831-3.727 3.516-5.6 5.145-1.839 1.6-3.742 3.258-5.477 5.028a1.784 1.784 0 01-1.276.535zm-6.929-10a3.976 3.976 0 013.156 1.428c.978 1.124 2.655 2.955 3.828 4.225 1.433-1.369 2.911-2.656 4.352-3.91 1.46-1.271 2.857-2.488 4.193-3.766l-.64-.709c-1.172-1.3-2.278-2.526-3.64-3.9a4.025 4.025 0 01-1.091-4.245 12.011 12.011 0 00.122-5.863 13.786 13.786 0 00-13.586-11.261 12.543 12.543 0 00-3.517.263c2.322 2.32 4.617 4.665 6.913 7.01l.445.456a4.192 4.192 0 01.009 6.389 281.772 281.772 0 01-4.271 4.2l-.157.153a4.193 4.193 0 01-6.339-.06c-.67-.673-1.332-1.354-1.993-2.035-.825-.849-1.65-1.7-2.49-2.53a198.34 198.34 0 00-2.773-2.68c-.016.1-.029.2-.037.3a18.952 18.952 0 00.161 4.608 13.677 13.677 0 009.191 11.231 17.1 17.1 0 007.608.718 9.4 9.4 0 01.556-.02zm37.055 38.219a9.515 9.515 0 01-6.847-2.912q-3.748-3.891-7.449-7.825-1.771-1.873-3.545-3.741l-1.782-1.88c-.838-.885-1.651-1.744-2.64-2.781a1.785 1.785 0 010-2.465l10.413-10.886a1.786 1.786 0 012.5-.074c.108.1.449.457 4.47 4.686 3.494 3.675 8.29 8.721 11.36 11.92a9.353 9.353 0 01.486 12.741 9.037 9.037 0 01-6.628 3.212c-.108.003-.223.005-.338.005zM39.866 57.759c.51.536.987 1.041 1.474 1.556l1.778 1.876q1.778 1.871 3.551 3.747 3.7 3.912 7.425 7.8a5.941 5.941 0 004.491 1.816 5.388 5.388 0 004.013-1.927 5.819 5.819 0 00-.317-7.988c-3.073-3.2-7.874-8.253-11.372-11.933l-3.1-3.256z"/></svg>
            </div>

            <div class="featured-item-content">
              <h3 class="featured-item-title">Reliable Service</h3>
              <h4 class="featured-item-subtitle">
                Transferable warranties for waterproofing systems
              </h4>
            </div>
          </div>

          <div class="feautured-item">
            <div class="featured-item-icon-container">
              <svg class="featured-item-icon" xmlns="http://www.w3.org/2000/svg" width="82.757" height="74.835" viewBox="0 0 82.757 74.835"><path d="M57.382.5a24.813 24.813 0 00-16 5.832A24.871 24.871 0 004.537 38.961a6.732 6.732 0 004.919 11.335 6.852 6.852 0 001.187-.105 6.726 6.726 0 006.628 7.921 6.847 6.847 0 001.019-.076 6.726 6.726 0 006.657 7.747 6.843 6.843 0 001.187-.105 6.737 6.737 0 0011.393 5.951l.619-.619 1.435 1.435a6.445 6.445 0 0010.991-4.1 6.447 6.447 0 009.106-6.681 6.579 6.579 0 00.827.054 6.439 6.439 0 006.393-7.275 6.574 6.574 0 00.827.054 6.445 6.445 0 005.459-9.882c2.236-2.25 3.474-3.524 3.7-3.808a24.936 24.936 0 002.741-4.3 24.622 24.622 0 002.632-11.144A24.9 24.9 0 0057.382.5zM30.247 69.383a3.57 3.57 0 01-.534-4.341 3.524 3.524 0 01.534-.692l.675-.675 1.522-1.522.614-.614.056-.056 3.085-3.085a3.533 3.533 0 012.076-1.008h0a3.61 3.61 0 01.44-.03 3.548 3.548 0 012.516 1.038h0a3.555 3.555 0 011.008 2.957h0a3.533 3.533 0 01-.986 2.052l-.022.024-3.084 3.087-2.248 2.248-.619.619a3.527 3.527 0 01-.713.546 3.562 3.562 0 01-4.32-.548zm-7.816-7.816a3.57 3.57 0 01-.511-4.379 3.523 3.523 0 01.511-.654l2.228-2.228 3.724-3.724a3.569 3.569 0 015.033 0h0a3.569 3.569 0 010 5.033l-3.745 3.745-2.208 2.208a3.523 3.523 0 01-.678.526 3.563 3.563 0 01-4.355-.526zM6.94 46.083a3.573 3.573 0 010-5.033l2.343-2.343 3.61-3.61a3.57 3.57 0 015.033 0h0a3.569 3.569 0 010 5.033l-3.668 3.668-2.285 2.285a3.569 3.569 0 01-5.033 0zm7.816 7.816h0a3.571 3.571 0 01-.482-4.428 3.53 3.53 0 01.482-.605l2.267-2.267 3.686-3.685a3.569 3.569 0 015.033 0h0a3.569 3.569 0 010 5.033l-3.712 3.712-2.24 2.24a3.569 3.569 0 01-5.033 0zm29.391 17.257a3.262 3.262 0 01-2.313-.954l-1.435-1.435 1.3-1.3 1.788-1.788a6.741 6.741 0 001.083-1.417q.108-.187.2-.381h0l1.178 1.178.514.514a3.282 3.282 0 010 4.627h0a3.243 3.243 0 01-.637.491 3.278 3.278 0 01-1.678.465zm31.93-36.419a20.966 20.966 0 01-2.3 3.6c-.239.281-1.366 1.434-3.287 3.364l-8-7.993a1.588 1.588 0 00-2.159.081h0a1.589 1.589 0 00-.081 2.159l3.189 3.2h0l4.806 4.806 1.794 1.794a3.237 3.237 0 01.776 1.254 3.263 3.263 0 01-4.158 4.146 3.237 3.237 0 01-1.245-.773l-1.8-1.8-5.489-5.489h0l-2.8-2.8h0a1.81 1.81 0 00-2.56 0l-.034.034a1.81 1.81 0 000 2.56l.1.1 8.176 8.176 1.81 1.81a3.238 3.238 0 01.768 1.231 3.279 3.279 0 01-.768 3.4h0a3.262 3.262 0 01-2.314.954 3.3 3.3 0 01-1.108-.2 3.236 3.236 0 01-1.2-.759l-1.829-1.829-5.461-5.461h0l-2.8-2.8h0a1.811 1.811 0 00-2.56 0l-.033.034a1.81 1.81 0 000 2.56l.186.186 8.059 8.059 1.842 1.842a3.238 3.238 0 01.751 1.183 3.279 3.279 0 01-.751 3.444h0a3.261 3.261 0 01-2.313.954 3.3 3.3 0 01-1.165-.215 3.236 3.236 0 01-1.149-.739l-1.867-1.867-3.924-3.924a6.726 6.726 0 00-4.57-4.57 6.276 6.276 0 00-.235-.065 4.254 4.254 0 00-.91-.162 6.709 6.709 0 00-1.936.063 6.726 6.726 0 00-6.628-7.921 6.859 6.859 0 00-1.019.076 6.727 6.727 0 00-6.657-7.752 6.85 6.85 0 00-1.187.105 6.738 6.738 0 00-11.39-5.948l-3.226 3.227c-.262-.439-.51-.887-.739-1.344a20.681 20.681 0 01-2.205-9.352 20.893 20.893 0 0134.463-15.9h0l-13.1 12.723-.025.025a4.644 4.644 0 00-1.532 4.413c.432 1.578 1.891 2.7 4.334 3.349a15.329 15.329 0 004 .568 17.063 17.063 0 006.415-1.378l.07-.03a22.889 22.889 0 005.386-3.6 8.82 8.82 0 012.14-1.522 9 9 0 013.044.477 18.616 18.616 0 006.418.79l.076-.007c.285-.031 7.023-.821 10.6-5.41a1.669 1.669 0 00-2.632-2.053c-2.6 3.335-7.864 4.084-8.307 4.142a15.37 15.37 0 01-5.3-.689 11.34 11.34 0 00-4.377-.557l-.158.026a9.054 9.054 0 00-3.646 2.242 19.88 19.88 0 01-4.591 3.108 11.918 11.918 0 01-8.288.667c-1.495-.394-1.926-.858-1.966-1-.033-.119.094-.579.678-1.176l13.605-13.256.982-.884a20.9 20.9 0 0133.306 24.3z" fill="#c8102e" stroke="#c8102e"/></svg>
            </div>

            <div class="featured-item-content">
              <h3 class="featured-item-title">Trustworthy Crew</h3>
              <h4 class="featured-item-subtitle">
                Trained, experienced contractors
              </h4>
            </div>
          </div>

          <div class="feautured-item">
            <div class="featured-item-icon-container">
              <svg class="featured-item-icon" xmlns="http://www.w3.org/2000/svg" width="73.923" height="73.923" viewBox="0 0 73.923 73.923"><defs><style>.a{fill:#c8102e;stroke:#c8102e}</style></defs><path class="a" d="M36.962 73.423a36.461 36.461 0 1136.465-36.462 36.5 36.5 0 01-36.465 36.462zm0-69.34a32.879 32.879 0 1032.879 32.879A32.916 32.916 0 0036.962 4.083z"/><g transform="translate(20.213 21.34)"><path class="a" d="M16.745 36.636c-7.991 0-14.492-5.87-14.492-13.084a1.7 1.7 0 013.383 0c0 5.53 4.983 10.029 11.108 10.029s11.108-4.5 11.108-10.029a1.7 1.7 0 013.383 0c.005 7.215-6.496 13.084-14.49 13.084z"/><circle class="a" cx="5.739" cy="5.739" r="5.739"/><circle class="a" cx="5.739" cy="5.739" r="5.739" transform="translate(22.018)"/></g></svg>
            </div>

            <div class="featured-item-content">
              <h3 class="featured-item-title">Customer Support</h3>
              <h4 class="featured-item-subtitle">
                Savings of up to 50% over other methods
              </h4>
            </div>
          </div>
        </div>
      </section>

      <!-- About Section -->
      <section class="about space-section">
        <h2 class="about-title">Exceptional Quality and Service Since 1994</h2>
        <h3 class="about-subtitle">
          OVER 27,000 SATISFIED SURE-DRY CUSTOMERS IN NORTHEAST AND CENTRAL
          WISCONSIN
        </h3>
        <p class="about-content">
          Sure-Dry has secured its position as the largest and most trusted
          basement waterproofing and foundation repair contractor in Northeast
          and Central Wisconsin. With access to more than 30 patented products,
          comprehensive training, and support through international
          organizations, we help set and redefine the standards for the industry
          and your home.
        </p>

        <p class="about-content">
          The feel and function of a home shouldn’t be compromised by a flooded
          basement, cracked or bowing walls, musty crawl space, or sinking
          concrete. Our goal is to provide homeowners with the most reliable
          solutions to those issues and peace of mind knowing the job will be
          done right.
        </p>

        <p class="about-content">
          Whether you live in Green Bay, Fox Cities, Oshkosh, Wausau, the
          Lakeshore or up in Hodag country, we can help! If you need basement
          waterproofing, foundation repair, crawl space repair, concrete
          lifting, and leveling, please contact us today for your
          <b>FREE ESTIMATE</b>.
        </p>

        <div class="about-cta-container">
          <a class="button"> Get Your Free Estimate </a>
        </div>
      </section>
      
      
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
          <img class="object-cover- img" src="https://cdn.treehouseinternetgroup.com/cms_images/101/map.678dc703.png" />
        </div>

        <div class="service-areas-bg-container">
          <img class="img service-areas-bg-image" src="https://cdn.treehouseinternetgroup.com/cms_images/101/noise.c99464a9.png" />
        </div>

        <div class="service-areas-heading-container">
          <h2 class="service-areas-heading">
            Proudly serving Central & Northeast Wisconsin - Green Bay, Oshkosh,
            Appleton, Wausau
          </h2>
        </div>

        <div class="service-areas-content-container">
          <h3 class="service-areas-content-title">Wisconsin</h3>
          <p class="service-areas-content-text">
            Adell Algoma Baileys Harbor Belgium Brussels Cascade Casco Cedar
            Grove Cleveland Denmark Egg Harbor Elkhart Lake Ellison Bay Fish
            Creek Forestville Francis Creek Fredonia Kellnersville Kewaunee Kiel
            Kohler Luxemburg Manitowoc Maribel Marinette Mishicot New Franken
            Newton Oostburg Plymouth Port Washington Random Lake Reedsville
            Sheboygan Sheboygan Falls Sister Bay Sturgeon Bay Two Rivers Valders
            Waldo Washington Island Whitelaw
          </p>
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
          <a class="button"> Get Your Free Estimate </a>

          <label class="cta-label"> Or call us at </label>
          <p class="cta-number">1-800-379-3788</p>
        </div>

        <!-- Bottom Nav -->
        <?= $bottomNav->generateSuperMarkup(); ?>
      </section>

      <!-- Bottom Banner -->
      <section class="bottom-footer">
        <span class="text-span">
          <span class="text-bold"> Sure-Dry Basement Systems </span>
          © 2021
        </span>
        <span class="text-separator"> | </span>
        <span class="text-span"> 754 W. Airport Road, Menasha, WI 54952 </span>
        <span class="text-separator"> | </span>
        <span class="text-span">4205 Stewart Ave., Wausau, WI 54401</span>
        <span class="text-separator"> | </span>
        <span class="text-span">1-800-379-3788</span>
      </section>
    </footer>

    <!-- Selected scripts -->
    <?= $bottomInject; ?>

    <!-- City Page core scripts -->
    <? if($isCityPage): ?>
    [[custom_core_v3_9_js]]
    <? endif; ?>
  </body>

</html>