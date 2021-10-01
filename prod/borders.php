<?php
  global $thePage, $cmsPageData, $siteTokens;
  $siteConfig = new SiteConfig($thePage, $csmPageData, $siteTokens);
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    [[top-inject]]
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
        [[top-nav-links]]

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
        Content Page Wrapper
        =====================
      -->
      <? if ($siteConfig->pageType == "CONTENT"): ?>
      <!-- Content Section -->
      <section
        class="content-template-article space-section <?= $siteConfig->showSilo ? "" : 'no-silo'; ?> <?= $siteConfig->showBreadcrumbs ? "" : 'no-breadcrumbs'; ?>"
      >
        <? if($siteConfig->showBreadcrumbs): ?>
        <nav aria-label="Breadcrumb" class="content-template-breadcrumbs">
          [[breadcrumbs]]
        </nav>
        <? endif; ?>

        <? if ($siteConfig->showSilo): ?>
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
          <? if($siteConfig->pageType == "CONTENT"): ?>
        </article>
      </section>
      <? endif; ?>

      <!--
        Service Areas (Somewhat page agnostic)
        ==============
      -->
      <? if($siteConfig->showServiceAreas): ?>
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
          <p class="cta-number"><a href="tel:<?= $phonenumber; ?>">[[phone]]</a></p>
          <p class="footer-address">[[display_addresses]]</p>
        </div>

        <!-- Bottom Nav -->
        [[bottom-nav-links]]
      </section>

      <!-- Bottom Banner -->
      <section class="bottom-footer">
        <span class="text-span">
          <span class="text-bold"> © <?= date('Y'); ?> Sure-Dry Basement Systems </span>
        </span>
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

    [[bottom-inject]]

    <!-- City Page core scripts -->
    <? if( ($siteConfig->isCityPage) || (stristr($thePage,"meet-the-team")) || (strpos($thePage, 'confirmation')) ): ?>
    [[custom_core_v3_9_js]]
    <? endif; ?>
    <script defer src="https://connect.podium.com/widget.js#API_TOKEN=abfc03c0-6d23-4ce4-b9b9-b284af5de0ce" id="podium-widget" data-api-token="abfc03c0-6d23-4ce4-b9b9-b284af5de0ce"></script> 
  </body>
</html>