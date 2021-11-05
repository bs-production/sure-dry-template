/**
 * NOTE:
 * =====
 * I'm simply cloning the contents of the actuall nav
 * and then just injecting them. I want the entire script
 * to handle the nav even the styling.
 *
 * This needs to be imported before the actual super nav
 * to allow the script to also account for the cloned
 * element
 *
 * PROS:
 *  - Just pulling the script would remove the feature
 *  - Non intrusive (Kinda)
 *
 * CONS:
 *  - Increase DOM elements
 *  - Could have a performance impact on older devices
 */

class StickyNavAlt {
  // Config
  realSupernavTag: string = "real-supernav";
  cloneSupernavTag: string = "cloned-supernav";

  // References
  styleClassDefRef: HTMLStyleElement;
  styleCompansationDefRef: HTMLStyleElement;
  navClone: HTMLElement;
  navReal: HTMLElement;

  // Meta Info
  previousTop: number = window.scrollY;
  currentTop: number = 0;
  viewportWidth: number = window.innerWidth;
  scrollThreshold: number = 25;

  constructor() {
    this._createClassDefinitions();
    // this._createCompansatingStyles();
    this._siloFixes();

    this._cloneNav();

    this._handleScroll();
    this._handleResizing();
  }

  private _createClassDefinitions() {
    const stylesToInsert = `
      .navigation-clone {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: transparent;
        z-index: 1000;
        border-bottom: 1px solid transparent;
        pointer-events: none;
        transition: all 100ms ease-in-out;
      }

      .navigation-clone .supernav {
        opacity: 0;
        transition: all 150ms ease-in-out;
      }

      .navigation-clone .banner-text {
        pointer-events: auto;
      }

      .navigation-clone .logo-container {
        opacity: 0;
        transition: all 150ms ease-in-out;
      }

      .sticky-nav-show {
        background-color: white;
        border-bottom-color: rgba(35, 31, 32, 0.10);
        pointer-events: auto;
      }

      .sticky-nav-show.navigation-clone .supernav {
        opacity: 1;
      }

      .sticky-nav-show.navigation-clone .logo-container {
        opacity: 1;
      }
    `;

    if (this.styleClassDefRef) {
      this.styleClassDefRef.innerHTML = stylesToInsert;
    } else {
      this.styleClassDefRef = document.createElement("style");
      this.styleClassDefRef.type = "text/css";
      this.styleClassDefRef.innerHTML = stylesToInsert;

      document
        .getElementsByTagName("head")[0]
        .appendChild(this.styleClassDefRef);
    }
  }

  /**
   * Some styles do not take into account
   * the sticky nav. So I'm just compansating here
   */
  private _createCompansatingStyles() {
    const defaultTopStyle = 16;
    const navHeight = 72;

    let stylesToInsert = ``;

    /**
     * SILO
     * ========
     */
    stylesToInsert += `
      @media (min-width: 1023px) {
        #silo-container {
          top: ${defaultTopStyle + navHeight}px !important;
        }
      }
    `;

    if (this.styleCompansationDefRef) {
      this.styleCompansationDefRef.innerHTML = stylesToInsert;
    } else {
      this.styleCompansationDefRef = document.createElement("style");
      this.styleCompansationDefRef.type = "text/css";
      this.styleCompansationDefRef.innerHTML = stylesToInsert;

      document
        .getElementsByTagName("head")[0]
        .appendChild(this.styleCompansationDefRef);
    }
  }

  private _siloFixes() {
    const defaultTopStyle = 18;
    const bannerHeight = document
      .querySelector(".banner")
      .getBoundingClientRect().height;
    const supernavHeight = document
      .querySelector(".supernav")
      .getBoundingClientRect().height;
    const viewportHeight = window.innerHeight;

    let stylesToInsert = ``;

    /**
     * SILO
     * ========
     */
    stylesToInsert += `
      @media (min-width: 1023px) {
        #silo-container {
          top: ${defaultTopStyle + bannerHeight}px !important;
          max-height: ${
            viewportHeight - (defaultTopStyle * 2 + bannerHeight)
          }px !important;
          transition: all 125ms ease-in-out;
          overflow: overlay;
        }


        #silo-container.push-down {
          top: ${defaultTopStyle + bannerHeight + supernavHeight}px !important;
          max-height: ${
            viewportHeight -
            (defaultTopStyle * 2 + bannerHeight + supernavHeight)
          }px !important;
        }
      }


      @media (min-width: 1279px) {
        #silo-container {
          top: ${defaultTopStyle}px !important;
        }
      }
    `;

    if (this.styleCompansationDefRef) {
      this.styleCompansationDefRef.innerHTML = stylesToInsert;
    } else {
      this.styleCompansationDefRef = document.createElement("style");
      this.styleCompansationDefRef.type = "text/css";
      this.styleCompansationDefRef.innerHTML = stylesToInsert;

      document
        .getElementsByTagName("head")[0]
        .appendChild(this.styleCompansationDefRef);
    }
  }

  private _cloneNav() {
    const bodyElement = document.body;
    const navigationElement = document.querySelector(".navigation-layout");

    const navigationClone = navigationElement.cloneNode(true);
    const navigationCloneContainer = document.createElement("nav");

    navigationCloneContainer.classList.add("navigation-clone");
    navigationElement.parentElement.classList.add("navigation-real");

    navigationCloneContainer.append(navigationClone);
    bodyElement.prepend(navigationCloneContainer);

    this.navClone = navigationCloneContainer;
    this.navReal = navigationElement.parentElement;
  }

  private _autoHideNavigation() {
    const silo = document.querySelector("#silo-container");

    this.currentTop = window.scrollY;
    const isFastEnough =
      Math.abs(this.previousTop - this.currentTop) > this.scrollThreshold;

    if (this.currentTop >= this.navReal.getBoundingClientRect().height) {
      // Checking can now happen
      if (isFastEnough) {
        if (this.previousTop >= this.currentTop) {
          this.navClone.classList.add("sticky-nav-show");
          silo?.classList.add("push-down");
        } else {
          this.navClone.classList.remove("sticky-nav-show");
          silo?.classList.remove("push-down");
        }
      }
    } else {
      this.navClone.classList.remove("sticky-nav-show");
      silo?.classList.remove("push-down");
    }

    this.previousTop = this.currentTop;
  }

  private _resizeHandler() {
    // On mobile this will also fire on scrolling since the viewport height may change
    if (this.viewportWidth !== window.innerWidth) {
      this._createClassDefinitions();
      this._siloFixes();
      this.viewportWidth = window.innerWidth;
    }
  }

  private _handleScroll() {
    // Initial run setup
    this._autoHideNavigation();

    let handler = () =>
      !window.requestAnimationFrame
        ? setTimeout(this._autoHideNavigation.bind(this), 250)
        : requestAnimationFrame(this._autoHideNavigation.bind(this));

    window.addEventListener("scroll", handler, {
      passive: true,
    });
  }

  private _handleResizing() {
    this._resizeHandler();

    let handler = () =>
      !window.requestAnimationFrame
        ? setTimeout(this._resizeHandler.bind(this), 250)
        : requestAnimationFrame(this._resizeHandler.bind(this));

    window.addEventListener("resize", handler, {
      passive: true,
    });
  }
}

try {
  new StickyNavAlt();
} catch (e) {
  console.error(`Could not init Sticky Nav: ${e}`);
}
