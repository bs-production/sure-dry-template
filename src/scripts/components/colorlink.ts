/**
 * Description:
 * =============
 * The name is misleading. This is essentially just a modal type component
 * that gives more information about a certain item
 */
import { zoomable } from "./zoom";

class Colorlink {
  colorLinks?: NodeListOf<HTMLAnchorElement>;
  contentIDs: Array<string>;

  templateOptions = {
    templateID: "zoom-template",
    imageContainerID: "zoom-container",
    contentID: "zoom-content",
  };
  activeColorLinkInnerHTML: string;

  zoomInstance: any;

  constructor() {
    this.colorLinks = document.querySelectorAll(".colorlink");

    this._createTemplateItem();
    this._configure();
    this._run();
    this._handleEvents();
  }

  private _createTemplateItem() {
    const template = document.createElement("template");
    template.setAttribute("id", this.templateOptions.templateID);
    template.innerHTML = `  
      <div class="template-colorlink-wrapper">
        <div class="colorlink-content">
          <aside id="${this.templateOptions.imageContainerID}"></aside>
          <article id="${this.templateOptions.contentID}"></article>
        </div>
      </div>
    `;

    document.querySelector("body").appendChild(template);
  }

  private _configure() {
    this.colorLinks.forEach((colorLink) => {
      this._preventNavigation(colorLink);
      this._setHDLink(colorLink);
      this._handleColorLinkAction(colorLink);
    });
  }

  private _handleColorLinkAction(colorLink: HTMLAnchorElement) {
    colorLink.addEventListener("click", (env) => {
      const colorLinkContent = document.querySelector<HTMLDivElement>(
        `${colorLink.getAttribute("href")} .popupimagetext`
      );

      this.activeColorLinkInnerHTML = colorLinkContent.innerHTML;
    });
  }

  private _preventNavigation(colorLink: HTMLAnchorElement) {
    colorLink.addEventListener("click", (e) => e.preventDefault());
  }

  private _setHDLink(lightboxLink: HTMLAnchorElement) {
    const zoomableImage = lightboxLink.querySelector<HTMLImageElement>("img");
    const hdLink = document.querySelector<HTMLImageElement>(
      `${lightboxLink.getAttribute("href")} img`
    ).src;

    zoomableImage.setAttribute("data-zoom-src", hdLink);
  }

  private _setColorLinkContent() {
    /**
     * NOTE:
     * ======
     * It needs to happen on the next execution loop
     * other wise the content is not yet in the dom
     */
    setTimeout(() => {
      const templateBodyContainer = document.querySelector(
        `#${this.templateOptions.contentID}`
      );

      if (templateBodyContainer)
        templateBodyContainer.innerHTML = this.activeColorLinkInnerHTML;
    }, 0);
    document.body.style.overflow = "hidden";
  }

  private _run() {
    this.zoomInstance = zoomable(".colorlink img", {
      template: `#${this.templateOptions.templateID}`,
      container: `#${this.templateOptions.imageContainerID}`,
      background: "rgba(0, 0, 0, 0.75)",
      margin: 32,
    });
  }

  private _handleEvents() {
    this.zoomInstance?.on("open", this._setColorLinkContent.bind(this));

    this.zoomInstance?.on("closed", () => {
      document.body.style.overflow = "auto";
    });
  }
}

try {
  new Colorlink();
} catch (e) {
  console.error(`Could not init Colorlink: ${e}`);
}
