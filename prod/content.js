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
      let o = "";

      (o += `\n @media (min-width: 1023px) {
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
          ? (this.styleCompansationDefRef.innerHTML = o)
          : ((this.styleCompansationDefRef = document.createElement("style")),
            (this.styleCompansationDefRef.type = "text/css"),
            (this.styleCompansationDefRef.innerHTML = o),
            document
              .getElementsByTagName("head")[0]
              .appendChild(this.styleCompansationDefRef));
    }

    _cloneNav() {
      const e = document.body,
        t = document.querySelector(".navigation-layout"),
        n = t.cloneNode(!0),
        o = document.createElement("nav");
      o.classList.add("navigation-clone"),
        t.parentElement.classList.add("navigation-real"),
        o.append(n),
        e.prepend(o),
        (this.navClone = o),
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
        this.navClone.classList.remove("sticky-nav-show");
        e?.classList?.remove?.("push-down");
        this.previousTop = this.currentTop;
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

  let t = [];

  try {
    document.querySelectorAll(".accordion").forEach((e, n) => {
      t.push({
        container: e,
        activeElement: null,
      }),
        e.querySelectorAll(".accordion-navigation").forEach((o) => {
          o.classList.contains("active") && (t[n].activeElement = o),
            (o.querySelector("a").onclick = (n) => {
              n.preventDefault(),
                (function (e, n) {
                  for (let o = 0; o < t.length; o++)
                    if (e === t[o].container) {
                      if (
                        t[o].activeElement &&
                        (t[o].activeElement.classList.remove("active"),
                        t[o].activeElement === n)
                      ) {
                        t[o].activeElement = null;
                        break;
                      }

                      n.classList.add("active"), (t[o].activeElement = n);
                      break;
                    }
                })(e, o);
            });
        });
    });
  } catch (e) {
    console.error(`Could not init Accordion: ${e}`);
  }

  try {
    !(function () {
      const e = document.querySelector("#silo-menu-button"),
        t = document.querySelector("#silo-container");

      e?.addEventListener?.("click", () => {
        t.classList.toggle("show");
      });
    })();
  } catch (e) {
    console.error(`Could not init Silo: ${e}`);
  }

  class n {
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
          o = e.querySelectorAll(".with-children");
        t.addEventListener("click", () => {
          n.classList.contains("show") && this._resetAll(n, o),
            n.classList.toggle("show");
        }),
          o.forEach((e) => {
            const t = e.querySelector(".dropdown"),
              i = e.querySelector(".level-2");
            t.addEventListener("click", () => this._goDeeper(o, n, i)),
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
          o = e.querySelectorAll(".with-children");
        document.addEventListener("click", (e) => {
          const i = e.composedPath().includes(n),
            r = e.composedPath().includes(t);
          i ||
            r ||
            (n.classList.contains("show") && this._resetAll(n, o),
            n.classList.remove("show"));
        });
      });
    }
  }

  try {
    new n();
  } catch (e) {
    console.error(`Could not init Mobile supernav: ${e}`);
  }

  /*!medium-zoom 1.0.6 | MIT License | https://github.com/francoischalifour/medium-zoom*/
  var o =
      Object.assign ||
      function (e) {
        for (var t = 1; t < arguments.length; t++) {
          var n = arguments[t];
          for (var o in n)
            Object.prototype.hasOwnProperty.call(n, o) && (e[o] = n[o]);
        }

        return e;
      },
    i = function (e) {
      return "IMG" === e.tagName;
    },
    r = function (e) {
      return e && 1 === e.nodeType;
    },
    a = function (e) {
      return ".svg" === (e.currentSrc || e.src).substr(-4).toLowerCase();
    },
    s = function (e) {
      try {
        return Array.isArray(e)
          ? e.filter(i)
          : (function (e) {
              return NodeList.prototype.isPrototypeOf(e);
            })(e)
          ? [].slice.call(e).filter(i)
          : r(e)
          ? [e].filter(i)
          : "string" == typeof e
          ? [].slice.call(document.querySelectorAll(e)).filter(i)
          : [];
      } catch (e) {
        throw new TypeError(
          "The provided selector is invalid.\nExpects a CSS selector, a Node element, a NodeList or an array.\nSee: https://github.com/francoischalifour/medium-zoom"
        );
      }
    },
    l = function (e) {
      var t = document.createElement("div");
      return (
        t.classList.add("medium-zoom-overlay"), (t.style.background = e), t
      );
    },
    c = function (e) {
      var t = e.getBoundingClientRect(),
        n = t.top,
        o = t.left,
        i = t.width,
        r = t.height,
        a = e.cloneNode(),
        s =
          window.pageYOffset ||
          document.documentElement.scrollTop ||
          document.body.scrollTop ||
          0,
        l =
          window.pageXOffset ||
          document.documentElement.scrollLeft ||
          document.body.scrollLeft ||
          0;
      return (
        a.removeAttribute("id"),
        (a.style.position = "absolute"),
        (a.style.top = n + s + "px"),
        (a.style.left = o + l + "px"),
        (a.style.width = i + "px"),
        (a.style.height = r + "px"),
        (a.style.transform = ""),
        a
      );
    },
    d = function (e, t) {
      var n = o(
        {
          bubbles: !1,
          cancelable: !1,
          detail: void 0,
        },

        t
      );
      if ("function" == typeof window.CustomEvent) return new CustomEvent(e, n);
      var i = document.createEvent("CustomEvent");
      return i.initCustomEvent(e, n.bubbles, n.cancelable, n.detail), i;
    },
    m = function e(t) {
      var n =
          arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
        i =
          window.Promise ||
          function (e) {
            function t() {}

            e(t, t);
          },
        m = function (e) {
          var t = e.target;

          t !== x
            ? -1 !== _.indexOf(t) &&
              L({
                target: t,
              })
            : b();
        },
        u = function () {
          if (!T && A.original) {
            var e =
              window.pageYOffset ||
              document.documentElement.scrollTop ||
              document.body.scrollTop ||
              0;
            Math.abs(H - e) > q.scrollOffset && setTimeout(b, 150);
          }
        },
        h = function (e) {
          var t = e.key || e.keyCode;
          ("Escape" !== t && "Esc" !== t && 27 !== t) || b();
        },
        p = function () {
          var e =
              arguments.length > 0 && void 0 !== arguments[0]
                ? arguments[0]
                : {},
            t = e;

          if (
            (e.background && (x.style.background = e.background),
            e.container &&
              e.container instanceof Object &&
              (t.container = o(
                {},

                q.container,
                e.container
              )),
            e.template)
          ) {
            var n = r(e.template)
              ? e.template
              : document.querySelector(e.template);
            t.template = n;
          }

          return (
            (q = o(
              {},

              q,
              t
            )),
            _.forEach(function (e) {
              e.dispatchEvent(
                d("medium-zoom:update", {
                  detail: {
                    zoom: D,
                  },
                })
              );
            }),
            D
          );
        },
        v = function () {
          var t =
            arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {};

          return e(
            o(
              {},

              q,
              t
            )
          );
        },
        f = function () {
          for (var e = arguments.length, t = Array(e), n = 0; n < e; n++)
            t[n] = arguments[n];

          var o = t.reduce(function (e, t) {
            return [].concat(e, s(t));
          }, []);

          return (
            o
              .filter(function (e) {
                return -1 === _.indexOf(e);
              })
              .forEach(function (e) {
                _.push(e), e.classList.add("medium-zoom-image");
              }),
            k.forEach(function (e) {
              var t = e.type,
                n = e.listener,
                i = e.options;
              o.forEach(function (e) {
                e.addEventListener(t, n, i);
              });
            }),
            D
          );
        },
        g = function () {
          for (var e = arguments.length, t = Array(e), n = 0; n < e; n++)
            t[n] = arguments[n];
          A.zoomed && b();

          var o =
            t.length > 0
              ? t.reduce(function (e, t) {
                  return [].concat(e, s(t));
                }, [])
              : _;

          return (
            o.forEach(function (e) {
              e.classList.remove("medium-zoom-image"),
                e.dispatchEvent(
                  d("medium-zoom:detach", {
                    detail: {
                      zoom: D,
                    },
                  })
                );
            }),
            (_ = _.filter(function (e) {
              return -1 === o.indexOf(e);
            })),
            D
          );
        },
        y = function (e, t) {
          var n =
            arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {};

          return (
            _.forEach(function (o) {
              o.addEventListener("medium-zoom:" + e, t, n);
            }),
            k.push({
              type: "medium-zoom:" + e,
              listener: t,
              options: n,
            }),
            D
          );
        },
        w = function (e, t) {
          var n =
            arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {};

          return (
            _.forEach(function (o) {
              o.removeEventListener("medium-zoom:" + e, t, n);
            }),
            (k = k.filter(function (n) {
              return !(
                n.type === "medium-zoom:" + e &&
                n.listener.toString() === t.toString()
              );
            })),
            D
          );
        },
        z = function () {
          var e =
              arguments.length > 0 && void 0 !== arguments[0]
                ? arguments[0]
                : {},
            t = e.target,
            n = function () {
              var e = {
                  width: document.documentElement.clientWidth,
                  height: document.documentElement.clientHeight,
                  left: 0,
                  top: 0,
                  right: 0,
                  bottom: 0,
                },
                t = void 0,
                n = void 0;

              if (q.container)
                if (q.container instanceof Object)
                  (t =
                    (e = o(
                      {},

                      e,
                      q.container
                    )).width -
                    e.left -
                    e.right -
                    2 * q.margin),
                    (n = e.height - e.top - e.bottom - 2 * q.margin);
                else {
                  var i = (
                      r(q.container)
                        ? q.container
                        : document.querySelector(q.container)
                    ).getBoundingClientRect(),
                    s = i.width,
                    l = i.height,
                    c = i.left,
                    d = i.top;

                  e = o(
                    {},

                    e,
                    {
                      width: s,
                      height: l,
                      left: c,
                      top: d,
                    }
                  );
                }

              (t = t || e.width - 2 * q.margin),
                (n = n || e.height - 2 * q.margin);
              var m = A.zoomedHd || A.original,
                u = a(m) ? t : m.naturalWidth || t,
                h = a(m) ? n : m.naturalHeight || n,
                p = m.getBoundingClientRect(),
                v = p.top,
                f = p.left,
                g = p.width,
                y = p.height,
                w = Math.min(u, t) / g,
                z = Math.min(h, n) / y,
                b = Math.min(w, z),
                L =
                  "scale(" +
                  b +
                  ") translate3d(" +
                  ((t - g) / 2 - f + q.margin + e.left) / b +
                  "px, " +
                  ((n - y) / 2 - v + q.margin + e.top) / b +
                  "px, 0)";
              (A.zoomed.style.transform = L),
                A.zoomedHd && (A.zoomedHd.style.transform = L);
            };

          return new i(function (e) {
            if (t && -1 === _.indexOf(t)) e(D);
            else {
              if (A.zoomed) e(D);
              else {
                if (t) A.original = t;
                else {
                  if (!(_.length > 0)) return void e(D);
                  var o = _;
                  A.original = o[0];
                }

                if (
                  (A.original.dispatchEvent(
                    d("medium-zoom:open", {
                      detail: {
                        zoom: D,
                      },
                    })
                  ),
                  (H =
                    window.pageYOffset ||
                    document.documentElement.scrollTop ||
                    document.body.scrollTop ||
                    0),
                  (T = !0),
                  (A.zoomed = c(A.original)),
                  document.body.appendChild(x),
                  q.template)
                ) {
                  var i = r(q.template)
                    ? q.template
                    : document.querySelector(q.template);
                  (A.template = document.createElement("div")),
                    A.template.appendChild(i.content.cloneNode(!0)),
                    document.body.appendChild(A.template);
                }

                if (
                  (document.body.appendChild(A.zoomed),
                  window.requestAnimationFrame(function () {
                    document.body.classList.add("medium-zoom--opened");
                  }),
                  A.original.classList.add("medium-zoom-image--hidden"),
                  A.zoomed.classList.add("medium-zoom-image--opened"),
                  A.zoomed.addEventListener("click", b),
                  A.zoomed.addEventListener("transitionend", function t() {
                    (T = !1),
                      A.zoomed.removeEventListener("transitionend", t),
                      A.original.dispatchEvent(
                        d("medium-zoom:opened", {
                          detail: {
                            zoom: D,
                          },
                        })
                      ),
                      e(D);
                  }),
                  A.original.getAttribute("data-zoom-src"))
                ) {
                  (A.zoomedHd = A.zoomed.cloneNode()),
                    A.zoomedHd.removeAttribute("srcset"),
                    A.zoomedHd.removeAttribute("sizes"),
                    (A.zoomedHd.src = A.zoomed.getAttribute("data-zoom-src")),
                    (A.zoomedHd.onerror = function () {
                      clearInterval(a),
                        console.warn(
                          "Unable to reach the zoom image target " +
                            A.zoomedHd.src
                        ),
                        (A.zoomedHd = null),
                        n();
                    });
                  var a = setInterval(function () {
                    A.zoomedHd.complete &&
                      (clearInterval(a),
                      A.zoomedHd.classList.add("medium-zoom-image--opened"),
                      A.zoomedHd.addEventListener("click", b),
                      document.body.appendChild(A.zoomedHd),
                      n());
                  }, 10);
                } else if (A.original.hasAttribute("srcset")) {
                  (A.zoomedHd = A.zoomed.cloneNode()),
                    A.zoomedHd.removeAttribute("sizes"),
                    A.zoomedHd.removeAttribute("loading");
                  var s = A.zoomedHd.addEventListener("load", function () {
                    A.zoomedHd.removeEventListener("load", s),
                      A.zoomedHd.classList.add("medium-zoom-image--opened"),
                      A.zoomedHd.addEventListener("click", b),
                      document.body.appendChild(A.zoomedHd),
                      n();
                  });
                } else n();
              }
            }
          });
        },
        b = function () {
          return new i(function (e) {
            if (!T && A.original) {
              (T = !0),
                document.body.classList.remove("medium-zoom--opened"),
                (A.zoomed.style.transform = ""),
                A.zoomedHd && (A.zoomedHd.style.transform = ""),
                A.template &&
                  ((A.template.style.transition = "opacity 150ms"),
                  (A.template.style.opacity = 0)),
                A.original.dispatchEvent(
                  d("medium-zoom:close", {
                    detail: {
                      zoom: D,
                    },
                  })
                ),
                A.zoomed.addEventListener("transitionend", function t() {
                  A.original.classList.remove("medium-zoom-image--hidden"),
                    document.body.removeChild(A.zoomed),
                    A.zoomedHd && document.body.removeChild(A.zoomedHd),
                    document.body.removeChild(x),
                    A.zoomed.classList.remove("medium-zoom-image--opened"),
                    A.template && document.body.removeChild(A.template),
                    (T = !1),
                    A.zoomed.removeEventListener("transitionend", t),
                    A.original.dispatchEvent(
                      d("medium-zoom:closed", {
                        detail: {
                          zoom: D,
                        },
                      })
                    ),
                    (A.original = null),
                    (A.zoomed = null),
                    (A.zoomedHd = null),
                    (A.template = null),
                    e(D);
                });
            } else e(D);
          });
        },
        L = function () {
          var e =
              arguments.length > 0 && void 0 !== arguments[0]
                ? arguments[0]
                : {},
            t = e.target;

          return A.original
            ? b()
            : z({
                target: t,
              });
        },
        E = function () {
          return q;
        },
        C = function () {
          return _;
        },
        S = function () {
          return A.original;
        },
        _ = [],
        k = [],
        T = !1,
        H = 0,
        q = n,
        A = {
          original: null,
          zoomed: null,
          zoomedHd: null,
          template: null,
        };

      "[object Object]" === Object.prototype.toString.call(t)
        ? (q = t)
        : (t || "string" == typeof t) && f(t),
        (q = o(
          {
            margin: 0,
            background: "#fff",
            scrollOffset: 40,
            container: null,
            template: null,
          },

          q
        ));
      var x = l(q.background);
      document.addEventListener("click", m),
        document.addEventListener("keyup", h),
        document.addEventListener("scroll", u),
        window.addEventListener("resize", b);

      var D = {
        open: z,
        close: b,
        toggle: L,
        update: p,
        clone: v,
        attach: f,
        detach: g,
        on: y,
        off: w,
        getOptions: E,
        getImages: C,
        getZoomedImage: S,
      };

      return D;
    };

  !(function (e, t) {
    void 0 === t && (t = {});
    var n = t.insertAt;

    if (e && "undefined" != typeof document) {
      var o = document.head || document.getElementsByTagName("head")[0],
        i = document.createElement("style");
      (i.type = "text/css"),
        "top" === n && o.firstChild
          ? o.insertBefore(i, o.firstChild)
          : o.appendChild(i),
        i.styleSheet
          ? (i.styleSheet.cssText = e)
          : i.appendChild(document.createTextNode(e));
    }
  })(
    ".medium-zoom-overlay{position:fixed;top:0;right:0;bottom:0;left:0;opacity:0;transition:opacity .3s;will-change:opacity}.medium-zoom--opened .medium-zoom-overlay{cursor:pointer;cursor:zoom-out;opacity:1}.medium-zoom-image{cursor:pointer;cursor:zoom-in;transition:transform .3s cubic-bezier(.2,0,.2,1)!important}.medium-zoom-image--hidden{visibility:hidden}.medium-zoom-image--opened{position:relative;cursor:pointer;cursor:zoom-out;will-change:transform}"
  );

  class u {
    constructor() {
      (this.lightboxLinks = document.querySelectorAll(".lightbox")),
        this._configure(),
        this._run();
    }

    _configure() {
      this.lightboxLinks.forEach((e) => {
        this._setHDLink(e), this._preventNavigation(e);
      });
    }

    _setHDLink(e) {
      const t = e.querySelector("img"),
        n = e.href;
      t.setAttribute("data-zoom-src", n);
    }

    _preventNavigation(e) {
      e?.addEventListener?.("click", (e) => e.preventDefault());
    }

    _run() {
      m(".lightbox img", {
        background: "rgba(0, 0, 0, 0.75)",
        margin: 32,
      });
    }
  }

  try {
    new u();
  } catch (e) {
    console.error(`Could not init Lightbox: ${e}`);
  }

  !(function () {
    const e = document.querySelector("#related_page");

    e?.addEventListener?.("change", (t) => {
      "0" !== e.value &&
        (window.location.href = e.value + "?cache=0&dev_template=1&test=1");
    });
  })();

  class h {
    templateOptions = {
      templateID: "zoom-template",
      imageContainerID: "zoom-container",
      contentID: "zoom-content",
    };

    constructor() {
      (this.colorLinks = document.querySelectorAll(".colorlink")),
        this._createTemplateItem(),
        this._configure(),
        this._run(),
        this._handleEvents();
    }

    _createTemplateItem() {
      const e = document.createElement("template");
      e.setAttribute("id", this.templateOptions.templateID),
        (e.innerHTML = ` \n <div class="template-colorlink-wrapper">\n <div class="colorlink-content">\n <aside id="${this.templateOptions.imageContainerID}"></aside>\n <article id="${this.templateOptions.contentID}"></article>\n </div>\n </div>\n `),
        document.querySelector("body").appendChild(e);
    }

    _configure() {
      this.colorLinks.forEach((e) => {
        this._preventNavigation(e),
          this._setHDLink(e),
          this._handleColorLinkAction(e);
      });
    }

    _handleColorLinkAction(e) {
      e.addEventListener("click", (t) => {
        const n = document.querySelector(
          `${e.getAttribute("href")} .popupimagetext`
        );
        this.activeColorLinkInnerHTML = n.innerHTML;
      });
    }

    _preventNavigation(e) {
      e.addEventListener("click", (e) => e.preventDefault());
    }

    _setHDLink(e) {
      const t = e.querySelector("img"),
        n = document.querySelector(`${e.getAttribute("href")} img`).src;
      t.setAttribute("data-zoom-src", n);
    }

    _setColorLinkContent() {
      setTimeout(() => {
        const e = document.querySelector(`#${this.templateOptions.contentID}`);
        e && (e.innerHTML = this.activeColorLinkInnerHTML);
      }, 0),
        (document.body.style.overflow = "hidden");
    }

    _run() {
      this.zoomInstance = m(".colorlink img", {
        template: `#${this.templateOptions.templateID}`,
        container: `#${this.templateOptions.imageContainerID}`,
        background: "rgba(0, 0, 0, 0.75)",
        margin: 32,
      });
    }

    _handleEvents() {
      this.zoomInstance?.on?.("open", this._setColorLinkContent.bind(this)),
        this.zoomInstance?.on?.("closed", () => {
          document.body.style.overflow = "auto";
        });
    }
  }

  try {
    new h();
  } catch (e) {
    console.error(`Could not init Colorlink: ${e}`);
  }

  let p = [];

  try {
    document.querySelectorAll(".qa-wrap").forEach((e, t) => {
      p.push({
        container: e,
        activeElement: null,
      }),
        e.querySelectorAll(".qa-item").forEach((n) => {
          n.classList.contains("active") && (p[t].activeElement = n),
            (n.querySelector(".qa-header").onclick = (t) => {
              !(function (e, t) {
                for (let n = 0; n < p.length; n++)
                  if (e === p[n].container) {
                    if (
                      p[n].activeElement &&
                      (p[n].activeElement.classList.remove("active"),
                      p[n].activeElement === t)
                    ) {
                      p[n].activeElement = null;
                      break;
                    }

                    t.classList.add("active"), (p[n].activeElement = t);
                    break;
                  }
              })(e, n);
            });
        });
    });
  } catch (e) {
    console.error(`Could not init QA: ${e}`);
  }

  try {
    !(function () {
      const e = document.querySelector("#content_selector"),
        t = document.querySelector("#tabs-written"),
        n = document.querySelector("#tabs-video");

      e?.addEventListener?.("change", (o) => {
        "video" === e.value &&
          (t?.classList?.add?.("hide"), n?.classList?.remove?.("hide")),
          "written" === e.value &&
            (t?.classList?.remove?.("hide"), n?.classList?.add?.("hide"));
      });
    })();
  } catch (e) {
    console.error(`Could not init Content Selector: ${e}`);
  }
})();
