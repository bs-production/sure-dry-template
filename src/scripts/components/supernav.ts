/**
 * Mobile navigation
 * ==================
 * I can't use the focus-within selector since it's
 * not supported ni Edge 15
 *
 * I'm also accounting for multiple supernavs (clones)
 *
 * TODO: Handle click outside
 */

class MobileNav {
  supernavs: NodeListOf<HTMLDivElement>;

  previousTop: number = window.scrollY;
  currentTop: number = 0;
  viewportWidth: number = window.innerWidth;
  scrollThreshold: number = 25;

  constructor() {
    this._getSupernavs();
    this._configureSuperNav();

    this._handleScroll();
    this._handleClickOutside();
  }

  private _getSupernavs() {
    this.supernavs = document.querySelectorAll(".supernav");
  }

  private _configureSuperNav() {
    this.supernavs.forEach((supernav) => {
      const toggleButton =
        supernav.querySelector<HTMLButtonElement>(".menu-button");
      const rootNode =
        supernav.querySelector<HTMLUListElement>(".level-1.small");
      const subMenus =
        supernav.querySelectorAll<HTMLLIElement>(".with-children");

      toggleButton.addEventListener("click", () => {
        if (rootNode.classList.contains("show")) {
          this._resetAll(rootNode, subMenus);
        }
        rootNode.classList.toggle("show");
      });

      subMenus.forEach((subMenu) => {
        const moveButton = subMenu.querySelector<HTMLDivElement>(".dropdown");
        const list = subMenu.querySelector<HTMLUListElement>(".level-2");

        moveButton.addEventListener("click", () =>
          this._goDeeper(subMenus, rootNode, list)
        );

        list
          .querySelector<HTMLButtonElement>(".return-level-1")
          .addEventListener("click", () => this._goShallow(rootNode));
      });
    });
  }

  private _goDeeper(
    submenus: NodeListOf<HTMLLIElement>,
    rootNode: HTMLUListElement,
    list: HTMLUListElement
  ) {
    this._resetSubMenus(submenus);
    rootNode.classList.add("nest");
    list.classList.add("show");
  }

  private _goShallow(rootNode: HTMLUListElement) {
    rootNode.classList.remove("nest");
  }

  private _resetSubMenus(subMenus: NodeListOf<HTMLLIElement>) {
    subMenus.forEach((submenu) => {
      submenu.querySelector(".level-2").classList.remove("show");
    });
  }

  private _resetAll(
    rootNode: HTMLUListElement,
    submenus: NodeListOf<HTMLLIElement>
  ) {
    rootNode.classList.remove("nest");

    submenus.forEach((submenu) => {
      submenu.querySelector(".level-2").classList.remove("show");
    });
  }

  private _hideOnScroll() {
    this.currentTop = window.scrollY;

    const isFastEnough =
      Math.abs(this.previousTop - this.currentTop) > this.scrollThreshold;

    if (isFastEnough) {
      this.supernavs.forEach((supernav) => {
        const rootNode =
          supernav.querySelector<HTMLUListElement>(".level-1.small");
        const subMenus =
          supernav.querySelectorAll<HTMLLIElement>(".with-children");

        if (rootNode.classList.contains("show")) {
          this._resetAll(rootNode, subMenus);
        }
        rootNode.classList.remove("show");
      });
    }

    this.previousTop = this.currentTop;
  }

  private _handleScroll() {
    let handler = () =>
      !window.requestAnimationFrame
        ? setTimeout(this._hideOnScroll.bind(this), 250)
        : requestAnimationFrame(this._hideOnScroll.bind(this));

    document.addEventListener("scroll", handler, {
      passive: true,
    });
  }

  private _handleClickOutside() {
    this.supernavs.forEach((supernav) => {
      const toggleButton =
        supernav.querySelector<HTMLButtonElement>(".menu-button");
      const rootNode =
        supernav.querySelector<HTMLUListElement>(".level-1.small");
      const subMenus =
        supernav.querySelectorAll<HTMLLIElement>(".with-children");

      document.addEventListener("click", (e) => {
        const withinboundries = e.composedPath().includes(rootNode);
        const toggleInPath = e.composedPath().includes(toggleButton);

        if (!withinboundries && !toggleInPath) {
          if (rootNode.classList.contains("show")) {
            this._resetAll(rootNode, subMenus);
          }
          rootNode.classList.remove("show");
        }
      });
    });
  }
}

try {
  new MobileNav();
} catch (e) {
  console.error(`Could not init Mobile supernav: ${e}`);
}
