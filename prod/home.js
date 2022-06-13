!(function () {
  class e {
    realSupernavTag = "real-supernav";
    cloneSupernavTag = "cloned-supernav";
    previousTop = window.scrollY;
    currentTop = 0;
    viewportWidth = window.innerWidth;
    scrollThreshold = 25;

    constructor() {
      this._createClassDefinitions(),
        this._siloFixes(),
        this._cloneNav(),
        this._handleScroll(),
        this._handleResizing();
    }

    _createClassDefinitions() {
      const e =
        "\n      .navigation-clone {\n        position: fixed;\n        top: 0;\n        left: 0;\n        right: 0;\n        background: transparent;\n        z-index: 1000;\n        border-bottom: 1px solid transparent;\n        pointer-events: none;\n        transition: all 100ms ease-in-out;\n      }\n\n      .navigation-clone .supernav {\n        opacity: 0;\n        transition: all 150ms ease-in-out;\n      }\n\n      .navigation-clone .banner-text {\n        pointer-events: auto;\n      }\n\n      .navigation-clone .logo-container {\n        opacity: 0;\n        transition: all 150ms ease-in-out;\n      }\n\n      .sticky-nav-show {\n        background-color: white;\n        border-bottom-color: rgba(35, 31, 32, 0.10);\n        pointer-events: auto;\n      }\n\n      .sticky-nav-show.navigation-clone .supernav {\n        opacity: 1;\n      }\n\n      .sticky-nav-show.navigation-clone .logo-container {\n        opacity: 1;\n      }\n    ";
      this.styleClassDefRef
        ? (this.styleClassDefRef.innerHTML = e)
        : ((this.styleClassDefRef = document.createElement("style")),
          (this.styleClassDefRef.type = "text/css"),
          (this.styleClassDefRef.innerHTML = e),
          document
            .getElementsByTagName("head")[0]
            .appendChild(this.styleClassDefRef));
    }

    _createCompansatingStyles() {
      let e = "";
      (e +=
        "\n      @media (min-width: 1023px) {\n        #silo-container {\n          top: 88px !important;\n        }\n      }\n    "),
        this.styleCompansationDefRef
          ? (this.styleCompansationDefRef.innerHTML = e)
          : ((this.styleCompansationDefRef = document.createElement("style")),
            (this.styleCompansationDefRef.type = "text/css"),
            (this.styleCompansationDefRef.innerHTML = e),
            document
              .getElementsByTagName("head")[0]
              .appendChild(this.styleCompansationDefRef));
    }

    _siloFixes() {
      const e = document
          .querySelector(".banner")
          .getBoundingClientRect().height,
        t = document.querySelector(".supernav").getBoundingClientRect().height,
        n = window.innerHeight;
      let s = "";

      (s += `\n @media (min-width: 1023px) {
                \n #silo-container {
                    \n top: ${18 + e}px !important;
                    \n max-height: ${n - (36 + e)}px !important;
                    \n transition: all 125ms ease-in-out;
                    \n overflow: overlay;
                    \n
                }

                \n\n\n #silo-container.push-down {
                    \n top: ${18 + e + t}px !important;
                    \n max-height: ${n - (36 + e + t)}px !important;
                    \n
                }

                \n
            }

            \n\n\n @media (min-width: 1279px) {
                \n #silo-container {
                    \n top: 18px !important;
                    \n
                }

                \n
            }

            \n `),
        this.styleCompansationDefRef
          ? (this.styleCompansationDefRef.innerHTML = s)
          : ((this.styleCompansationDefRef = document.createElement("style")),
            (this.styleCompansationDefRef.type = "text/css"),
            (this.styleCompansationDefRef.innerHTML = s),
            document
              .getElementsByTagName("head")[0]
              .appendChild(this.styleCompansationDefRef));
    }

    _cloneNav() {
      const e = document.body,
        t = document.querySelector(".navigation-layout"),
        n = t.cloneNode(!0),
        s = document.createElement("nav");
      s.classList.add("navigation-clone"),
        t.parentElement.classList.add("navigation-real"),
        s.append(n),
        e.prepend(s),
        (this.navClone = s),
        (this.navReal = t.parentElement);
    }

    _autoHideNavigation() {
      const e = document.querySelector("#silo-container");
      this.currentTop = window.scrollY;
      const t =
        Math.abs(this.previousTop - this.currentTop) > this.scrollThreshold;

      if (this.currentTop >= this.navReal.getBoundingClientRect().height && t) {
          this.navClone.classList.add("sticky-nav-show");
          e?.classList?.add?.("push-down");
      } else {
        this.navClone.classList.remove("sticky-nav-show")
        e?.classList?.remove?.("push-down")
        this.previousTop = this.currentTop
      }
    }

    _resizeHandler() {
      this.viewportWidth !== window.innerWidth &&
        (this._createClassDefinitions(),
        this._siloFixes(),
        (this.viewportWidth = window.innerWidth));
    }

    _handleScroll() {
      this._autoHideNavigation();

      window.addEventListener(
        "scroll",
        () =>
          window.requestAnimationFrame
            ? requestAnimationFrame(this._autoHideNavigation.bind(this))
            : setTimeout(this._autoHideNavigation.bind(this), 250),
        {
          passive: !0,
        }
      );
    }

    _handleResizing() {
      this._resizeHandler();

      window.addEventListener(
        "resize",
        () =>
          window.requestAnimationFrame
            ? requestAnimationFrame(this._resizeHandler.bind(this))
            : setTimeout(this._resizeHandler.bind(this), 250),
        {
          passive: !0,
        }
      );
    }
  }

  try {
    new e();
  } catch (e) {
    console.error(`Could not init Sticky Nav: ${e}`);
  }

  class t {
    previousTop = window.scrollY;
    currentTop = 0;
    viewportWidth = window.innerWidth;
    scrollThreshold = 25;

    constructor() {
      this._getSupernavs(),
        this._configureSuperNav(),
        this._handleScroll(),
        this._handleClickOutside();
    }

    _getSupernavs() {
      this.supernavs = document.querySelectorAll(".supernav");
    }

    _configureSuperNav() {
      this.supernavs.forEach((e) => {
        const t = e.querySelector(".menu-button"),
          n = e.querySelector(".level-1.small"),
          s = e.querySelectorAll(".with-children");
        t.addEventListener("click", () => {
          n.classList.contains("show") && this._resetAll(n, s),
            n.classList.toggle("show");
        }),
          s.forEach((e) => {
            const t = e.querySelector(".dropdown"),
              i = e.querySelector(".level-2");
            t.addEventListener("click", () => this._goDeeper(s, n, i)),
              i
                .querySelector(".return-level-1")
                .addEventListener("click", () => this._goShallow(n));
          });
      });
    }

    _goDeeper(e, t, n) {
      this._resetSubMenus(e), t.classList.add("nest"), n.classList.add("show");
    }

    _goShallow(e) {
      e.classList.remove("nest");
    }

    _resetSubMenus(e) {
      e.forEach((e) => {
        e.querySelector(".level-2").classList.remove("show");
      });
    }

    _resetAll(e, t) {
      e.classList.remove("nest"),
        t.forEach((e) => {
          e.querySelector(".level-2").classList.remove("show");
        });
    }

    _hideOnScroll() {
      if (window.innerWidth >= 768) {
        this.currentTop = window.scrollY;

        Math.abs(this.previousTop - this.currentTop) > this.scrollThreshold &&
          this.supernavs.forEach((e) => {
            const t = e.querySelector(".level-1.small"),
              n = e.querySelectorAll(".with-children");
            t.classList.contains("show") && this._resetAll(t, n),
              t.classList.remove("show");
          }),
          (this.previousTop = this.currentTop);
      }
    }

    _handleScroll() {
      document.addEventListener(
        "scroll",
        () =>
          window.requestAnimationFrame
            ? requestAnimationFrame(this._hideOnScroll.bind(this))
            : setTimeout(this._hideOnScroll.bind(this), 250),
        {
          passive: !0,
        }
      );
    }

    _handleClickOutside() {
      this.supernavs.forEach((e) => {
        const t = e.querySelector(".menu-button"),
          n = e.querySelector(".level-1.small"),
          s = e.querySelectorAll(".with-children");
        document.addEventListener("click", (e) => {
          const i = e.composedPath().includes(n),
            o = e.composedPath().includes(t);
          i ||
            o ||
            (n.classList.contains("show") && this._resetAll(n, s),
            n.classList.remove("show"));
        });
      });
    }
  }

  try {
    new t();
  } catch (e) {
    console.error(`Could not init Mobile supernav: ${e}`);
  }
})();

