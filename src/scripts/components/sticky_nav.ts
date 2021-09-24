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
  realSupernavTag: string = "real-supernav";
  cloneSupernavTag: string = "cloned-supernav";
  sticky: boolean = false;

  parentClone: HTMLElement;
  logoClone: HTMLElement;
  supernavClone: HTMLElement;

  constructor() {
    this._createClassDefinitions();
    this._tagRealSupernav();
    this._createCompansatingStyles();

    this._createParentClone();
    this._createSuperNavClone();
    this._createLogoClone();

    this._insertCloneElements();

    this._handleScroll();
  }

  private _createClassDefinitions() {
    const style = document.createElement("style");
    style.type = "text/css";

    style.innerHTML = `
      .sticky-nav-show { 
          opacity: 1 !important;
          transform: translateY(0px) !important;
          pointer-events: auto !important;
       }
    `;

    document.getElementsByTagName("head")[0].appendChild(style);
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
        transform: translateY(-25px);
        top: 0px;
        left: 0px;
        right: 0px;
        z-index: 1000;
        background-color: white;
        border-bottom: 1px solid rgba(35, 31, 32, 0.10);
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

    supernavClone.classList.remove(this.realSupernavTag);
    supernavClone.classList.add(this.cloneSupernavTag);

    this.supernavClone = supernavClone;
  }

  private _insertCloneElements() {
    document.querySelector("body").prepend(this.parentClone);

    this.parentClone.append(this.supernavClone);
    this.parentClone.append(this.logoClone);
  }

  private _scrollHandler() {
    const realSupernavOffsetTop = document.querySelector<HTMLElement>(
      `.supernav.${this.realSupernavTag}`
    ).offsetTop;

    if (window.scrollY >= realSupernavOffsetTop && !this.sticky) {
      this.sticky = true;
      this.parentClone.classList.add("sticky-nav-show");
    }
    if (window.scrollY <= realSupernavOffsetTop && this.sticky) {
      this.sticky = false;
      this.parentClone.classList.remove("sticky-nav-show");
    }
  }

  private _handleScroll() {
    // Initial run setup
    this._scrollHandler();

    window.addEventListener("scroll", this._scrollHandler.bind(this), {
      passive: true,
    });
  }
}

try {
  new StickyNav();
} catch (e) {
  console.error(`Could not init Sticky Nav: ${e}`);
}
