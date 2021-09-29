/**
 * NOTE:
 * =====
 * I'm simply cloning the contents of the actuall nav
 * and then just injecting them. I want the entire script
 * to handle the nav even the styling.
 *
 * This needs to be imported before the actual super nav
 * to allow the script to also accound for the cloned
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

class StickyNav {
  // Config
  realSupernavTag: string = "real-supernav";
  cloneSupernavTag: string = "cloned-supernav";

  // References
  styleRef: HTMLStyleElement;

  // Cloned elements
  parentClone: HTMLElement;
  navBGClone: HTMLElement;
  logoClone: HTMLElement;
  supernavClone: HTMLElement;
  bannerTextClone: HTMLElement;
  bannerBGClone: HTMLElement;

  // Meta Info
  previousTop: number = window.scrollY;
  currentTop: number = 0;
  viewportWidth: number = window.innerWidth;
  scrollThreshold: number = 50;
  shouldShowSticky: boolean = false;

  constructor() {
    this._tagRealSupernav();
    this._createCompansatingStyles();

    this._createParentClone();
    this._createSuperNavClone();
    this._createLogoClone();
    this._createBannerTextClone();
    this._createBannerBGClone();
    this._createNavBGClone();

    this._createClassDefinitions();

    this._insertCloneElements();

    this._handleScroll();
    this._handleResizing();
  }

  private _tagRealSupernav() {
    const realNav = document.querySelector(".supernav");

    realNav.classList.add(this.realSupernavTag);
  }

  /**
   * Some styles do not take into account
   * the sticky nav. So I'm just compansating here
   */
  private _createCompansatingStyles() {
    /**
     * SILO
     * ======
     */
    const siloContainer =
      document.querySelector<HTMLElement>("#silo-container");

    if (siloContainer) {
      const head = document.head || document.getElementsByTagName("head")[0];
      const styles = document.createElement("style");

      head.appendChild(styles);
      styles.type = "text/css";

      const defaultTopStyle = 16;
      const navHeight = 72;
      const css = `
        @media (min-width: 1023px) {
          #silo-container {
            top: ${defaultTopStyle + navHeight}px !important;
          }
        }
      `;

      styles.appendChild(document.createTextNode(css));
    }
  }

  private _createParentClone() {
    const parent = document.createElement("nav");
    // Removes tabs, new lines and double spaces
    const reqexNoSpace = /(\r\n|\n|\r|\t|(\s\s+))/gm;

    parent.setAttribute(
      "style",
      `
        position: fixed;
        opacity: 0;
        transform: translateY(-100%);
        top: 0px;
        left: 0px;
        right: 0px;
        z-index: 1000;
        
        transition: all 150ms ease-in-out;
        pointer-events: none;
    `.replace(reqexNoSpace, "")
    );

    parent.setAttribute("class", "navigation-layout");

    this.parentClone = parent;
  }

  private _createLogoClone() {
    const logoContainer = document
      .querySelector<HTMLDivElement>(".logo-container")
      .cloneNode(true) as HTMLDivElement;

    logoContainer.setAttribute(
      "style",
      `
      margin-top: 20px;
      margin-bottom: 20px;
    `
    );

    this.logoClone = logoContainer;
  }

  private _createSuperNavClone() {
    const supernavClone = document
      .querySelector<HTMLDivElement>(".supernav")
      .cloneNode(true) as HTMLDivElement;

    supernavClone.setAttribute(
      "style",
      `
          z-index: 1;
        `
    );

    supernavClone.classList.remove(this.realSupernavTag);
    supernavClone.classList.add(this.cloneSupernavTag);

    this.supernavClone = supernavClone;
  }

  private _createBannerTextClone() {
    const bannerTextContainer = document
      .querySelector<HTMLDivElement>(".banner-text")
      .cloneNode(true) as HTMLDivElement;

    bannerTextContainer.setAttribute(
      "style",
      `
        z-index: -1;
        grid-row-start: 3;
        grid-row-end: 4;
      `
    );

    this.bannerTextClone = bannerTextContainer as HTMLElement;
  }

  private _createBannerBGClone() {
    const bannerBGContainer = document
      .querySelector<HTMLDivElement>(".banner")
      .cloneNode(true) as HTMLDivElement;

    bannerBGContainer.setAttribute(
      "style",
      `
        z-index: -1;
        grid-row-start: 3;
        grid-row-end: 4;
      `
    );

    this.bannerBGClone = bannerBGContainer as HTMLElement;
  }

  private _createNavBGClone() {
    const navBG = document.createElement("div");
    // Removes tabs, new lines and double spaces
    const reqexNoSpace = /(\r\n|\n|\r|\t|(\s\s+))/gm;

    navBG.setAttribute(
      "style",
      `
        background-color: white;
        border-bottom: 1px solid rgba(35, 31, 32, 0.10);
        z-index: 0;
        grid-column-start: 1;
        grid-column-end: -1;
        grid-row-start: 1;
        grid-row-end: -1;
    `.replace(reqexNoSpace, "")
    );

    navBG.setAttribute("class", "navigation-layout");

    this.navBGClone = navBG;
  }

  private _insertCloneElements() {
    document.querySelector("body").prepend(this.parentClone);

    this.parentClone.append(this.navBGClone);
    this.parentClone.append(this.supernavClone);
    this.parentClone.append(this.logoClone);
    this.parentClone.append(this.bannerBGClone);
    this.parentClone.append(this.bannerTextClone);
  }

  private _createClassDefinitions() {
    const supernav = document.querySelector(".supernav");

    const stylesToInsert = `
    .sticky-nav-show-partial {
      opacity: 1 !important;
      transform: translateY(-${
        supernav.getBoundingClientRect().height
      }px) !important;
      pointer-events: auto !important;
    }

    .sticky-nav-show { 
        opacity: 1 !important;
        transform: translateY(0px) !important;
        pointer-events: auto !important;
     }
  `;

    if (this.styleRef) {
      this.styleRef.innerHTML = stylesToInsert;
    } else {
      this.styleRef = document.createElement("style");
      this.styleRef.type = "text/css";
      this.styleRef.innerHTML = stylesToInsert;

      document.getElementsByTagName("head")[0].appendChild(this.styleRef);
    }
  }

  private _autoHideNavigation() {
    const realSupernav = document.querySelector<HTMLElement>(
      `.supernav.${this.realSupernavTag}`
    );
    const realSupernavOffsetTop = realSupernav.offsetTop;
    const realSupernavHeight = realSupernav.getBoundingClientRect().height;

    this.currentTop = window.scrollY;

    const isFastEnough =
      Math.abs(this.previousTop - this.currentTop) > this.scrollThreshold;

    const containsPartialClasses =
      this.parentClone.classList.contains('sticky-nav-show"') ||
      this.parentClone.classList.contains("sticky-nav-show-partial");

    // Check partials
    if (this.currentTop >= realSupernavOffsetTop + realSupernavHeight) {
      if (isFastEnough && containsPartialClasses) {
        if (this.previousTop <= this.currentTop) {
          // partial show
          this.parentClone.classList.add("sticky-nav-show-partial");
          this.parentClone.classList.remove("sticky-nav-show");
        } else {
          // full show
          this.parentClone.classList.add("sticky-nav-show");
          this.parentClone.classList.remove("sticky-nav-show-partial");
        }
      } else if (
        (!containsPartialClasses && isFastEnough) ||
        (!containsPartialClasses && !isFastEnough)
      ) {
        if (this.previousTop <= this.currentTop) {
          // partial show
          this.parentClone.classList.add("sticky-nav-show-partial");
        } else {
          // full show
          this.parentClone.classList.add("sticky-nav-show");
        }
      }
    } else {
      this.parentClone.classList.remove("sticky-nav-show-partial");
      this.parentClone.classList.remove("sticky-nav-show");
    }

    this.previousTop = this.currentTop;
  }

  private _resizeHandler() {
    // On mobile this will also fire on scrolling since the viewport height may change
    if (this.viewportWidth !== window.innerWidth) {
      this._createClassDefinitions();
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
  new StickyNav();
} catch (e) {
  console.error(`Could not init Sticky Nav: ${e}`);
}
