import { GUI } from "dat.gui";

export class NavigationPlugin {
  rootGUI: GUI;

  params = {
    enabled: window.location.hostname !== "localhost",
    appendUrl: "?cache=0&dev_template=1&test=1",
    openProd: this.openProd,
  };

  constructor(rootGUI: GUI) {
    this.rootGUI = rootGUI;

    this._createControls();

    this.attatchListenersToLinks();
  }

  private _createControls() {
    const navigationFolder = this.rootGUI.addFolder("Navigation");

    navigationFolder.open();

    navigationFolder.add(this.params, "enabled").name("Keep inside DEV");
    navigationFolder.add(this.params, "appendUrl").name("DEV Append URL");
    navigationFolder.add(this.params, "openProd").name("Open in Production");
  }

  openProd() {
    const { origin, pathname } = window.location;
    window.open(origin + pathname);
  }

  attatchListenersToLinks() {
    const LinkTags = document.querySelectorAll<HTMLAnchorElement>("a[href]");

    LinkTags.forEach((linkElem) => {
      linkElem?.addEventListener("click", this.linkAction.bind(this));
    });
  }

  linkAction(event: MouseEvent) {
    const link = event.currentTarget as HTMLAnchorElement;
    /**
     * First make sure there is a actual value present
     * Sometimes the target event seems to attach itself
     * to none anchor tags
     */
    if (this.params.enabled) {
      if (link?.attributes?.getNamedItem?.("href")?.value) {
        event.preventDefault();

        window.location.href =
          link.attributes.getNamedItem("href").value + this.params.appendUrl;
      } else {
        // I want to see what gets selected
        console.log(link);

        // Make sure the user wants to leave the test enviroment
        if (
          !window.confirm(
            "This link could not be intercepted. Are you sure you want to leave the test enviroment?"
          )
        ) {
          event.preventDefault();
        }
      }
    }
  }
}
